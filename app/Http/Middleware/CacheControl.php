<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Clear user-specific caches when refresh parameter is present
        if ($request->get('refresh') && Auth::check()) {
            $user_id = Auth::id();
            
            // Clear dashboard caches
            Cache::forget("user_dashboard_{$user_id}");
            Cache::forget("user_orders_{$user_id}");
            Cache::forget("user_attempts_{$user_id}");
            
            // Clear test-specific caches
            $pattern_keys = [
                "user_attempts_{$user_id}",
                "attempted_{$user_id}",
                "mytests_{$user_id}",
                "my_products_{$user_id}",
                "my_orders_{$user_id}"
            ];
            
            foreach ($pattern_keys as $key) {
                Cache::forget($key);
            }
            
            // Clear test score caches (pattern matching would be ideal but not all cache drivers support it)
            // This is a trade-off - we clear user-specific caches but not all test score caches
            
            $request->session()->flash('cache_cleared', 'Cache refreshed successfully!');
        }

        return $next($request);
    }
}