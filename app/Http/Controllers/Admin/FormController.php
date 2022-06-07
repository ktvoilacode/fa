<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Form as Obj;
use App\Models\Test\Test;
use App\Mail\RequestForm;
use App\Mail\NotifyContact;

use Illuminate\Support\Facades\Mail;

class FormController extends Controller
{
     /*
        Test Tags Controller
    */

    public function __construct(){
        $this->app      =   'admin';
        $this->module   =   'form';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj,Request $request)
    {
        
        if($request->get('closeall')){
            $objs = $obj->get();
            foreach($objs as $o){
                $o->status=1;
                $o->save();
            }
        }
        $search = $request->search;
        $item = $request->item;
        $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->orWhere('phone','LIKE',"%{$item}%")
                    ->orWhere('email','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));   

        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
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

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('app',$this);
    }


    public function request()
    {
        $obj = new Obj();
        $tests = Test::where('price',0)->where('status',1)->get()->random(3);

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('tests',$tests)
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
    public function save(Obj $obj, Request $request)
    {
        try{
            
            if($request->phone==null){
            flash('Phone number cannot be empty')->error();
                 return redirect()->back()->withInput();
            }

            if($request->email==null){
            flash('Email cannot be empty')->error();
                 return redirect()->back()->withInput();
            }

            if($request->name==null){
                flash('Name cannot be empty')->error();
                     return redirect()->back()->withInput();
            }

            if($request->year){
                if(!is_numeric($request->year)){
                   flash('Year of passing has to be a number')->error();
                     return redirect()->back()->withInput(); 
                }
                
            }

            if($request->get('source')){
                $request->merge(['description'=>"<p>".$request->get('description')."</p><p>Source:<b>".$request->get('source')."</b></p>"]);
            }
            
            /* create a new entry */
            $obj = $obj->create($request->all());

            Mail::to(config('mail.report'))->send(new  RequestForm($request));
            Mail::to(config('mail.report2'))->send(new  RequestForm($request));

            flash('Your request is registered. Our Counsellors will get in touch with you soon.')->warning();
            return redirect()->route('form.request');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('Some error in Creating the record')->error();
                 return redirect()->back()->withInput();;
            }
        }
    }

    public function ajaxx(Request $request)
    {
        try{
            
            if($request->phone==null){
                $message = 'Phone number cannot be empty';
            }

            if($request->email==null){
                $message = 'Email cannot be empty';
            }

            if($request->name==null){
                $message = 'Name cannot be empty';
            }

            if(isset($message))
            {
                echo $message;
            }else{

                $obj = new Obj();
                $obj->name = $request->name;
                $obj->phone = $request->phone;
                $obj->email = $request->email;
                $obj->subject = $request->subject;
                $description ='';
                foreach($request->all() as $k=>$r){
                    if(is_array($r))
                        $r = implode(',', $r);
                    if($k!='_token' && $k!='_method' && $k!='url')
                    $description = $description. '<div>'.strtoupper($k).' - '.$r.'</div>' ;
                }
                $obj->description = $description;
                $obj->year = 0;
                $obj->college = '';
                $obj->save();

                Mail::to(config('mail.report'))->send(new  RequestForm($request));
                Mail::to(config('mail.report2'))->send(new  RequestForm($request));

                $message = 'Your request is registered. Our Counsellors will get in touch with you soon.';

                echo $message;
            }
            
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Obj $obj, Request $request)
    {
        try{
            
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
        $user = \auth::user()->where('email',$obj->email)->first();
        if(!$user)
            $user = \auth::user()->where('phone',$obj->phone)->first();

        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('app',$this)->with('user',$user);
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


        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
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
        try{
            $obj = Obj::where('id',$id)->first();


            $this->authorize('update', $obj);
            
            /* Change to uppercase */
            if($request->get('name')){
                $request->merge(['name' => strtoupper($request->get('name'))]);
            }

            if(!$request->get('phone')){
                $request->merge(['phone' => '0']);
            }

            if(!$request->get('college')){
                $request->merge(['college' => ' ']);
            }

            
            
            $obj->update($request->all()); 
            
            if($request->get('status')==2){
                $pieces = explode('TEST -',strip_tags($obj->description));
                //dd($pieces);
                if(isset($pieces[1]))
                {
                    $obj->test_name = trim($pieces[1]);
                }

                Mail::to($obj->email)->send(new  NotifyContact($obj));
                $request->merge(['status',1]);
            }
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
