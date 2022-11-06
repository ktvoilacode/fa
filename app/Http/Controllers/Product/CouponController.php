<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Coupon as Obj;
use App\Models\Product\Product;
use App\Models\Product\Order;
use App\Models\Test\Test;
use App\Models\Test\Attempt;
use Illuminate\Support\Facades\Cache;

use App\Exports\ScoreExport;
use Maatwebsite\Excel\Facades\Excel;

class CouponController extends Controller
{
    /*
        Coupon Controller
    */

    public function __construct(){
        $this->app      =   'product';
        $this->module   =   'coupon';
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
        
        if($client_slug)
        $objs = $obj->where('code','LIKE',"%{$item}%")->where('client_slug',$client_slug)
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));   
        else
        $objs = $obj->where('code','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('client_list',$client_list)
                ->with('client_slug',$client_slug)
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

        $products  = Product::where('status',1)->where('client_slug',$client_slug)->get();
        $tests  = Test::where('status',1)->where('client_slug',$client_slug)->get();

        $obj->code = strtoupper(\str_random(5));
        $obj->expiry = date("Y-m-d H:i:s",strtotime("+6 month"));

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('products',$products)
                ->with('client_list',$client_list)
                ->with('tests',$tests)
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

            // attach the products
            $products = $request->get('products');
            if($products)
            foreach($products as $product){
                $obj->products()->attach($product);
            }

            // attach the test
            $tests = $request->get('tests');
            if($tests)
            foreach($tests as $test){
                $obj->tests()->attach($test);
            }

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
    public function try()
    {
        return view('appl.product.coupon.try');
    }

    public function use()
    {
        $code = request()->get('code');

        if(!$code)
            abort('403','Coupon code cannot be empty');


        $client_slug = $request->session()->get('client')->slug;
        $coupon = Obj::where('code',$code)->where('client_slug',$client_slug)->first();
         

        if($coupon->status==0){
            abort('403','Coupon code expired');
        }else if(\carbon\Carbon::parse($coupon->expiry)->lte(\carbon\Carbon::now())){
                 abort('403','Coupon code expired');
        }

        if($coupon->enrolled==1 ){
            if(\auth::user()->enrolled!=1)
                abort('403','This coupon can only be used by enrolled students');
        }

        $order = new Order();
        foreach($coupon->products as $p){
            $order->coupon($p->id,null,$coupon);
        }

        foreach($coupon->tests as $t){
            $order->coupon(null,$t->id,$coupon);
        }
        //dd($coupon->tests);
  
        flash('Successfully activated products/tests')->success();
        return redirect()->route('home');
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
            
            // attach the tags
            $products = $request->get('products');
            if($products){
                $obj->products()->detach();
                foreach($products as $product){
                $obj->products()->attach($product);
                }
            }else{
                $obj->products()->detach();
            }

            // attach the tags
            $tests = $request->get('tests');
            if($tests){
                $obj->tests()->detach();
                foreach($tests as $test){
                $obj->tests()->attach($test);
                }
            }else{
                $obj->tests()->detach();
            }

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
