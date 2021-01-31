<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Mcq as Obj;
use App\Models\Test\Extract;
use App\Models\Test\Section;
use App\Models\Test\Test;
use App\Models\Test\Tag;

class McqController extends Controller
{
    /*
        The MCQ Controller
    */

    public function __construct(){
        $this->app      =   'test';
        $this->module   =   'mcq';
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
                $query->where('question','LIKE',"%{$item}%")
                    ->orWhere('a','LIKE',"%{$item}%")
                     ->orWhere('b','LIKE',"%{$item}%")
                    ->orWhere('c','LIKE',"%{$item}%");
                    })
                    ->orderBy('extract_id','asc')
                    ->orderBy('qno','asc')
                    ->paginate(config('global.no_of_records'));   
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('try',true)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $obj = new Obj();
        $this->authorize('create', $obj);

        /* add the serial number */
        if(Obj::orderBy('id','desc')->where('test_id',$this->test->id)->first()){
            $m = Obj::orderBy('id','desc')->where('test_id',$this->test->id)->first();
            $this->qno = $m->qno +1;
            $this->sno = $m->sno+1;
        }
        else{
            $this->qno =1;
            $this->sno = 1;
        }

        if(request()->get('layout'))
            $obj->layout = request()->get('layout');

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Obj $obj, Request $request)
    {
        try{
            $user = \auth::user();

            $request->session()->put('extract_id', $request->get('extract_id'));
            
            $this->imageupdater($request);
            
            
            // for multi answer
            $answers = $request->get('answers');
            if($answers){
                $answer = implode(', ', $answers);
                /* merge the updated data in request */
                $request->merge(['answer'=>$answer]);
            }
            

            /* create a new entry */
            $obj = $obj->create($request->except(['tags']));

            // attach the tags
            $tags = $request->get('tags');
            if($tags)
            foreach($tags as $tag){
                $obj->tags()->attach($tag);
            }

            
            //update extract
            if($request->get('extract_id'))
            {
                if($obj->extract)
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

    public function imageupdater($request){

        $user = \auth::user();
        $options =['question','a','b','c','d','e','f','g','h','i'];
        foreach($options as $opt){
            $text = $request->get($opt);
            if(preg_match('/<img/', $text))
                $text = summernote_imageupload($user,$text);
            $request->merge([$opt=>$text]);
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
            return redirect()->route('mcq.index',$test_id);
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

        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)
                    ->with('m',$obj)
                    ->with('answers',null)
                    ->with('app',$this)
                    ->with('try',true)
                    ->with('gre',1);
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

            $this->authorize('update', $obj);

            $user = \auth::user();
            $request->session()->put('extract_id', $request->get('extract_id'));
            /* upload images if any */
            $question = summernote_imageupload($user,$request->get('question'));
            $a = summernote_imageupload($user,$request->get('a'));
            $b = summernote_imageupload($user,$request->get('b'));
            $c= summernote_imageupload($user,$request->get('c'));
            $d= summernote_imageupload($user,$request->get('d'));

            foreach(['a','b','c','d'] as $item){
                if(trim($request->get($item))=='<p><br></p>')
                    $request->merge([$item=>'']);
            }
            
            // for multi answer
            $answers = $request->get('answers');
            if($answers){
                $answer = implode(', ', $answers);
                /* merge the updated data in request */
                $request->merge(['answer'=>$answer]);
            }else{
                $request->merge(['answer'=>'']);
            }

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

            
            $obj = $obj->update($request->all()); 

           

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
        
        /* remove images before deleting */
        summernote_imageremove($obj->question);
        summernote_imageremove($obj->a);
        summernote_imageremove($obj->b);
        summernote_imageremove($obj->c);
        summernote_imageremove($obj->d);

        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index',$this->test->id);
    }
}
