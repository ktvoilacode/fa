<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Client as Obj;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Product\Product;
use App\Models\Test\Test;

class ClientController extends Controller
{
    /*
        Client Controller
    */

    public function __construct(){
        $this->app      =   'product';
        $this->module   =   'client';
        $this->cache_path =  '../storage/app/cache/clients/';
        $this->image_path =  '../storage/app/public/clients/';
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
        
        $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));   
        $admins  = User::whereIn('id',$objs->pluck('user_id')->toArray())->get()->keyBy('id');
        $usercount = User::select('client_slug')->whereIn('client_slug',$objs->pluck('slug')->toArray())->get()->groupBy('client_slug');

        foreach($objs as $k=>$obj){
            $objs[$k]->users = 0;
            if(isset($usercount[$obj->slug]))
            $objs[$k]->users = count($usercount[$obj->slug]);
        }

        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('admins',$admins)
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
        $products = Product::all();
        $this->authorize('create', $obj);
        if(!$obj->config){
            $settings['register'] = NULL;
            $settings['change_password'] = NULL;
            $settings['add_users'] = NULL;
            $settings['contact'] = NULL;
            $settings['message_d'] = NULL;
            $settings['timer_d'] = NULL;
            $settings['message_l'] = NULL;
            $settings['timer_l'] = NULL;
            $settings['message_r'] = NULL;
            $settings['timer_r'] = NULL;
            $settings['key'] = NULL;
            $settings['token'] = NULL;
            $settings['rform'] = NULL;
            $settings['default_coupon'] = NULL;
            $obj->config = json_decode(json_encode($settings));
        }

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('products',$products)
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
            $client = new Obj();
            $request->slug = str_replace(' ', '-', $request->slug);
            $client_exists = Obj::where('slug',$request->slug)->first();
            if($client_exists){
                flash('The slug(<b>'.$request->slug.'</b>) is already taken. Kindly use a different slug.')->error();
                 return redirect()->back()->withInput();
            }

            $admin_exists = User::where('email',$request->admin_email)->where('client_slug',$request->slug)->first();
            if($admin_exists){
                flash('The admin account(<b>'.$request->admin_email.'</b>) is already taken. Kindly use a different email.')->error();
                 return redirect()->back()->withInput();
            }

            /* update in cache data */
            $domains = explode(',', strtolower($request->get('domains')));
            if(!$request->get('domains'))
                $domains=[$request->slug.".gradable.in"];
            else
                array_push($domains,$request->slug.".gradable.in");

            $client->name = $request->name;
            $client->slug = strtolower($request->slug);
            $client->domains = implode(',', $domains);
            $client->user_id = null;
            $client->status = $request->status;
            $settings['register'] = $request->get('register');
            $settings['contact'] = $request->get('contact');
            $settings['change_password'] = $request->get('change_password');
            $settings['add_users'] = $request->get('add_users');
            $settings['message_d'] = $request->get('message_d');
            $settings['timer_d'] = $request->get('timer_d');
            $settings['message_l'] = $request->get('message_l');
            $settings['timer_l'] = $request->get('timer_l');
            $settings['message_r'] = $request->get('message_r');
            $settings['timer_r'] = $request->get('timer_r');
            $settings['key'] = $request->get('key');
            $settings['token'] = $request->get('token');
            $settings['rform'] = $request->get('rform');
            $settings['default_coupon'] = $request->get('default_coupon');
            

             /* If image is given upload and store path */
            if(isset($request->all()['file_logo'])){
                $file      = $request->all()['file_logo'];
                $filename = $request->get('slug').'_logo.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_logo'),$filename,'public');
                $settings['image_logo'] = $path;
            }

            if(isset($request->all()['file_login'])){
                $file      = $request->all()['file_login'];
                $filename = $request->get('slug').'_login.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_login'),$filename,'public');
                $settings['image_login'] = $path;
            }

            if(isset($request->all()['file_dashboard'])){
                $file      = $request->all()['file_dashboard'];
                $filename = $request->get('slug').'_dashboard.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_dashboard'),$filename,'public');
                $settings['image_dashboard'] = $path;
            }

            if(isset($request->all()['file_favicon'])){
                $file      = $request->all()['file_favicon'];
                $filename = $request->get('slug').'file_favicon.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_favicon'),$filename,'public');
                $settings['image_favicon'] = $path;
            }

            if(isset($request->all()['file_register'])){
                $file      = $request->all()['file_register'];
                $filename = $request->get('slug').'_register.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_register'),$filename,'public');
                $settings['image_register'] = $path;
            }

            $client->config = json_encode($settings);
            $client->save(); 
            
            $products = $request->get('product');
            $product_list =  Product::all()->pluck('id')->toArray();
            if($products)
            foreach($product_list as $product){
                if(in_array($product, $products)){
                    if(!$client->products->contains($product))
                        $client->products()->attach($product);
                }else{
                    if($client->products->contains($product))
                        $client->products()->detach($product);
                }
                
            } 

            if($request->get('admin_name')){
                $password = $request->admin_phone;
                 $user = User::create([
                    'name' => $request->admin_name,
                    'email' => strtolower($request->admin_email),
                    'phone' =>$request->admin_phone,
                    'password' => bcrypt($password),
                    'activation_token' => $password,
                    'client_slug' =>$client->slug,
                    'admin'=>5,
                    'status'=>1,
                ]);

                $user->save();

               $client->user_id = $user->id;
               $client->save();
            }


            if($request->get('admin_name')){
                $password = 'demo500';
                 $user2 = User::create([
                    'name' => 'Demo User',
                    'email' => strtolower('demo500@gradable.in'),
                    'phone' =>'12345',
                    'password' => bcrypt($password),
                    'activation_token' => $password,
                    'client_slug' =>$client->slug,
                    'admin'=>0,
                    'status'=>1,
                ]);
                
            }
            
            //update the cache
            foreach($domains as $d){
                Cache::forever('client_'.$d,json_encode($obj,JSON_PRETTY_PRINT));
            }

            // add all client list to Cache
            $client_list = Obj::all();
            Cache::forget('client_list');
            Cache::forever('client_list',$client_list);

        
            flash('A new client('.$request->name.') is created!')->success();
            return redirect()->route('client.index');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('The slug(<b>'.$request->slug.'</b>) is already taken. Kindly use a different slug.')->error();
                 return redirect()->back()->withInput();
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
        $products = Product::all();
        $obj->config = json_decode($obj->config);
        

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('products',$products)
                ->with('obj',$obj)
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
        $client = Obj::where('id',$id)->first();
        $domains = explode(',',$client->domains);
        //forget cache
        foreach($domains as $d){
            Cache::forget('client_'.$d);
        }
        
        try{
            $request->slug = str_replace(' ', '-', $request->slug);
            
             /* update in cache data */
            $domains = explode(',', strtolower($request->get('domains')));
            if(!in_array($request->slug.".gradable.in",$domains))
                array_push($domains,$request->slug.".gradable.in");

            $this->authorize('update', $client);
            $client->name = $request->name;
            $client->slug = strtolower($request->slug);
            $client->domains = implode(',', $domains);
            $client->status = $request->status;
            $settings['register'] = $request->get('register');
            $settings['contact'] = $request->get('contact');
            $settings['change_password'] = $request->get('change_password');
            $settings['add_users'] = $request->get('add_users');
            $settings['message_d'] = $request->get('message_d');
            $settings['timer_d'] = $request->get('timer_d');
            $settings['message_l'] = $request->get('message_l');
            $settings['timer_l'] = $request->get('timer_l');
            $settings['message_r'] = $request->get('message_r');
            $settings['timer_r'] = $request->get('timer_r');
            $settings['key'] = $request->get('key');
            $settings['token'] = $request->get('token');
            $settings['rform'] = $request->get('rform');
            $settings['default_coupon'] = $request->get('default_coupon');
           

              /* If image is given upload and store path */
            if(isset($request->all()['file_logo'])){
                $file      = $request->all()['file_logo'];
                $filename = $request->get('slug').'_logo.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_logo'),$filename,'public');
                $settings['image_logo'] = $path;
            }

            if(isset($request->all()['file_login'])){
                $file      = $request->all()['file_login'];
                $filename = $request->get('slug').'_login.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_login'),$filename,'public');
                $settings['image_login'] = $path;
            }

            if(isset($request->all()['file_dashboard'])){
                $file      = $request->all()['file_dashboard'];
                $filename = $request->get('slug').'_dashboard.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_dashboard'),$filename,'public');
                $settings['image_dashboard'] = $path;
            }

            if(isset($request->all()['file_favicon'])){
                $file      = $request->all()['file_favicon'];
                $filename = $request->get('slug').'file_favicon.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_favicon'),$filename,'public');
                $settings['image_favicon'] = $path;
            }

            if(isset($request->all()['file_register'])){
                $file      = $request->all()['file_register'];
                $filename = $request->get('slug').'_register.'.$file->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('clients', $request->file('file_register'),$filename,'public');
                $settings['image_register'] = $path;
            }

            $client->config = json_encode($settings);
            $client->save(); 

            $products = $request->get('product');
            $product_list =  Product::all()->pluck('id')->toArray();
            if($products)
            foreach($product_list as $product){
                if(in_array($product, $products)){
                    if(!$client->products->contains($product))
                        $client->products()->attach($product);
                }else{
                    if($client->products->contains($product))
                        $client->products()->detach($product);
                }
                
            }else{
                 $client->products()->detach();
            }

             //update the cache
            foreach($domains as $d){
                Cache::forever('client_'.$d,json_encode($client,JSON_PRETTY_PRINT));
            }

            // add all client list to Cache
            $client_list = Obj::all();
            Cache::forget('client_list');
            Cache::forever('client_list',$client_list);


            flash('client (<b>'.$request->name.'</b>) Successfully updated!')->success();
            return redirect()->route('client.show',$request->id);
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('The slug(<b>'.$request->slug.'</b>) is already taken. Kindly use a different slug.')->error();
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
