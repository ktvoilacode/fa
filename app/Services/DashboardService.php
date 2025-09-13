<?php

namespace App\Services;

use App\Models\Test\Test;
use App\Models\Test\Attempt;
use App\Models\Product\Product;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Get optimized dashboard data for a user
     *
     * @param User $user
     * @param string $search_term
     * @param string $product_search
     * @return array
     */
    public function getDashboardData(User $user, $search_term = '', $product_search = '')
    {
        $cache_key = "user_dashboard_v2_{$user->id}_" . md5($search_term . $product_search);
        
        return Cache::remember($cache_key, 600, function () use ($user, $search_term, $product_search) {
            // Get orders with eager loading to prevent N+1 queries
            $orders = $user->orders()
                ->with(['product.tests.testtype', 'test.testtype'])
                ->where('status', 1)
                ->orderBy('expiry', 'desc')
                ->get();

            $data = $this->processOrdersData($orders);
            
            // Get filtered tests and products
            $data['tests'] = $this->getFilteredTests($data['test_ids'], $search_term);
            $data['products'] = $this->getFilteredProducts($data['product_ids'], $product_search);
            
            // Get attempt data
            $data['attempts'] = $this->getUserAttempts($user->id, $data['test_ids']);
            
            return $data;
        });
    }

    /**
     * Process orders data efficiently
     *
     * @param Collection $orders
     * @return array
     */
    private function processOrdersData($orders)
    {
        $test_ids = collect();
        $product_ids = collect();
        $status = [];
        $expiry = [];
        $product_status = [];
        $product_expiry = [];

        foreach ($orders as $order) {
            $is_active = strtotime($order->expiry) > strtotime(date('Y-m-d'));
            $status_text = $is_active ? 'Active' : 'Expired';

            // Handle direct test orders
            if ($order->test_id) {
                $test_ids->push($order->test_id);
                $expiry[$order->test_id] = $order->expiry;
                $status[$order->test_id] = $status_text;
            }

            // Handle product orders
            if ($order->product_id && $order->product) {
                $product_ids->push($order->product_id);
                $product_status[$order->product_id] = $status_text;
                $product_expiry[$order->product_id] = $order->expiry;

                // Tests are already eager loaded
                foreach ($order->product->tests as $test) {
                    if (!$test_ids->contains($test->id)) {
                        $test_ids->push($test->id);
                        $expiry[$test->id] = $order->expiry;
                        $status[$test->id] = $status_text;
                    }
                }
            }
        }

        return [
            'test_ids' => $test_ids->unique()->values()->toArray(),
            'product_ids' => $product_ids->unique()->values()->toArray(),
            'status' => $status,
            'expiry' => $expiry,
            'product_status' => $product_status,
            'product_expiry' => $product_expiry
        ];
    }

    /**
     * Get filtered tests with search term
     *
     * @param array $test_ids
     * @param string $search_term
     * @return Collection
     */
    private function getFilteredTests($test_ids, $search_term = '')
    {
        if (empty($test_ids)) {
            return collect();
        }

        return Test::with('testtype')
            ->whereIn('id', $test_ids)
            ->where('name', 'LIKE', "%{$search_term}%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Get filtered products with search term
     *
     * @param array $product_ids
     * @param string $search_term
     * @return Collection
     */
    private function getFilteredProducts($product_ids, $search_term = '')
    {
        if (empty($product_ids)) {
            return collect();
        }

        return Product::whereIn('id', $product_ids)
            ->where('name', 'LIKE', "%{$search_term}%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Get user attempts data efficiently
     *
     * @param int $user_id
     * @param array $test_ids
     * @return Collection
     */
    private function getUserAttempts($user_id, $test_ids = [])
    {
        $cache_key = "user_attempts_data_{$user_id}";
        
        return Cache::remember($cache_key, 300, function () use ($user_id) {
            return Attempt::where('user_id', $user_id)
                ->select('test_id', 'answer', 'accuracy')
                ->get()
                ->keyBy('test_id');
        });
    }

    /**
     * Process test completion status
     *
     * @param Collection $tests
     * @param Collection $attempts
     * @param array $status
     * @return array
     */
    public function processTestStatus($tests, $attempts, $status)
    {
        $status2 = [];

        foreach ($tests as $test) {
            if ($attempts->has($test->id)) {
                $status[$test->id] = 'Completed';
                
                // Handle writing test evaluation status
                if ($test->testtype && $test->testtype->name == 'WRITING') {
                    $attempt = $attempts->get($test->id);
                    $status2[$test->id] = $attempt->answer ? 'evaluated' : 'notevaluated';
                }
            }
        }

        return [$status, $status2];
    }

    /**
     * Clear dashboard cache for user
     *
     * @param int $user_id
     */
    public function clearUserCache($user_id)
    {
        $cache_patterns = [
            "user_dashboard_{$user_id}",
            "user_dashboard_v2_{$user_id}",
            "user_orders_{$user_id}",
            "user_attempts_{$user_id}",
            "user_attempts_data_{$user_id}",
            "attempted_{$user_id}",
            "mytests_{$user_id}",
            "my_products_{$user_id}",
            "my_orders_{$user_id}"
        ];

        foreach ($cache_patterns as $pattern) {
            Cache::forget($pattern);
        }
    }
}