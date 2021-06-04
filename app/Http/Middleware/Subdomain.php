<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Storage;
use Closure;

class Subdomain
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
        $domain = request()->getHost();
        if( $domain!='prep.firstacademy.in' && $domain!='fa.test' && $domain!='project.test' && $domain!='onlinelibrary.test' && $domain!= 'test.piofx.com' && $domain!= 'test.p24.in' ){
             $filename = '../storage/app/cache/clients/'.$domain.'.json';

             if(file_exists($filename)){
                $client = json_decode(file_get_contents($filename));
                if(Storage::disk('public')->has('clients/'.$client->id.'.png'))
                    $client->logo = url('/').'/storage/clients/'.$client->id.'.png';
                elseif(Storage::disk('public')->has('clients/'.$client->id.'.jpg'))
                    $client->logo = url('/').'/storage/clients/'.$client->id.'.jpg';
                else
                    $client->logo = url('/').'img/piofx.png';

                
                if($client->status==0)
                    abort(403,'Site is not published');
                $request->session()->put('client',$client);
            }else{
                abort(404,'Site not found');
            } 
        }
        return $next($request);

    }
}
