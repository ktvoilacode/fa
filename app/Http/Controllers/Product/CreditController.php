<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Credit as Obj;
use App\Models\Product\Order;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Cache;

class CreditController extends Controller
{
    /*
        Credit Controller
    */

    public function __construct(){
        $this->app      =   'product';
        $this->module   =   'credit';
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj,Request $request)
    {
        $this->authorize('view', $obj);

        $search = $request->search;
        $item = $request->item;
        $client_list = Cache::get('client_list');
        $client_slug = request()->get('client_slug');
        $credits = array("total"=>0,"used"=>0,"unused"=>0);
        if(subdomain()!='prep'){
            $objs = $obj->where('client_slug',subdomain())
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 
            $credits['total'] = Obj::where('client_slug',subdomain())->sum('credit');
            $pids = Product::where('client_slug',subdomain())->pluck('id')->toArray();
            $credits['used'] = Order::whereIn('product_id',$pids)->sum('txn_value');
            $credits['unused'] =  $credits['total'] - $credits['used'];
            Cache::forever('credits_'.subdomain(),$credits);
        }
        else{
            $objs = $obj->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));  
        }


        

        
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('client_list',$client_list)
                ->with('client_slug',$client_slug)
                ->with('credits',$credits)
                ->with('obj',$obj)
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
        $this->authorize('create', $obj);
        $client_list = Cache::get('client_list');
        $client_slug = request()->get('client_slug');

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('client_list',$client_list)
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
            
            /* create a new entry */
            $obj = $obj->create($request->except(['products']));

            $credits['total'] = Obj::where('client_slug',subdomain())->sum('credit');
            $pids = Product::where('client_slug',subdomain())->pluck('id')->toArray();
            $credits['used'] = Order::whereIn('product_id',$pids)->sum('txn_value');
            $credits['unused'] =  $credits['total'] - $credits['used'];
            Cache::forever('credits_'.subdomain(),$credits);

            flash('Successfully added credits')->success();
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
   
        if(request()->get('export')){
            $users =Order::where('txn_id',$obj->code)->pluck('user_id')->toArray();
            request()->session()->put('users',$users);
            if(request()->get('test_id')){
                $test = Test::where('id',request()->get('test_id'))->first();
            }else
            $test = $obj->products[0]->tests[0];
            $u = Attempt::where('test_id',$test->id)
                    ->whereIn('user_id',$users)
                    ->get()->groupBy('user_id');
            $score =[];
            foreach($u as $k=>$usr){
                $score[$k] = 0;
            }
            foreach($u as $k=>$usr){
                foreach($usr as $at){
                    if($at->accuracy==1)
                        $score[$k]++;                    
                }
            }

            arsort($score);
            request()->session()->put('score',$score);
            request()->session()->put('ids_ordered',array_keys($score));

            $name = $test->slug.'_report';
            return Excel::download(new ScoreExport, $name.'.xlsx');
        }

        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('app',$this);
        else
            abort(404);
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
        $this->authorize('update', $obj);
        $client_slug = request()->get('client_slug');
        
        $products  = Product::where('status',1)->where('client_slug',$client_slug)->get();
        $tests  = Test::where('status',1)->where('client_slug',$client_slug)->get();
        $client_list = Cache::get('client_list');

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('products',$products)
                ->with('client_list',$client_list)
                ->with('tests',$tests)
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
           

            $obj = $obj->update($request->except(['products'])); 


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

        
        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index');
    }
}
