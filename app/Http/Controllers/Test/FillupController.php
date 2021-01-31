<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Fillup as Obj;
use App\Models\Test\Extract;
use App\Models\Test\Section;
use App\Models\Test\Tag;
use App\Models\Test\Test;
use Illuminate\Support\Facades\Storage;

class FillupController extends Controller
{
    /*
        The Fillup Controller
    */

    public function __construct(){
        $this->app      =   'test';
        $this->module   =   'fillup';
        if(request()->route('test')){
            $this->test = Test::find(request()->route('test'));
        } 
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
        
        $objs = $obj->where('test_id',$this->test->id)->where(function ($query) use ($item) {
                $query->where('prefix','LIKE',"%{$item}%")
                    ->orWhere('suffix','LIKE',"%{$item}%")
                     ->orWhere('answer','LIKE',"%{$item}%")
                    ->orWhere('label','LIKE',"%{$item}%");
                    })
                    ->orderBy('sno','asc')
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
        $test_id = $this->test->id;



        /* add the serial number */
        if(Obj::where('test_id',$test_id)->orderBy('id','desc')->first()){
            $f = Obj::where('test_id',$test_id)->orderBy('id','desc')->first();
            $this->sno = $f->sno+1;
            $this->qno = $f->qno+1;
        }
        else{
            $this->sno = 1;
            $this->qno = 1;
        }

        $extracts = Extract::where('test_id',$this->test->id)->get();
        $sections = Section::where('test_id',$this->test->id)->get();
        $tags = Tag::all();
        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('extracts',$extracts)
                ->with('sections',$sections)
                ->with('tags',$tags)
                ->with('app',$this);
    }

    public function layout()
    {
        $obj = new Obj();
        $this->authorize('create', $obj);
        $test_id = $this->test->id;

        return view('appl.'.$this->app.'.'.$this->module.'.layout')
                ->with('stub','Create')
                ->with('obj',$obj)
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
          
            $request->session()->put('extract_id', $request->get('extract_id'));

            if(!$request->get('prefix') && !$request->get('label') && !$request->get('suffix') && !$request->get('answer'))
            {
                flash('Data cannot be empty')->error();
                 return redirect()->back()->withInput();
            }

            /* create a new entry */
            $obj = $obj->create($request->except(['tags']));

            /* If file is given upload and store path */
            if(isset($request->all()['file_'])){
                $file      = $request->all()['file_'];
                $filename = $obj->id.'_q.'.$file->getClientOriginalExtension();
                $path_ = Storage::disk('public')->putFileAs('extracts', $request->file('file_'),$filename);
            }

            if(isset($request->all()['file_img_'])){
                $file      = $request->all()['file_img_'];
                $filename = $obj->id.'_q.'.$file->getClientOriginalExtension();
                $path_ = Storage::disk('public')->putFileAs('extracts', $request->file('file_img_'),$filename);
                image_jpg($path_,'300');
            }

            if(isset($request->all()['file2_'])){
                $alpha = ["a","b","c","d","e","f","g","h","i","j","k","l"];
                foreach($request->all()['file2_'] as $i=>$file){
                    $k = $alpha[$i];
                    $filename = $obj->id.'_'.$k.'.'.$file->getClientOriginalExtension();
                    $path_ = Storage::disk('public')->putFileAs('extracts', $file,$filename); 
                }
                
            }

            // attach the tags
            $tags = $request->get('tags');
            if($tags)
            foreach($tags as $tag){
                $obj->tags()->attach($tag);
            }

            //update extract
            if($request->get('extract_id'))
            {
                $obj->extract->extract_update($obj->qno);
            }

            flash('A new ('.$this->app.'/'.$this->module.') item is created!')->success();
            return redirect()->route($this->module.'.index',[$this->test->id]);
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('Some error in Creating the record')->error();
                 return redirect()->back()->withInput();;
            }
        }
    }


    public function d($test_id,$id)
    {
        $obj = Obj::where('id',$id)->first();
    
        $this->authorize('view', $obj);

        $last = Obj::where('test_id',$test_id)->orderBy('id','desc')->first();
        $str = substr(md5(time()), 0, 7);
        $f_new = $obj->replicate();
        $f_new->sno = intval($last->sno)+1;
        $f_new->qno = intval($last->qno)+1;
        $f_new->save();


        if($obj)
            return redirect()->route('fillup.index',$test_id);
        else
            abort(404);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($test_id,$id)
    {
        $obj = Obj::where('id',$id)->first();

        if(request()->get('deleteimages')){
            $filename = $obj->id.'_q_300.png';
            
            if(Storage::disk('public')->exists('extracts/'.$filename))
                Storage::disk('public')->delete('extracts/'.$filename);

            $filename = $obj->id.'_q_300.jpg';
            if(Storage::disk('public')->exists('extracts/'.$filename))
                Storage::disk('public')->delete('extracts/'.$filename);
        }

        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('f',$obj)
                    ->with('try',1)
                    ->with('grammar',1)
                    ->with('answers',false)
                    ->with('test',$this->test)
                    ->with('app',$this);
        else
            abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($test_id,$id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        $extracts = Extract::where('test_id',$this->test->id)->get();
        $sections = Section::where('test_id',$this->test->id)->get();
        $tags = Tag::all();

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('extracts',$extracts)
                ->with('sections',$sections)
                ->with('tags',$tags)
                ->with('app',$this);
        else
            abort(404);
    }

    public function ajaxupdate(Request $request, $test_id,$id)
    {
        try{
            $obj = Obj::where('id',$id)->first();

            $obj = $obj->update($request->except(['tags'])); 

            echo 1;
            dd();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $test_id,$id)
    {
        try{
            $obj = Obj::where('id',$id)->first();

            $request->session()->put('extract_id', $request->get('extract_id'));
            $this->authorize('update', $obj);

            $tags = $request->get('tags');
            if($tags){
                $obj->tags()->detach();
                foreach($tags as $tag){
                $obj->tags()->attach($tag);
                }
            }else{
                $obj->tags()->detach();
            }

            //update extract
            if($request->get('extract_id'))
            {
                if($obj->extract)
                $obj->extract->extract_update($obj->qno);
            }

            /* If file is given upload and store path */
            if(isset($request->all()['file_'])){
                $file      = $request->all()['file_'];
                $filename = $obj->id.'_q.'.$file->getClientOriginalExtension();
                $path_ = Storage::disk('public')->putFileAs('extracts', $request->file('file_'),$filename);
            }

            if(isset($request->all()['file_img_'])){
                $file      = $request->all()['file_img_'];
                $filename = $obj->id.'_q.'.$file->getClientOriginalExtension();
                $path_ = Storage::disk('public')->putFileAs('extracts', $request->file('file_img_'),$filename);
                image_jpg($path_,'300');
            }
            

            if(isset($request->all()['file2_'])){
                $alpha = ["a","b","c","d","e","f","g","h","i","j","k","l"];
                foreach($alpha as $k){
                    $filename = $obj->id.'_'.$k.'.mp3';
                    if(Storage::disk('public')->exists('extracts/'.$filename))
                        Storage::disk('public')->delete('extracts/'.$filename);
                }
                foreach($request->all()['file2_'] as $i=>$file){
                    
                    $k = $alpha[$i];
                    $filename = $obj->id.'_'.$k.'.'.$file->getClientOriginalExtension();
                    $path_ = Storage::disk('public')->putFileAs('extracts', $file,$filename); 
                }
                
            }

            $obj = $obj->update($request->except(['tags'])); 

            flash('('.$this->app.'/'.$this->module.') item is updated!')->success();
            return redirect()->route($this->module.'.show',[$test_id,$id]);
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
    public function destroy($test_id,$id)
    {
        $obj = Obj::where('id',$id)->first();
        $this->authorize('update', $obj);
        

        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index',$this->test->id);
    }
}
