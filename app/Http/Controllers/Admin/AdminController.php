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

        // OPTIMIZED: Cache complete dashboard data for 30 minutes
        // PERFORMANCE: Extended from 10min to 30min due to date filtering optimizations
        // With 2.3M records, first load can be slow - cache helps significantly
        $cache_key = "admin_dashboard_{$subdomain}_v5"; // v5 = JOIN instead of whereIn/whereNotIn (13s -> <1s!)
        $data = Cache::remember($cache_key, 1800, function () use ($subdomain) {
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
        // PERFORMANCE: Only load recent attempts (2023+) to avoid scanning 2.3M records
        $cutoff_date = '2023-01-01';

        // OPTIMIZED: Use JOIN instead of whereIn() for better performance (13s -> <1s)
        // CRITICAL FIX: whereIn() with subquery was taking 13.28 seconds!
        // VIEW OPTIMIZATION: View only displays 3 items, so we only load 4 for sorting!
        $writing_attempts = Attempt::join('tests', 'attempts.test_id', '=', 'tests.id')
            ->where('tests.client_slug', $subdomain)
            ->where('tests.type_id', 3)
            ->whereNull('attempts.answer')
            ->where('attempts.created_at', '>=', $cutoff_date) // FIX: Filter old data
            ->leftJoin('orders', function ($join) {
                $join->on('attempts.test_id', '=', 'orders.test_id')
                     ->on('attempts.user_id', '=', 'orders.user_id')
                     ->where('orders.product_id', '=', 3)
                     ->where('orders.status', '=', 1);
            })
            ->with([
                'user:id,name,email',
                'test:id,name'
            ])
            ->select('attempts.*', DB::raw('CASE WHEN orders.id IS NOT NULL THEN 1 ELSE 0 END as premium'))
            ->orderBy('attempts.created_at', 'desc')
            ->limit(4) // OPTIMIZED: View shows 3, load 4 for premium sorting
            ->get();

        // OPTIMIZED: Sort by premium status efficiently
        $writing_data = $writing_attempts->sortByDesc('premium')->take(3)->values();

        return ['writing' => $writing_data];
    }

    /**
     * OPTIMIZED: Get recent attempts data efficiently
     */
    private function getOptimizedAttemptsData($subdomain)
    {
        // PERFORMANCE: Only load recent attempts (2023+) to avoid scanning 2.3M records
        $cutoff_date = '2023-01-01';

        // OPTIMIZED: Use JOIN instead of whereNotIn() for better performance
        // VIEW OPTIMIZATION: View only displays 10 items in "Tests Attempted" table!
        // Previously loading 50, but view breaks at 10 (@if($k==10) @break)
        $attempts = Attempt::join('tests', 'attempts.test_id', '=', 'tests.id')
            ->where('attempts.user_id', '!=', 0)
            ->where('attempts.created_at', '>=', $cutoff_date) // FIX: Filter old data (cuts 55% of data!)
            ->where('tests.client_slug', $subdomain)
            ->where('tests.type_id', '!=', 3) // Exclude writing tests (type_id 3)
            ->select('attempts.*')
            ->with([
                'user:id,name,email',
                'test:id,name'
            ])
            ->orderBy('attempts.created_at', 'desc')
            ->limit(15) // OPTIMIZED: View shows 10, load 15 to account for duplicates
            ->get();

        // OPTIMIZED: Get unique combinations and limit to what view displays
        $latest = $attempts->unique(function ($attempt) {
            return $attempt->test_id . '_' . $attempt->user_id;
        })->take(10) // VIEW OPTIMIZATION: Only load what's displayed!
        ->map(function ($attempt) {
            return [
                'user' => $attempt->user,
                'test' => $attempt->test,
                'attempt' => $attempt
            ];
        })->values();

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
        // PERFORMANCE: Filter recent mock attempts (2024+) for faster queries
        $cutoff_date = '2024-01-01';

        // VIEW OPTIMIZATION: View only displays 3 mock attempts (@if($counter==3) @break)
        $mock_attempts = Mock_Attempt::where('status', -1)
            ->where('created_at', '>=', $cutoff_date) // FIX: Only show recent incomplete attempts
            ->with([
                'mock:id,name',
                'user:id,name,email'
            ])
            ->select('id', 'mock_id', 'user_id', 'status', 'created_at')
            ->orderBy('id', 'desc')
            ->limit(3)  // OPTIMIZED: View shows exactly 3, so load only 3!
            ->get();

        // OPTIMIZED: Extract mocks efficiently from loaded relationships
        $mocks = $mock_attempts->pluck('mock')->filter()->keyBy('id');

        // REMOVED: New users query - section is HIDDEN in view (class="d-none")!
        // This was loading 5 users that are never displayed - pure waste!

        // VIEW OPTIMIZATION: Forms card displays 3 items (@if($k==2) @break)
        $forms = Form::select('id', 'name', 'email', 'subject', 'created_at')
            ->orderBy('id', 'desc')
            ->limit(3) // OPTIMIZED: Was 5, view shows 3
            ->get();

        return [
            'mock_attempts' => $mock_attempts,
            'mocks' => $mocks,
            'new' => collect([]), // REMOVED QUERY: View section is hidden (d-none), no need to load
            'form' => $forms
        ];
    }

    /**
     * Clear all admin-related cache keys
     */
    private function clearAdminCache($subdomain)
    {
        $cache_keys = [
            "admin_dashboard_{$subdomain}_v2", // Old cache key
            "admin_dashboard_{$subdomain}_v3", // Date filters
            "admin_dashboard_{$subdomain}_v4", // View-matched limits
            "admin_dashboard_{$subdomain}_v5", // JOIN optimization
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

    /**
     * Audit Log Viewer - View application errors and logs
     */
    public function auditLogs(Request $request)
    {
        $days = $request->get('days', 7); // Default: last 7 days
        $error_type = $request->get('error_type', 'all'); // all, ERROR, WARNING, INFO
        $status_code = $request->get('status_code', 'all'); // all, 404, 500, 504, etc
        $search = $request->get('search', ''); // Search keyword

        $logs = $this->parseLogFiles($days, $error_type, $status_code, $search);

        // Get statistics
        $stats = [
            'total' => count($logs),
            'errors' => collect($logs)->where('level', 'ERROR')->count(),
            'warnings' => collect($logs)->where('level', 'WARNING')->count(),
            '404s' => collect($logs)->filter(function($log) {
                return strpos($log['message'], '404') !== false;
            })->count(),
            '504s' => collect($logs)->filter(function($log) {
                return strpos($log['message'], '504') !== false ||
                       strpos($log['message'], 'timeout') !== false;
            })->count(),
            '500s' => collect($logs)->filter(function($log) {
                return strpos($log['message'], '500') !== false;
            })->count(),
        ];

        return view('appl.admin.admin.audit_logs', [
            'logs' => $logs,
            'stats' => $stats,
            'days' => $days,
            'error_type' => $error_type,
            'status_code' => $status_code,
            'search' => $search
        ]);
    }

    /**
     * Parse Laravel log files for the last N days
     */
    private function parseLogFiles($days, $error_type, $status_code, $search)
    {
        $logs = [];
        $log_path = storage_path('logs');

        // Get log files for the last N days
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $file = "{$log_path}/laravel-{$date}.log";

            if (file_exists($file)) {
                $logs = array_merge($logs, $this->parseLogFile($file, $error_type, $status_code, $search));
            }
        }

        // Sort by timestamp (newest first)
        usort($logs, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        // Limit to 500 most recent logs for performance
        return array_slice($logs, 0, 500);
    }

    /**
     * Parse a single log file
     */
    private function parseLogFile($file, $error_type, $status_code, $search)
    {
        $logs = [];
        $content = file_get_contents($file);

        // Match Laravel log entries
        // Format: [2025-10-19 12:00:00] local.ERROR: Message {"exception":"..."}
        preg_match_all(
            '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.+?)(?=\n\[|$)/s',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $timestamp = $match[1];
            $level = $match[2];
            $message = $match[3];

            // Filter by error type
            if ($error_type !== 'all' && $level !== $error_type) {
                continue;
            }

            // Filter by status code
            if ($status_code !== 'all') {
                if (!preg_match('/' . preg_quote($status_code) . '/', $message)) {
                    continue;
                }
            }

            // Filter by search keyword
            if (!empty($search)) {
                if (stripos($message, $search) === false) {
                    continue;
                }
            }

            // Extract key information
            $log_entry = [
                'timestamp' => $timestamp,
                'level' => $level,
                'message' => $this->cleanLogMessage($message),
                'file' => $this->extractFile($message),
                'user_id' => $this->extractUserId($message),
                'url' => $this->extractUrl($message),
            ];

            $logs[] = $log_entry;
        }

        return $logs;
    }

    /**
     * Clean log message for display
     */
    private function cleanLogMessage($message)
    {
        // Limit message length
        $message = substr($message, 0, 500);

        // Remove JSON exception traces for cleaner display
        $message = preg_replace('/\{.*?\}/s', '', $message);

        return trim($message);
    }

    /**
     * Extract file path from log message
     */
    private function extractFile($message)
    {
        if (preg_match('/at \/home\/forge\/prep\.firstacademy\.in\/(.+?):(\d+)/', $message, $match)) {
            return $match[1] . ':' . $match[2];
        }
        return '-';
    }

    /**
     * Extract user ID from log message
     */
    private function extractUserId($message)
    {
        if (preg_match('/"userId["\']:\s*(\d+)/', $message, $match)) {
            return $match[1];
        }
        return '-';
    }

    /**
     * Extract URL from log message
     */
    private function extractUrl($message)
    {
        if (preg_match('/"url["\']:\s*["\']([^"\']+)["\']/', $message, $match)) {
            return $match[1];
        }
        if (preg_match('/GET|POST|PUT|DELETE\s+([^\s]+)/', $message, $match)) {
            return $match[1];
        }
        return '-';
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
