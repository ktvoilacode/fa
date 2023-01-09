<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Mock as Obj;
use App\Models\Test\Mock_Attempt;
use App\Models\Test\Attempt;
use App\Models\Test\Test;
use App\Models\Product\Order;
use Illuminate\Support\Facades\Cache;

use App\Models\Product\Client;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class MockController extends Controller
{
     /*
        Coupon Controller
    */

    public function __construct(){
        $this->app      =   'test';
        $this->module   =   'mock';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj,Request $request)
    {
        $search = $request->search;
        $item = $request->item;
        if(subdomain()=='prep')
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));  
        else
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('client_slug',subdomain())
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));  

        $attempts = Mock_Attempt::whereIn('mock_id',$objs->pluck('id')->toArray())->get()->groupBy('mock_id'); 
        $attempts_review = Mock_Attempt::whereIn('mock_id',$objs->pluck('id')->toArray())->where('status',-1)->get()->groupBy('mock_id'); 
        $attempts_completed = Mock_Attempt::whereIn('mock_id',$objs->pluck('id')->toArray())->where('status',1)->get()->groupBy('mock_id'); 

        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('attempts',$attempts)
                ->with('attempts_review',$attempts_review)
                ->with('attempts_completed',$attempts_completed)
                ->with('obj',$obj)
                ->with('app',$this);
    }

    /** Mock History **/
    public function history(Request $request)
    {
        if(subdomain()!='prep')
        $mocks = Obj::where('client_slug',subdomain())->get()->keyBy('id');
        else
            $mocks = Obj::get()->keyBy('id');
        $mockids = $mocks->pluck('id');
      

        $objs = Mock_Attempt::whereIn('mock_id',$mockids->toArray())
                ->with('user')
                 ->paginate(config('global.no_of_records'));  
        
        $view = 'history';
        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('mocks',$mocks)
                ->with('app',$this);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $obj = new Obj();
        $clients = Client::get();
        $this->authorize('create', $obj);

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('clients',$clients)
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('app',$this);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Obj $obj, Request $request)
    {
        try{

            //update datetime
             $settings = [];
            if($request->activation)
                $settings['activation'] = \carbon\carbon::parse($request->activation)->format('Y-m-d H:i:s');
            else
                $settings['activation'] = null;
            if($request->deactivation)
                $settings['deactivation'] = \carbon\carbon::parse($request->deactivation)->format('Y-m-d H:i:s');
            else
                $settings['deactivation'] = null;

            $settings['noreport'] = 1;
            if($request->noreport==0){
                $settings['noreport'] = 0;
            }elseif($request->noreport==2)
                $settings['noreport'] = 2;

             if($request->testtype)
                $settings['testtype'] = $request->testtype;
            else
                $settings['testtype'] = null;

            if($request->scoring)
                $settings['scoring'] = $request->scoring;
            else
                $settings['scoring'] = null;

            $request->merge(['settings' => json_encode($settings)]);
            
            /* create a new entry */
            $obj = $obj->create($request->all());

            flash('A new ('.$this->app.'/'.$this->module.') item is created!')->success();
            return redirect()->route($this->module.'.index');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('Some error in Creating the record')->error();
                 return redirect()->back()->withInput();;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $obj = Obj::where('id',$id)->first();

        $settings = json_decode($obj->settings);

        if(subdomain()!=$obj->client_slug && subdomain()!='prep')
            abort(403,'Unauthorized Access');

        $this->authorize('view', $obj);
        $attempts = Mock_Attempt::where('mock_id',$obj->id)->with('user')->get();
        $attempt_test = [];
        if($obj->t3){
            $t = $obj->t3;
            $t3= Cache::remember('mtest_'.$obj->t3,60, function() use($t){
                return Test::where('slug',$t)->first();
              });
            $attempt_test[$t3->id] = Attempt::where('test_id',$t3->id)->get();
        }
       
       if($obj->t4){
            $t = $obj->t4;
            $t4 = Cache::remember('mtest_'.$obj->t4,60, function() use($t){
                    return Test::where('slug',$t)->first();
                  });
            $attempt_test[$t4->id] = Attempt::where('test_id',$t4->id)->get();
       }

        if(request()->get('validate'))
        foreach($attempts as $attempt){
            if($attempt->t3==-1){
                $attempt_done = Attempt::where('test_id',$t3->id)->where('user_id',$attempt->user_id)->get();
                if($attempt_done->sum('status') == $attempt_done->count('id')){
                    $attempt->t3 = 1;
                    $attempt->t3_score = $attempt_done->sum('score');
                    $attempt->save();
                }
            }
              if($attempt->t4==-1){
                
                $attempt_done = Attempt::where('test_id',$t4->id)->where('user_id',$attempt->user_id)->get();
                if($attempt_done->sum('status') == $attempt_done->count('id')){
                    $attempt->t4 = 1;
                    $attempt->t4_score = $attempt_done->sum('score');
                    $attempt->save();
                }
            }

            if($attempt->t3==1 && $attempt->t4==1){
                $attempt->status=1;
                $attempt->save();
            }
        }

        if(request()->get('export')){
            $attempts = Mock_Attempt::where('mock_id',$obj->id)->get();
            request()->session()->put('attempts',$attempts);
            
            $name = $obj->slug.'_report';
            return Excel::download(new UsersExport, $name.'.xlsx');
        }



        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('attempts',$attempts)
                    ->with('settings',$settings)
                    ->with('obj',$obj)->with('app',$this);
        else
            abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function public($id)
    {
        $obj = Obj::where('slug',$id)->first();
        $settings = json_decode($obj->settings);
        $user = \Auth::user();

        if(request()->get('user_id'))
            $user = \Auth::user()->where('id',request()->get('user_id'))->first();
        if($user)
            $attempt = Mock_Attempt::where('user_id',$user->id)->where('mock_id',$obj->id)->first();
        else
            $attempt=null;

        if(!$attempt){
            $attempt = new Mock_Attempt();
            $attempt->status =0;
        }

        if($attempt->t1==-1){
            $test = Test::where('slug',$obj->t1)->first();
            $attempt_done = Attempt::where('test_id',$test->id)->where('user_id',$user->id)->get();
            
            if($attempt_done->sum('status') == $attempt_done->count('id')){
                $attempt->t1 = 1;
                $attempt->t1_score = $attempt_done->sum('score');
                $attempt->save();
            }
        }

        if($attempt->t3==-1){
            $test = Test::where('slug',$obj->t3)->first();
            $attempt_done = Attempt::where('test_id',$test->id)->where('user_id',$user->id)->get();
            
            if($attempt_done->sum('status') == $attempt_done->count('id')){
                $attempt->t3 = 1;
                $attempt->t3_score = $attempt_done->sum('score');
                $attempt->save();
            }
        }

         if($attempt->t4==-1){
            $test = Test::where('slug',$obj->t4)->first();
            $attempt_done = Attempt::where('test_id',$test->id)->where('user_id',$user->id)->get();
            if($attempt_done->sum('status') == $attempt_done->count('id')){
                $attempt->t4 = 1;
                $attempt->t4_score = $attempt_done->sum('score');
                $attempt->status = 1;
                $attempt->save();
            }
        }

        $pids = $obj->products->pluck('id')->toArray();
        if($user)
            $orders = Order::where('user_id',$user->id)->whereIn('product_id',$pids)->first();
        else
            $orders=null;

        $auto_activation = \carbon\carbon::parse(\carbon\carbon::now());
        $auto_deactivation = \carbon\carbon::parse(\carbon\carbon::now()->addDays(1));
        if(isset($settings->activation)){
            $auto_activation  = \carbon\carbon::parse($settings->activation);
            $auto_deactivation  = \carbon\carbon::parse($settings->deactivation);
        }

        $noreport = false;
        if(isset($settings->noreport)){
            $noreport = $settings->noreport;
        }

        $settings = json_decode($obj->settings,1);
       
        // check for comments
        $t3slug = $obj->t3;
        if(request()->get('refresh'))
            Cache::forget('t3_'.$obj->t3.'_'.$user->id);
        $comments = [];

        if($obj->t3){
            $comments['t3'] =Cache::remember('t3_'.$obj->t3.'_'.$user->id, 60, function() use($t3slug,$user){
                $t3= Test::where('slug',$t3slug)->first();
               
                $com = Attempt::where('test_id',$t3->id)->where('user_id',$user->id)->where('comment','!=',null)->get(); 
                foreach($com as $c){
                    if($c->comment !='')
                        return $c;
                }
            });
        }
        
        if($obj->t4){
            $t4slug = $obj->t4;
            if(request()->get('refresh'))
                Cache::forget('t4_'.$obj->t4.'_'.$user->id);

            $comments['t4'] =Cache::remember('t4_'.$obj->t4.'_'.$user->id, 60, function() use($t4slug,$user){
                $t4= Test::where('slug',$t4slug)->first();
                $com = Attempt::where('test_id',$t4->id)->where('user_id',$user->id)->where('comment','!=',null)->get(); 
                foreach($com as $c){
                    if($c->comment !='')
                        return $c;
                }
               
            });

        }
        
        if(!$obj->products->first())
            abort(403,'Product has not been attached');


        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.public')
                    ->with('orders',$orders)
                    ->with('auto_activation',$auto_activation)
                    ->with('auto_deactivation',$auto_deactivation)
                    ->with('audio_permission',1)
                    ->with('noreport',$noreport)
                    ->with('comments',$comments)
                    ->with('settings',$settings)
                    ->with('obj',$obj)->with('app',$this)->with('attempt',$attempt);
        else
            abort(404);
    }

    public function start($id)
    {
        $obj = Obj::where('slug',$id)->first();
        $user = \Auth::user();

        session()->put('t1', $obj->t1);
        session()->put('t2', $obj->t2);
        session()->put('t3', $obj->t3);
        session()->put('t4', $obj->t4);
        session()->put('uri', route('mockpage.end',$obj->slug));

        $attempt = Mock_Attempt::where('user_id',$user->id)->where('mock_id',$obj->id)->first();


        // if there is no retrun slug then start from the left over point
        if(!$attempt){
            session()->put('current_test', $obj->t1);

            $url = route('test.instructions',$obj->t1)."?grantaccess=1&mock=1";
            return redirect()->to($url);
        }else{
            if(!$attempt->t1){
                session()->put('current_test', $obj->t1);
                $url = route('test.instructions',$obj->t1)."?grantaccess=1&mock=1";
                return redirect()->to($url);
            }else if(!$attempt->t2 && $attempt->t2!=-2){
                session()->put('current_test', $obj->t2);
                $url = route('test.instructions',$obj->t2)."?grantaccess=1&mock=1";
                return redirect()->to($url);
            }else if(!$attempt->t3 && $attempt->t3!=-2){
                session()->put('current_test', $obj->t3);
                $url = route('test.instructions',$obj->t3)."?grantaccess=1&mock=1";
                return redirect()->to($url);
            }else if(!$attempt->t4 && $attempt->t4!=-2){
                session()->put('current_test', $obj->t4);
                $url = route('test.instructions',$obj->t4)."?grantaccess=1&mock=1";
                return redirect()->to($url);
            }
        }
        
        return redirect()->route('mockpage',$obj->slug);
    }

    public function end($id)
    {
        $obj = Obj::where('slug',$id)->first();

        $user = \Auth::user();
        $attempt = Mock_Attempt::where('user_id',$user->id)->where('mock_id',$obj->id)->first();

        if(!$attempt){
            $attempt = new Mock_Attempt();
            $attempt->mock_id = $obj->id;
            $attempt->user_id = $user->id;
            $attempt->status = 0;
        }

        $test_id = request()->get('test_id');
        $test_slug = request()->get('test_slug');
        $attempt_done = Attempt::where('user_id',$user->id)->where('test_id',$test_id)->get();

        $score = $attempt_done->sum('score');
        if($test_slug == $obj->t1){
            $attempt->t1 = 1;
            $attempt->t1_score = $score;
            

            if(!$obj->t2){
                $attempt->t2 = -2;
                $attempt->t3 = -2;
                $attempt->t4 = -2;
                if($attempt_done->sum('status')==$attempt_done->count('id')){
                    $attempt->t1 = 1;
                    $attempt->status =1;
                }else{
                    $attempt->t1 = -1;
                    $attempt->status =-1;
                }
                
            }
        }else if($test_slug == $obj->t2){
            $attempt->t2 = 1;
            $attempt->t2_score = $score;
        }else if($test_slug == $obj->t3){
            $attempt->t3_score = $score;
            if($attempt_done->sum('status')==$attempt_done->count('id')){
                $attempt->t3 = 1;
            }else{
                $attempt->t3 = -1;
            }
        }else if($test_slug == $obj->t4){
            $attempt->t4_score = $score;
            if($attempt_done->sum('status')==$attempt_done->count('id')){
                $attempt->t4 = 1;
                $attempt->status =1;
            }else{
                $attempt->t4 = -1;
                $attempt->status =-1;
            }
        }

        $attempt->save();
        return redirect()->route('mockpage.start',$id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $obj= Obj::where('id',$id)->first();
        $clients = Client::get();

        $settings = json_decode($obj->settings);
        $this->authorize('update', $obj);


        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('clients',$clients)
                ->with('settings',$settings)
                ->with('editor',true)
                ->with('app',$this);
        else
            abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $obj = Obj::where('id',$id)->first();
            $this->authorize('update', $obj);

            //update datetime
             $settings = [];
            if($request->activation)
                $settings['activation'] = \carbon\carbon::parse($request->activation)->format('Y-m-d H:i:s');
            else
                $settings['activation'] = null;
            if($request->deactivation)
                $settings['deactivation'] = \carbon\carbon::parse($request->deactivation)->format('Y-m-d H:i:s');
            else
                $settings['deactivation'] = null;

            $settings['noreport'] = 1;
            if($request->noreport==0){
                $settings['noreport'] = 0;
            }elseif($request->noreport==2)
                $settings['noreport'] = 2;

            if($request->scoring)
                $settings['scoring'] = $request->scoring;
            else
                $settings['scoring'] = null;

            if($request->testtype)
                $settings['testtype'] = $request->testtype;
            else
                $settings['testtype'] = null;

            $request->merge(['settings' => json_encode($settings)]);

            $obj = $obj->update($request->all()); 

            flash('('.$this->app.'/'.$this->module.') item is updated!')->success();
            return redirect()->route($this->module.'.show',$id);
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                 flash('Some error in updating the record')->error();
                 return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        $user_id = request()->get('user_id');


        if($user_id){
            $t1 = Test::where('slug',$obj->t1)->first();
            Attempt::where('user_id',$user_id)->where('test_id',$t1->id)->delete();

            if($obj->t2){
                $t2 = Test::where('slug',$obj->t2)->first();
                Attempt::where('user_id',$user_id)->where('test_id',$t2->id)->delete();
            }
            

            if($obj->t3){

                $t3 = Test::where('slug',$obj->t3)->first();
                Attempt::where('user_id',$user_id)->where('test_id',$t3->id)->delete();
            }

            if($obj->t4){
                $t4 = Test::where('slug',$obj->t4)->first();
                Attempt::where('user_id',$user_id)->where('test_id',$t4->id)->delete();

            }

            
            Mock_Attempt::where('mock_id',$obj->id)->where('user_id',$user_id)->delete();

            flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
            return redirect()->route($this->module.'.show',$obj->id);

        }else{
            $obj->delete();
            flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
            return redirect()->route($this->module.'.index');
        }
        
    }
}
