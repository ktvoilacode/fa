<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test\Test;
use App\Models\Test\Category;
use App\Models\Test\Attempt;
use App\Models\Product\Product;
use App\Models\Product\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Storage::disk('s3')->put('sample.text', 'hello');
        $view = 'welcome2';
        $tests = Test::where('status', 1)->orderBy('id', 'desc')->limit(18)->get();
        return view($view)
            ->with('tests', $tests);
    }

    public function dbUpdate()
    {

        //update user subdomians
        if (request()->get('user'))
            User::query()->update(['client_slug' => 'prep']);
        if (request()->get('test'))
            Test::query()->update(['client_slug' => 'prep']);
        if (request()->get('product'))
            Product::query()->update(['client_slug' => 'prep']);
        if (request()->get('coupon'))
            Coupon::query()->update(['client_slug' => 'prep']);
    }



    public function welcome2()
    {
        $view = 'welcome2';

        return view($view);
    }

    public function welcome(Request $request)
    {

        $view = 'welcome4';
        $this->app = 'test';
        $this->module = 'test';

        $obj = new Test();
        $search = $request->search;
        $item = $request->item;
        $category = $request->category;
        $type = $request->type;
        $category_id = null;
        if ($category) {
            $cate = Category::where('slug', $category)->first();
            $category_id = $cate->id;
            if ($type == 'free')
                $objs = $obj->where('name', 'LIKE', "%{$item}%")
                    ->where('category_id', $category_id)
                    ->where('price', 0)
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('global.no_of_records'));
            else if ($type == 'premium')
                $objs = $obj->where('name', 'LIKE', "%{$item}%")
                    ->where('category_id', $category_id)
                    ->where('price', '!=', 0)
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('global.no_of_records'));
            else
                $objs = $obj->where('name', 'LIKE', "%{$item}%")
                    ->where('category_id', $category_id)
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('global.no_of_records'));
        } else {
            if ($type == 'free')
                $objs = $obj->where('name', 'LIKE', "%{$item}%")
                    ->where('price', 0)
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('global.no_of_records'));
            else if ($type == 'premium')
                $objs = $obj->where('name', 'LIKE', "%{$item}%")
                    ->where('price', '!=', 0)
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('global.no_of_records'));
            else {

                $objs = Cache::get('tests_active');
                if (!$objs) {
                    $objs = $obj->where('name', 'LIKE', "%{$item}%")
                        ->where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->paginate(config('global.no_of_records'));
                    Cache::forever('tests_active', $objs);
                }
            }
        }

        $categories = Cache::get('categories');
        if (!$categories) {
            $categories = Category::where('status', 1)->get();
            Cache::forever('categories', $categories);
        }


        $view = $search ? 'appl.test.test.public_list' : 'welcome4';

        $toast = 1;
        if ($_SERVER['HTTP_HOST'] != 'project.test' && $_SERVER['HTTP_HOST'] != 'prep.firstacademy.in' && $_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != 'localhost:8000') {
            if (!$search)
                $view = 'welcome_piofx';
            $toast = 0;
        }

        if (Auth::user())
            return redirect()->to('/home');
        else
            return view($view)
                ->with('tests', $objs)
                ->with('objs', $objs)
                ->with('obj', $obj)
                ->with('toast', $toast)
                ->with('categories', $categories)
                ->with('app', $this);
    }

    public function dashboard(Request $request)
    {
        if ($_SERVER['HTTP_HOST'] != 'prep.firstacademy.in' && $_SERVER['HTTP_HOST'] != 'fa.test' && $_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != 'localhost:8000') {
            return $this->dashboard_piofx($request);
        }

        $user = Auth::user();
        $search = $request->search;
        $item = $request->item ?? '';
        $item2 = $request->item2 ?? '';

        // Clear cache if refresh requested
        if ($request->get('refresh')) {
            Cache::forget("user_dashboard_{$user->id}");
            Cache::forget("user_orders_{$user->id}");
            Cache::forget("user_attempts_{$user->id}");
        }

        // Cache dashboard data for 10 minutes
        $cache_key = "user_dashboard_{$user->id}_" . md5($item . $item2 . $search);
        $dashboard_data = Cache::remember($cache_key, 600, function () use ($user, $item, $item2) {
            // OPTIMIZED: Single query with eager loading to prevent N+1
            $orders = $user->orders()
                ->with(['product.tests.testtype', 'test.testtype'])
                ->where('status', 1)
                ->orderBy('expiry', 'desc')
                ->get();

            // OPTIMIZED: Use collections for efficient data processing
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

                    // OPTIMIZED: Tests are already eager loaded, no additional queries
                    foreach ($order->product->tests as $test) {
                        if (!$test_ids->contains($test->id)) {
                            $test_ids->push($test->id);
                            $expiry[$test->id] = $order->expiry;
                            $status[$test->id] = $status_text;
                        }
                    }
                }
            }

            // FIXED: Ensure arrays are always returned, never collections
            $test_array = $test_ids->unique()->values()->toArray();
            $product_array = $product_ids->unique()->values()->toArray();
            
            return [
                'test_ids' => is_array($test_array) ? $test_array : [],
                'product_ids' => is_array($product_array) ? $product_array : [],
                'status' => $status,
                'expiry' => $expiry,
                'product_status' => $product_status,
                'product_expiry' => $product_expiry
            ];
        });

        // OPTIMIZED: Single query for tests with search  
        $tests = collect();
        if (!empty($dashboard_data['test_ids'])) {
            // FIXED: Ensure we always pass an array to whereIn
            $test_ids_array = is_array($dashboard_data['test_ids']) ? $dashboard_data['test_ids'] : [];
            if (!empty($test_ids_array)) {
                $tests = Test::with('testtype')
                    ->whereIn('id', $test_ids_array)
                    ->where('name', 'LIKE', "%{$item}%")
                    ->orderBy('name')
                    ->get();
            }
        }

        // OPTIMIZED: Single query for products with search
        $products = collect();
        if (!empty($dashboard_data['product_ids'])) {
            // FIXED: Ensure we always pass an array to whereIn
            $product_ids_array = is_array($dashboard_data['product_ids']) ? $dashboard_data['product_ids'] : [];
            if (!empty($product_ids_array)) {
                $products = Product::whereIn('id', $product_ids_array)
                    ->where('name', 'LIKE', "%{$item2}%")
                    ->orderBy('name')
                    ->get();
            }
        }

        // OPTIMIZED: Single query for all attempts
        $attempts_data = collect();
        $status2 = [];
        
        if ($tests->isNotEmpty()) {
            $attempts_data = Cache::remember("user_attempts_{$user->id}", 300, function () use ($user) {
                return Attempt::where('user_id', $user->id)
                    ->select('test_id', 'answer', 'accuracy')
                    ->get()
                    ->keyBy('test_id');
            });

            // OPTIMIZED: Process attempts efficiently
            foreach ($tests as $test) {
                if ($attempts_data->has($test->id)) {
                    $dashboard_data['status'][$test->id] = 'Completed';
                    
                    // Handle writing test evaluation status
                    if ($test->testtype && $test->testtype->name == 'WRITING') {
                        $attempt = $attempts_data->get($test->id);
                        $status2[$test->id] = $attempt->answer ? 'evaluated' : 'notevaluated';
                    }
                }
            }
        }

        // Determine view based on search and context
        if ($search) {
            $view = $item2 ? 'appl.pages.blocks.productlist' : 'appl.pages.blocks.testlist';
        } else {
            $view = 'appl.pages.dashboard2';
        }

        // Handle specific host overrides
        if ($_SERVER['HTTP_HOST'] == 'onlinelibrary.test' || $_SERVER['HTTP_HOST'] == 'piofx.com') {
            if ($user->role == 0) {
                $view = 'appl.admin.bfs.index_student';
            }
        }

        if (request()->segment(1) == 'home2') {
            $view = 'appl.pages.home2';
        }

        return view($view)
            ->with('tests', $tests)
            ->with('products', $products)
            ->with('expiry', $dashboard_data['expiry'])
            ->with('product_expiry', $dashboard_data['product_expiry'])
            ->with('product_status', $dashboard_data['product_status'])
            ->with('status', $dashboard_data['status'])
            ->with('status2', $status2);
    }

    public function dashboard_piofx(Request $request)
    {
        $user = Auth::user();
        if ($request->get('refresh')) {
            Cache::forget('my_products_' . $user->id);
            Cache::forget('my_orders_' . $user->id);
        }
        $orders = Cache::remember('my_orders_' . $user->id, 300, function () use ($user) {
            return $user->orders()->where('status', 1)->orderBy('expiry', 'desc')->get();
        });
        $products = Cache::remember('my_products_' . $user->id, 300, function () use ($orders) {
            $products =  array();
            foreach ($orders as $o) {
                if ($o->product_id)
                    $products[$o->product_id] = $o->product;
            }
            return $products;
        });

        $product_status = array();
        $product_expiry = array();

        foreach ($orders as $o) {
            if ($o->product_id) {
                if (strtotime($o->expiry) > strtotime(date('Y-m-d')))
                    $product_status[$o->product_id] = 'Active';
                else
                    $product_status[$o->product_id] = 'Expired';
                $product_expiry[$o->product_id] = $o->expiry;
            }
        }

        $view = 'appl.pages.piofx_dashboard';
        return view($view)
            ->with('products', $products)
            ->with('product_expiry', $product_expiry)
            ->with('product_status', $product_status);
    }
}
