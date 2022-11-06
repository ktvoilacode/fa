<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Page as Obj;
use App\Models\Blog\Blog;
use App\Models\Test\Test;
use App\Models\Blog\Collection;
use Illuminate\Support\Facades\Storage;


class PageController extends Controller
{
     /*
        Test Tags Controller
    */

    public function __construct(){
        $this->app      =   'admin';
        $this->module   =   'page';
        $this->cache_path =  '../storage/app/cache/pages/';
        $this->cache_path_test = '../storage/app/cache/test/';
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
        $objs = $obj->where('content','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 
         /* update in cache folder */
        if($request->refresh){
            foreach($objs as $obj){ 
                $p = explode('/',$obj->slug);

                if(count($p)==1){
                    $filename = $obj->slug.'.json';
                }
                else
                {

                  $slug = implode('_', $p);
                  $filename = $slug.'.json';
                }
                $filepath = $this->cache_path.$filename;

                file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
            }
            flash('Pages Cache Updated')->success();
        }
           

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Obj $obj, Request $request)
    {
        try{
            
            $string = rtrim($request->get('slug'), "/");

            $request->merge(['slug'=>$string]);

            /* create a new entry */
            $obj = $obj->create($request->all());


            /* cache pages */
            $p = explode('/',$obj->slug);
            if(count($p)==1){
              $filename = $obj->slug.'.json';
            }
            else
            {
              $slug = implode('_', $p);
              $filename = $slug.'.json';
            }
            $filepath = $this->cache_path.$filename;
            file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));

            flash('A new page ('.$obj->slug.') item is created!')->success();
            return redirect()->route($this->module.'.view',$obj->slug);
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
    public function show($slug,$s1=null,$s2=null)
    {

       
        if($s2){
          $slug = $slug.'/'.$s1.'/'.$s2;
        }

        $filename = $slug.'.json';
        $filepath = $this->cache_path.$filename;


       
        if(is_numeric($slug) && strlen($slug)==4){
            $this->name = $slug;
            if(domain()!='prep')
            return redirect('https://firstacademy.in/blog', 301); 
            return app('App\Http\Controllers\Blog\CollectionController')->year($slug);
            //app()->call('App\Http\Controllers\Blog\CollectionController@year',[$slug]);
        }

        $p = explode('/',$slug);
        if(count($p)==1){
          $filename = $slug.'.json';
        }
        else
        {
          $slug = implode('_', $p);
          $filename = $slug.'.json';
          $filepath = $this->cache_path.$filename;
        }



        if(Storage::disk('cache')->exists('pages/'.$filename))
        {
            $obj = json_decode(file_get_contents($filepath));

            if(isset($obj->content))
            if(preg_match('/{+(.*?)}/', $obj->content, $regs))
            {
                $test = $regs[1];
                $c = preg_replace('/{+(.*?)}/', '923', $obj->content);
                $pieces = explode(923, $c);
                $obj->intro = $pieces[0];
                $obj->test = $test;
                $obj->conclusion = $pieces[1];
            
            } 

        }else{

            $obj = Obj::where('slug',$slug)->first();

            if(!$obj){
                 $obj = Blog::where('slug',$slug)->first();
                 if(domain()!='prep')
                 return redirect('https://firstacademy.in/'.$slug, 301); 
            }
        }

        if(!$obj)
          abort('404','page not found');

        $try=null;$player =null;
        $categories = null;$dates=null;$test=null;$testtype=null;

       
        if(!isset($obj->description)){
          if(isset($obj->meta_title)){

            $filename = 'dates.json';
            $filepath = $this->cache_path.$filename;
            $dates = json_decode(file_get_contents($filepath));

            $filename = 'categories.json';
            $filepath = $this->cache_path.$filename;
            $categories = json_decode(file_get_contents($filepath));

            $this->app = 'blog';
            $this->module = 'blog';
                if(domain()!='prep')
             return redirect('https://firstacademy.in/'.$slug, 301); 

         }else{
          $obj->description = 'First Academy is the best coaching center for IELTS, GRE, TOEFL, PTE, OET, SAT,  and other international exams in Hyderabad.';
        }
        } 

        if(isset($obj->test))
         if($obj->test){
            $try =1;
            $filename = $this->cache_path_test.'test.'.$obj->test.'.json'; 
            if(file_exists($filename)){
              $this->test = json_decode(file_get_contents($filename));
              $test = $this->test;
              $testtype = $this->test->testtype;
              if(strtolower($testtype->name) == 'listening')
                $player =1;
            }
            else{
              $this->test = Test::where('slug',$obj->test)->first();

              if($this->test){
              $this->test->sections = $this->test->sections;
              $this->test->mcq_order = $this->test->mcq_order;
              $this->test->fillup_order = $this->test->fillup_order;
              $this->test->testtype = $this->test->testtype;
              $this->test->category = $this->test->category;
              //load test and all the extra data
              $this->test->qcount = 0;
              if(!$this->test->qcount){
                  foreach($this->test->mcq_order as $q){
                        if($q->qno)
                          if($q->qno!=-1)
                          $this->test->qcount++;
                  }
                  foreach($this->test->fillup_order as $q){
                        if($q->qno)
                          if($q->qno!=-1)
                          $this->test->qcount++;
                  }
                
              }
              foreach($this->test->sections as $section){ 
                  $ids = $section->id ;
                  $this->test->sections->$ids = $section->extracts;
                  foreach($this->test->sections->$ids as $m=>$extract){
                      $this->test->sections->$ids->mcq =$extract->mcq_order;
                      $this->test->sections->$ids->fillup=$extract->fillup_order;
                  }
                      
              }
               $test = $this->test;
               $testtype = $this->test->testtype;
               if(strtolower($testtype->name) == 'listening')
                $player =1;
            }else{
              $obj->test = null;
            }


          }

           

         }

        if($obj){
            if(\auth::user()){

                if(\auth::user()->admin==1){
                  return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('categories',$categories)->with('app',$this)->with('dates',$dates)->with('try',$try)->with('grammar',$try)->with('player',$player)->with('test',$test)->with('testtype',$testtype);
                }  
                else{
                    if($obj->status==1)
                      return view('appl.'.$this->app.'.'.$this->module.'.show')
                        ->with('obj',$obj)->with('categories',$categories)->with('app',$this)->with('dates',$dates)->with('try',$try)->with('grammar',$try)->with('player',$player)->with('test',$test)->with('testtype',$testtype);
                    else
                      abort(404);
                }
            }else{
                if($obj->status==1)
                      return view('appl.'.$this->app.'.'.$this->module.'.show')
                        ->with('obj',$obj)->with('categories',$categories)->with('app',$this)->with('dates',$dates)->with('try',$try)->with('grammar',$try)->with('player',$player)->with('test',$test)->with('testtype',$testtype);
                    else
                      abort(404);
            }

        }
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

            $obj->update($request->all()); 

            

            /* cache pages */
            $p = explode('/',$obj->slug);
            if(count($p)==1){
              $filename = $obj->slug.'.json';
            }
            else
            {
              $slug = implode('_', $p);
              $filename = $slug.'.json';
            }
            $filepath = $this->cache_path.$filename;
            file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));

            flash('('.$obj->slug.') item is updated!')->success();
            return redirect()->route($this->module.'.view',$obj->slug);
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
        $obj = Obj::where('slug',$id)->first();
        $this->authorize('update', $obj);
        
        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index');
    }
}
