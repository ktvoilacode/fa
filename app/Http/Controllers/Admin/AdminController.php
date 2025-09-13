<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Test as Obj;
use App\Models\Test\Attempt;
use App\Models\Test\Writing;
use App\Models\Admin\Admin;
use App\Models\Admin\Form;
use App\Models\Product\Coupon;
use App\Models\Product\Order;
use App\Mail\contactmessage;
use App\Mail\ErrorReport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Test\Mock;
use App\Models\Test\Mock_Attempt;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj)
    {
        $this->authorize('view', $obj);
        $subdomain = subdomain();
        
        // OPTIMIZED: Clear all admin caches at once
        if (request()->get('refresh')) {
            $this->clearAdminCache($subdomain);
        }

        // OPTIMIZED: Cache complete dashboard data
        $cache_key = "admin_dashboard_{$subdomain}_v2";
        $data = Cache::remember($cache_key, 600, function () use ($subdomain) {
            return $this->getOptimizedAdminData($subdomain);
        });

        $user = Auth::user();
        $view = 'appl.admin.admin.index';
        
        // Handle specific host overrides
        if ($_SERVER['HTTP_HOST'] == 'onlinelibrary.test' || $_SERVER['HTTP_HOST'] == 'piofx.com') {
            if ($user->admin == 1)
                $view = 'appl.admin.bfs.index_superadmin';
            if ($user->admin == 4)
                $view = 'appl.admin.bfs.index_trainer';
        }

        return view($view)->with('data', $data);
    }

    /**
     * OPTIMIZED: Get all admin dashboard data efficiently
     */
    private function getOptimizedAdminData($subdomain)
    {
        $data = [];

        // OPTIMIZED: User counts with single query
        $user_stats = User::where('client_slug', $subdomain)
            ->selectRaw('COUNT(*) as total_count')
            ->first();

        $data['ucount'] = $user_stats->total_count;

        // OPTIMIZED: Latest users
        $data['users'] = User::where('client_slug', $subdomain)
            ->select('id', 'name', 'email', 'created_at')
            ->limit(3)
            ->orderBy('id', 'desc')
            ->get();

        // OPTIMIZED: Writing test data for 'prep' subdomain only
        if ($subdomain == 'prep') {
            $data = array_merge($data, $this->getOptimizedWritingData($subdomain));
            $data = array_merge($data, $this->getOptimizedAttemptsData($subdomain));
            $data = array_merge($data, $this->getOptimizedMockData());
        } else {
            $data['writing'] = [];
            $data['latest'] = [];
            $data['attempt_total'] = 0;
            
            // Handle credits for non-prep subdomains
            $credits = Cache::get('credits_' . $subdomain);
            $data['balance'] = $credits ? $credits['unused'] : 0;
        }

        // Static/unused data (keeping for compatibility)
        $data['duolingo_tests'] = [];
        $data['duolingo_count'] = 0;
        $data['duolingo'] = [];
        $data['duo_orders'] = [];
        $data['coupon'] = '';

        return $data;
    }

    /**
     * OPTIMIZED: Get writing test data efficiently
     */
    private function getOptimizedWritingData($subdomain)
    {
        $test_ids = Obj::where('client_slug', $subdomain)
            ->whereIn('type_id', [3])
            ->pluck('id')
            ->toArray();

        if (empty($test_ids)) {
            return ['writing' => []];
        }

        // OPTIMIZED: Single query with joins to avoid N+1
        $writing_attempts = Attempt::whereIn('attempts.test_id', $test_ids)
            ->whereNull('attempts.answer')
            ->with([
                'user:id,name,email',
                'test:id,name'
            ])
            ->leftJoin('orders', function ($join) {
                $join->on('attempts.test_id', '=', 'orders.test_id')
                     ->on('attempts.user_id', '=', 'orders.user_id')
                     ->where('orders.product_id', '=', 3)
                     ->where('orders.status', '=', 1);
            })
            ->select('attempts.*', DB::raw('CASE WHEN orders.id IS NOT NULL THEN 1 ELSE 0 END as premium'))
            ->orderBy('attempts.created_at', 'desc')
            ->limit(20) // Reasonable limit
            ->get();

        // OPTIMIZED: Sort by premium status efficiently
        $writing_data = $writing_attempts->sortByDesc('premium')->take(4)->values();

        return ['writing' => $writing_data];
    }

    /**
     * OPTIMIZED: Get recent attempts data efficiently
     */
    private function getOptimizedAttemptsData($subdomain)
    {
        $writing_test_ids = Obj::where('client_slug', $subdomain)
            ->whereIn('type_id', [3])
            ->pluck('id')
            ->toArray();

        // OPTIMIZED: Single query with eager loading
        $attempts = Attempt::where('attempts.user_id', '!=', 0)
            ->whereNotIn('attempts.test_id', $writing_test_ids) // Exclude writing tests
            ->with([
                'user:id,name,email',
                'test:id,name'
            ])
            ->orderBy('attempts.created_at', 'desc')
            ->limit(100)
            ->get();

        // OPTIMIZED: Use collections for efficient processing
        $latest = $attempts->groupBy(function ($attempt) {
            return $attempt->test_id . '_' . $attempt->user_id;
        })->map(function ($group) {
            $attempt = $group->first();
            return [
                'user' => $attempt->user,
                'test' => $attempt->test,
                'attempt' => $attempt
            ];
        });

        return [
            'latest' => $latest->toArray(),
            'attempt_total' => $latest->count()
        ];
    }

    /**
     * OPTIMIZED: Get mock test data efficiently
     */
    private function getOptimizedMockData()
    {
        // OPTIMIZED: Single query with eager loading
        $mock_attempts = Mock_Attempt::where('status', -1)
            ->with([
                'mock:id,name',
                'user:id,name,email'
            ])
            ->select('id', 'mock_id', 'user_id', 'status', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        // OPTIMIZED: Extract mocks efficiently from loaded relationships
        $mocks = $mock_attempts->pluck('mock')->filter()->keyBy('id');

        // OPTIMIZED: Recent users and forms
        $new_users = User::where('admin', 0)
            ->select('id', 'name', 'email', 'lastlogin_at')
            ->orderBy('lastlogin_at', 'desc')
            ->limit(5)
            ->get();

        $forms = Form::select('id', 'name', 'email', 'subject', 'created_at')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return [
            'mock_attempts' => $mock_attempts,
            'mocks' => $mocks,
            'new' => $new_users,
            'form' => $forms
        ];
    }

    /**
     * Clear all admin-related cache keys
     */
    private function clearAdminCache($subdomain)
    {
        $cache_keys = [
            "admin_dashboard_{$subdomain}_v2",
            'tot_users_' . $subdomain,
            'latest_users_' . $subdomain,
            'wri_users_' . $subdomain,
            'att_users_' . $subdomain,
            'credits_' . $subdomain
        ];

        foreach ($cache_keys as $key) {
            Cache::forget($key);
        }
    }

    public function analytics(Obj $obj)
    {
        $this->authorize('view', $obj);
        $admin = new Admin;
        $data['user'] = $admin->userAnalytics();
        $data['order'] = $admin->orderAnalytics();
        $data['group_count'] = $admin->groupCount();
        $data['test_count'] = $admin->testCount();
        $data['product_count'] = $admin->productCount();
        $data['coupon_count'] = $admin->couponCount();
        return view('appl.admin.admin.analytics')->with('data', $data);
    }




    public function whatsappMsg(Request $request)
    {

        // Admin::whatsappWriting('99', 'kt', 33);
        // exit();

        if (isset($request->all()['file'])) {

            $file      = $request->all()['file'];
            $fname = str_replace(' ', '_', strtolower($file->getClientOriginalName()));
            $extension = strtolower($file->getClientOriginalExtension());
            $template = $request->get('template');

            if (!in_array($extension, ['csv'])) {
                flash('Only CSV files are allowed!')->error();
                return redirect()->route('whatsapp');
            }

            $row = 0;
            $file_path = Storage::disk('public')->putFileAs('excels', $request->file('file'), $fname, 'public');
            $fpath = Storage::disk('public')->path($file_path);
            if (($handle = fopen($fpath, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 9000, ",")) !== FALSE) {
                    if ($row == 0) {
                        $row++;
                        continue;
                    }
                    $row++;
                    foreach ($data as $a => $b) {
                        if ($b != "" && $a != 0)
                            $var[$a - 1] = $b;
                    }
                    $phone = $data[0];



                    Admin::sendWhatsapp($phone, $template, $var);
                }
                fclose($handle);
            }

            flash('Whatsapp message sent to (' . ($row - 1) . ') users')->success();
            return redirect()->route('whatsapp');
        } else {
            return view('appl.pages.wapp');
        }
    }


    public function webhookget(Request $r)
    {

        $verify_token = 'fa';
        $mode = $r->get('hub_mode');
        $token = $r->get('hub_verify_token');
        $challenge = $r->get('hub_challenge');
        $showed = $r->get('showed');
        $show = $r->get('show');
        $show_2 = $r->get('show_2');
        $phone = $r->get('phone');
        $data = $r->all();

        if ($mode && $token) {
            if ($token == $verify_token) {
                echo $challenge;
                exit();
            } else if (!$token) {
                $mode = $r->get('mode');
                $token = $r->get('verify_token');
                $challenge = $r->get('challenge');
                if ($token == $verify_token) {
                    echo $challenge;
                    exit();
                } else {
                    abort(403);
                }
            } else {
                abort(403);
            }
        }


        if ($showed) {
            $path = Storage::disk('public')->put('wadata/sample.json', json_encode($data));
            dd($path);
        } else if ($show) {
            $d = Storage::disk('public')->get('wadata/sample.json');
            dd($d);
            $phone = $d['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
            $text = $d['entry'][0]['changes'][0]['value']['messages'][0]['button']['text'];
        } else if ($show_2) {
            $d = Storage::disk('public')->get('wadata/sample_2.json');
            dd($d);
        } else if ($phone) {
            $status['rem_str'] = Cache::get('rem_' . $phone . '_status');
            $status['rem'] = Cache::get('rem');
            dd($status);
        }
    }

    public function webhookpost(Request $r)
    {

        $file = 'sample.json';
        $data = $r->all();
        $show = $r->get('show');
        $show_2 = $r->get('show_2');

        if ($show) {
            $d = Storage::disk('public')->get('wadata/sample.json');

            dd($d);
        } else  if ($show_2) {
            $d = Storage::disk('public')->get('wadata/sample_2.json');

            dd($d);
        } else {
            $path = Storage::disk('public')->put('wadata/sample.json', json_encode($data));
            $path = Storage::disk('public')->put('wadata/sample_2.json', json_encode($data));
            $d = json_decode(json_encode($data), true);

            $phone = $text = null;
            if (isset($d['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']))
                $phone = $d['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
            if (isset($d['entry'][0]['changes'][0]['value']['messages'][0]['button']['text']))
                $text = $d['entry'][0]['changes'][0]['value']['messages'][0]['button']['text'];
            if (isset($d['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']))
                $text = $d['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
            $d['accactivation'] = -1;
            $path = Storage::disk('public')->put('wadata/sample_2.json', json_encode($d));

            $rem_str = 'rem_' . $phone . '_status';
            $status_str = Cache::get($rem_str);
            if ($text == 'Activate Account' && $status_str) {
                $template = 'accactivation';
                Admin::sendWhatsapp($phone, $template, []);
                $d['accactivation'] = 1;
                $path = Storage::disk('public')->put('wadata/sample_2.json', json_encode($d));
            } else if ($text == 'hello' && $status_str) {
                $template = 'hello_world';
                Admin::sendWhatsapp($phone, $template, []);
                $d['accactivation'] = 2;
                $path = Storage::disk('public')->put('wadata/sample_2.json', json_encode($d));
            } else {
                $d['accactivation'] = 0;
                $d['rem_Str'] = $rem_str;
                $d['status_str'] = $status_str;
            }
            Cache::forget($rem_str);
            $path = Storage::disk('public')->put('wadata/sample_2.json', json_encode($d));
        }
    }



    public function contact(Request $r)
    {

        $result = request()->session()->get('result');
        $res = $r->get('result');

        return redirect()->back()->withInput();;

        if ($res != $result) {
            flash('Math operation invalid. Kindly retry!')->error();
            return redirect()->back()->withInput();;
        }

        $f = new Form();
        $f->name = $r->name;
        $f->email = $r->email;
        $f->phone = $r->phone;
        $f->college = '';
        $f->year = 0;
        $f->subject = 'Contact Form';
        $f->description = $r->message;
        $f->save();

        Mail::to(config('mail.report'))->send(new contactmessage($r));
        return view('appl.admin.admin.contactmessage');
    }


    public function notify(Request $request)
    {

        $obj = new Form();
        $obj->name = $request->name;
        $obj->phone = $request->phone;
        $obj->email = $request->email;
        $obj->subject = 'Error in question';
        $description = '';
        foreach ($request->all() as $k => $r) {
            if (is_array($r))
                $r = implode(',', $r);
            if ($k == 'test') {
                $test = Obj::where('name', $r)->first();
                if ($test)
                    $description = $description . '<div>' . strtoupper($k) . ' - <a href="' . route('test.show', $test->id) . '">' . $test->name . '</a>';
                else
                    $description = $description . '<div>' . strtoupper($k) . ' - ' . $r . '</div>';
            } else if ($k == 'email') {
                $u = Auth::user()->where('email', $r)->first();

                if ($u)
                    $description = $description . '<div>' . strtoupper($k) . ' - <a href="' . route('user.show', $u->id) . '">' . $u->email . '</a>';
                else
                    $description = $description . '<div>' . strtoupper($k) . ' - ' . $r . '</div>';
            } else if ($k != '_token' && $k != '_method' && $k != 'url')
                $description = $description . '<div>' . strtoupper($k) . ' - ' . $r . '</div>';
        }
        $obj->description = $description;
        $obj->year = 0;
        $obj->college = '';

        $obj->save();

        Mail::to(config('mail.report'))->send(new  ErrorReport($request));
        echo "Successfully reported to administrator.";
    }
}
