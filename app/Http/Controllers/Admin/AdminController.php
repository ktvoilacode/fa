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
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

        if(request()->get('refresh')){
            Cache::forget('tot_users');
            Cache::forget('latest_users');
            Cache::forget('wri_users');
            Cache::forget('att_users');
        }
        $data['ucount'] =Cache::remember('tot_users', 240, function(){ 
            return User::count();
        });
        $data['users'] =Cache::remember('latest_users', 240, function(){
            return User::limit(3)->orderBy('id','desc')->get();
        });
        /* writing data */
        $test_ids = Obj::whereIn('type_id',[3])->pluck('id')->toArray();
       
        $data['writing'] = Cache::remember('wri_users', 120, function() use ($test_ids) {

            $d = Attempt::whereIn('test_id',$test_ids)->whereNull('answer')->with('user')->orderBy('created_at','desc')->get();
            foreach($d as $k=>$m){
                $o = Order::where('test_id',$m->test_id)->where('user_id',$m->user_id)->where('product_id',3)->where('status',1)->first();
                if($o)
                    $d[$k]->premium = 1;
                else
                    $d[$k]->premium =0;
            }

            $d2=[];$k=0;
            foreach($d as $a=>$b){
                if($b->premium==1)
                $d2[$k++] = $b;
            }
            foreach($d as $a=>$b){
                if($b->premium!=1)
                $d2[$k++] = $b;
            }
            return $d2;
        });

        foreach($data['writing'] as $dd){
            echo $dd->id.' - '.$dd->user->name;
            echo"<br>";
        }
        dd($data['writing']);
        //$data['writing'] = $data['writing']->sort('premium');



        /* duolingo data */
        $data['duolingo_tests'] = [];//Obj::whereIn('type_id',[9])->where('price','!=',0)->get();
        //$test_ids2 = $data['duolingo_tests']->pluck('id')->toArray();
        $data2= [];//Attempt::whereIn('test_id',$test_ids2)->where('status',0)->whereNotNull('user_id')->with('user')->orderBy('created_at','desc')->get();

      
        $dat=[];$d2=[];
        foreach($data2 as $a){
            if($a->user_id){
                $dat[$a->test_id][$a->user_id] = $a->user;
                $d2[$a->test_id.'_'.$a->user_id] = $a;  
            }
            
           
        }
        $data['duolingo_count']=0;
        foreach($dat as $d){
            $data['duolingo_count'] +=count($d);
        }
        $data['duolingo'] = $d2;


        $attempts = Cache::remember('att_users', 240, function(){
            return Attempt::where('user_id','!=',0)->orderBy('created_at','desc')->with('user')->with('test')->limit(100)->get();
        });

        $data['duo_orders'] = Order::where('product_id',43)->orderBy('created_at','desc')->get();
        $data['new'] = User::where('admin','0')->orderBy('lastlogin_at','desc')->limit(5)->get();
        $data['form'] = Form::orderBy('id','desc')->limit(5)->get();

        $latest = [];$count=0;
        foreach($attempts as $a){
            if(!in_array($a->test_id, $test_ids))
            if(!isset($latest[$a->test_id.$a->user_id])){
              
                  $latest[$a->test_id.$a->user_id]['user']= $a->user;
                  $latest[$a->test_id.$a->user_id]['test'] = $a->test;
                  $latest[$a->test_id.$a->user_id]['attempt'] = $a;

                $count++;
                
            }
        }

       // dd($count);
         
        $data['latest'] = $latest;
        $data['attempt_total'] = $count; 
  
        $data['coupon'] = '';//Coupon::where('code','FA5Y9')->first();

        $user = \auth::user();

        $view = 'appl.admin.admin.index';
        /* code specific to piofx */
        if($_SERVER['HTTP_HOST'] == 'onlinelibrary.test' || $_SERVER['HTTP_HOST'] == 'piofx.com' )
        {
            if($user->admin==1)
                $view = 'appl.admin.bfs.index_superadmin';
            if($user->admin==4)
                $view = 'appl.admin.bfs.index_trainer';
        }

        return view($view)->with('data',$data);
        
    }

    public function analytics(Obj $obj){
        $this->authorize('view', $obj);
        $admin = new Admin;
        $data['user'] = $admin->userAnalytics();
        $data['order'] = $admin->orderAnalytics(); 
        $data['group_count'] = $admin->groupCount();
        $data['test_count'] = $admin->testCount();
        $data['product_count'] = $admin->productCount();
        $data['coupon_count'] = $admin->couponCount();
        return view('appl.admin.admin.analytics')->with('data',$data);
    }



    public function contact(Request $r){
        
        $result = request()->session()->get('result');
        $res = $r->get('result');

        return redirect()->back()->withInput();;

        if($res !=$result)
        {
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

    
    public function notify(Request $request){

        $obj = new Form();
                $obj->name = $request->name;
                $obj->phone = $request->phone;
                $obj->email = $request->email;
                $obj->subject = 'Error in question';
                $description ='';
                foreach($request->all() as $k=>$r){
                    if(is_array($r))
                        $r = implode(',', $r);
                    if($k =='test'){
                        $test = Obj::where('name',$r)->first();
                        if($test)
                        $description = $description.'<div>'.strtoupper($k).' - <a href="'.route('test.show',$test->id).'">'.$test->name.'</a>';
                        else
                        $description = $description. '<div>'.strtoupper($k).' - '.$r.'</div>' ;
                    }else if($k == 'email'){
                        $u = \auth::user()->where('email',$r)->first();
                      
                        if($u)
                        $description = $description.'<div>'.strtoupper($k).' - <a href="'.route('user.show',$u->id).'">'.$u->email.'</a>';
                        else
                        $description = $description. '<div>'.strtoupper($k).' - '.$r.'</div>' ;
                    }
                    else if($k!='_token' && $k!='_method' && $k!='url')
                    $description = $description. '<div>'.strtoupper($k).' - '.$r.'</div>' ;


                }
                $obj->description = $description;
                $obj->year = 0;
                $obj->college = '';

                $obj->save();
        
        Mail::to(config('mail.report'))->send(new  ErrorReport($request));
        echo "Successfully reported to administrator.";
    }


   
}
