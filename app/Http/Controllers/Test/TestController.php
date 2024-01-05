<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Test as Obj;
use App\Models\Test\Tag;
use App\Models\Test\Fillup;
use App\Models\Test\Mcq;
use App\Models\Test\Type;
use App\Models\Test\Attempt;
use App\Models\Test\Category;
use App\Models\Test\Group;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    /*
        The Core Test Application Controller
    */

    public function __construct(){
        $this->app      =   'test';
        $this->module   =   'test';
        $this->cache_path =  '../storage/app/cache/test/';
    }

     /* The public view page for test */
   public function details($slug,Request $request){

        if(domainname()=='piofx'){
            abort('404','Access not allowed');
        }
        //load from cache, else from database
        $obj = Cache::get('test_'.$slug);
        if(!$obj){
            $obj = Obj::where('slug',$slug)->first();
            Cache::forever('test_'.$slug,$obj);
        }

        $settings = json_decode($obj->settings);

        

        $active = 1;
        if(isset($settings->activation))
        {   
            $auto_activation  = \carbon\carbon::parse($settings->activation);
            $auto_deactivation  = \carbon\carbon::parse($settings->deactivation);

            $active = 0;
            if($auto_activation->lt(\carbon\carbon::now()) && $auto_deactivation->gt(\carbon\carbon::now())){
               $active = 1;
            }
        }else{

        }


        if(!$obj)
            abort('404');
        
        if($active)
        return view('appl.test.test.details')
                ->with('test',$obj)
                ->with('obj',$obj);
        else
             return view('appl.test.test.inactive')
                ->with('exam',$obj)
                ->with('settings',$settings)
                ->with('obj',$obj);

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
        $sort = $request->sort;

        $objs = $obj->sortable()->where('name','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 

        if($request->get('refresh')){
            foreach($objs as $obj)
                //$this->cache_refresh($obj->id);
            $this->cache_refresh2($obj->id);
            flash('cache is updated!')->success();
        }

        if($request->get('imageupdate')){
            foreach($objs as $obj){
                    $user = \auth::user();
                    /* upload images if any */
                    $obj->details = summernote_imageupload($user,$obj->details);
                
                $obj->save();
                 
            }
            flash('images updated!')->success();
        }
        
        $view = ($search ) ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
    }


    public function public(Obj $obj,Request $request)
    {

        if(domainname()=='piofx'){
            abort('404','Access not allowed');
        }
        $search = $request->search;
        $item = $request->item;
        $category = $request->category;
        $type = $request->type;
        $category_id = null;
        $categories = Category::where('status',1)->get();

        

        if($category)
        {
            $cate = Category::where('slug',$category)->first();
            $category_id = $cate->id;
            if($type=='free')
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('category_id',$category_id)
                    ->where('status',1)
                    ->where('price',0)
                    ->orderBy('name','asc')
                    ->paginate(18);
            else if($type=='premium')
             $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('status',1)
                    ->where('category_id',$category_id)
                    ->where('price','!=',0)
                    ->orderBy('name','asc')
                    ->paginate(18);  
            else
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('status',1)
                    ->where('category_id',$category_id)
                    ->orderBy('name','asc')
                    ->paginate(18);   
        }else{
            if($type=='free')
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('status',1)
                    ->where('price',0)
                    ->orderBy('name','asc')
                    ->paginate(18); 
            else if($type=='premium')
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('status',1)
                    ->where('price','!=',0)
                    ->orderBy('name','asc')
                    ->paginate(18); 
            else
            $objs = $obj->where('name','LIKE',"%{$item}%")
                    ->where('status',1)
                    ->orderBy('name','asc')
                    ->paginate(18); 
        }


        
        
        $view = $search ? 'public_list2': 'public_index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('categories',$categories)
                ->with('app',$this);
    }

     public function cache_refresh2($id){
        $obj= Obj::where('id',$id)->first();

        $test = Obj::where('id',$id)->first();
        $test->updated_at = \Carbon\Carbon::now();
        $test->sections = $obj->sections;

        $test->mcq_order = $obj->mcq_order;
        $test->fillup_order = $obj->fillup_order;
        $test->testtype = $obj->testtype;
        $test->category = $obj->category;


        $test->qcount =0;
        foreach($obj->sections as $i=>$section){ 
            $ids = $section->id ;
            $obj->sections[$i]->mcq_order =$section->mcq_order;
            $obj->sections[$i]->fillup_order =$section->fillup_order;
            $obj->sections->$ids = $section->extracts;
            foreach($obj->sections->$ids as $m=>$extract){
                $obj->sections->$ids->mcq =$extract->mcq_order;
                $obj->sections->$ids->fillup=$extract->fillup_order;
            }
                
        }

        foreach($test->mcq_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
          }
          foreach($test->fillup_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
          }

        Cache::forever('test_'.$id,$test);
        Cache::forever('test_'.$slug,$test);
 
    }

    public function cache_refresh($id){
        $obj= Obj::where('id',$id)->first();
        /* update in cache folder */
        $filename = $this->cache_path.$this->app.'.'.$obj->slug.'.json'; 

        $test = Obj::where('id',$id)->first();
        $test->updated_at = \Carbon\Carbon::now();
        $test->sections = $obj->sections;

        $test->mcq_order = $obj->mcq_order;
        $test->fillup_order = $obj->fillup_order;
        $test->testtype = $obj->testtype;
        $test->category = $obj->category;


        $test->qcount =0;
        foreach($obj->sections as $i=>$section){ 
            $ids = $section->id ;
            $obj->sections[$i]->mcq_order =$section->mcq_order;
            $obj->sections[$i]->fillup_order =$section->fillup_order;
            $obj->sections->$ids = $section->extracts;
            foreach($obj->sections->$ids as $m=>$extract){
                $obj->sections->$ids->mcq =$extract->mcq_order;
                $obj->sections->$ids->fillup=$extract->fillup_order;
            }
                
        }

        foreach($test->mcq_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
          }
          foreach($test->fillup_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
          }


 
        file_put_contents($filename, json_encode($test,JSON_PRETTY_PRINT));
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

        $types = Type::all();
        $categories = Category::where('status',1)->get();
        $groups = Group::where('status',1)->orderBy('id','desc')->get();
         $client_list = Cache::get('client_list');

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('types',$types)
                ->with('categories',$categories)
                ->with('client_list',$client_list)
                ->with('groups',$groups)
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
            
            // update slug with name if its empty
            if(!$request->get('slug')){
                $request->merge(['slug' => strtolower(str_replace(' ','-',$request->get('name')))]);
            }

            $test = Obj::where('slug',$request->get('slug'))->first();

            if($test){
                flash('Test slug duplication - change slug')->error();
                 return redirect()->back()->withInput();
            }

            /* If file is given upload and store path */
            if(isset($request->all()['file_'])){
                $file      = $request->all()['file_'];
                $path = Storage::disk('public')->putFile('extracts', $request->file('file_'));
                $request->merge(['file' => $path]);
            }

           


            /* If image is given upload and store path */
            if(isset($request->all()['image_'])){
                $file      = $request->all()['image_'];
                $filename = $request->get('slug').'.'.$file->getClientOriginalExtension();
                $path_ = Storage::disk('public')->putFileAs($this->module, $request->file('image_'),$filename);
                $request->merge(['image' => $path_]);
            }

            $sizes = [300,600,900,1200];
            if(isset($path_))
            foreach($sizes as $s)
                image_resize($path_,$s);

            $user = \auth::user();
            /* upload images if any */
            $text = summernote_imageupload($user,$request->get('description'));
            
            /* merge the updated data in request */
            $request->merge(['description' => $text]);

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

            $settings['noreport'] = 0;
            if($request->noreport){
                $settings['noreport'] = 1;
            }

            $settings['sidebox'] = 0;
            if($request->sidebox){
                $settings['sidebox'] = 1;
            }

            $settings['audio_permission'] = 0;
            if($request->audio_permission){
                $settings['audio_permission'] = 1;
            }
            $settings['back_re'] = 0;
            if($request->back_re){
                $settings['back_re'] = 1;
            }

            $settings['uftimer'] = 0;
            if($request->uftimer){
                $settings['uftimer'] = 1;
            }
            
            $settings['hide_player'] = 0;
            if($request->hide_player){
                $settings['hide_player'] = 1;
            }

            $request->merge(['settings' => json_encode($settings)]);

            if($request->get('details')){
                $user = \auth::user();
                /* upload images if any */
                $text = summernote_imageupload($user,$request->get('details'));
                
                /* merge the updated data in request */
                $request->merge(['details' => $text]);
            }

            /* create a new entry */
            $obj = $obj->create($request->all());

            flash('A new ('.$this->app.'/'.$this->module.') item is created!')->success();
            return redirect()->route($this->module.'.index');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('Some error in Creating the record')->error();
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



        // $filename = $this->cache_path.$this->app.'.'.$obj->slug.'.json'; 
        // if(file_exists($filename)){
        //     $json = json_decode(file_get_contents($filename)); 
        //     $obj->cache_updated_at = $json->updated_at;
        // }
        $cache_data = Cache::get('test_'.$id); 
        if($cache_data){
            $obj->cache_updated_at = $cache_data->updated_at;
        }

        //dd($cache_data);

        $app = $this;
        $app->test= $obj;


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
        $settings = json_decode($obj->settings,true);
        $hide_player = false;
    

         
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('app',$this)
                    ->with('test',$obj)
                    ->with('testtype',$obj->testtype)
                    ->with('hide_player',$hide_player)
                    ->with('player',true)
                    ->with('testedit',true)
                    ->with('grammar',1)
                    ->with('answers',0)
                    ->with('try',1);
        else
            abort(404);
    }

    
    public function cacheold($id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        /* update in cache folder */
        $filename = $this->cache_path.$this->app.'.'.$obj->slug.'.json'; 

        if(file_exists($filename))
            flash('cache is updated!')->success();
        else
            flash('cache is created!')->success();

        $test = Obj::where('id',$id)->first();
        $test->updated_at = \Carbon\Carbon::now();
        $test->sections = $obj->sections;

        $test->mcq_order = $obj->mcq_order;
        $test->fillup_order = $obj->fillup_order;

        foreach($obj->mcq_order as $e => $f){
            if($f->extract)
                $obj->mcq_order[$e]->extract = $f->extract;
        }
        foreach($obj->fillup_order as $e => $f){
            if($f->extract)
                $obj->fillup_order[$e]->extract = $f->extract;
        }

        $test->testtype = $obj->testtype;
        $test->category = $obj->category;

        $test->qcount =0;
        foreach($obj->sections as $i=>$section){ 
            $ids = $section->id ;
            $obj->sections[$i]->mcq_order =$section->mcq_order;
            $obj->sections[$i]->fillup_order =$section->fillup_order;

            foreach($obj->sections[$i]->mcq_order as $e => $f){
                if($f->extract)
                    $obj->sections[$i]->mcq_order[$e]->extract = $f->extract;
            }
            foreach($obj->sections[$i]->fillup_order as $e => $f){
                if($f->extract)
                    $obj->sections[$i]->fillup_order[$e]->extract = $f->extract;
            }

            $obj->sections->$ids = $section->extracts;
            foreach($obj->sections->$ids as $m=>$extract){
                $obj->sections->$ids->mcq =$extract->mcq_order;
                $obj->sections->$ids->fillup=$extract->fillup_order;
            }
                
        }

        foreach($test->mcq_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
        }
        foreach($test->fillup_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
        }
 
        file_put_contents($filename, json_encode($test,JSON_PRETTY_PRINT));
        return redirect()->route($this->module.'.show',$id);
    }

    public function cache($id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        /* update in cache folder */
        $cache_data = Cache::get('test_'.$id); 

        if($cache_data)
            flash('cache is updated!')->success();
        else
            flash('cache is created!')->success();

        $test = Obj::where('id',$id)->first();
        $test->updated_at = \Carbon\Carbon::now();
        $test->sections = $obj->sections;

        $test->mcq_order = $obj->mcq_order;
        $test->fillup_order = $obj->fillup_order;

        foreach($obj->mcq_order as $e => $f){
            if($f->extract)
                $obj->mcq_order[$e]->extract = $f->extract;
        }
        foreach($obj->fillup_order as $e => $f){
            if($f->extract)
                $obj->fillup_order[$e]->extract = $f->extract;
        }

        $test->testtype = $obj->testtype;
        $test->category = $obj->category;

        $test->qcount =0;
        foreach($obj->sections as $i=>$section){ 
            $ids = $section->id ;
            $obj->sections[$i]->mcq_order =$section->mcq_order;
            $obj->sections[$i]->fillup_order =$section->fillup_order;

            foreach($obj->sections[$i]->mcq_order as $e => $f){
                if($f->extract)
                    $obj->sections[$i]->mcq_order[$e]->extract = $f->extract;
            }
            foreach($obj->sections[$i]->fillup_order as $e => $f){
                if($f->extract)
                    $obj->sections[$i]->fillup_order[$e]->extract = $f->extract;
            }

            $obj->sections->$ids = $section->extracts;
            foreach($obj->sections->$ids as $m=>$extract){
                $obj->sections->$ids->mcq =$extract->mcq_order;
                $obj->sections->$ids->fillup=$extract->fillup_order;
            }
                
        }

        foreach($test->mcq_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
        }
        foreach($test->fillup_order as $q){
            if($q->qno)
              if($q->qno!=-1)
                  $test->qcount++;
        }
 
        Cache::forever('test_'.$id,$test);
        Cache::forever('test_'.$test->slug,$test);
        return redirect()->route($this->module.'.show',$id);
    }

    public function duplicate($id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        $str = substr(md5(time()), 0, 7);
        $test_new = $obj->replicate();
        $test_new->slug = $obj->slug.'_'.$str;
        $test_new->name = $obj->name.' - copy';
        $test_new->save();

        $test = $obj;
        $test->sections = $obj->sections;

        $s_array =array();
        if($obj->sections)
        foreach($obj->sections as $s){
            $s_new = $s->replicate();
            $s_new->slug = $s->slug.'_'.$str;

            $s_new->test_id = $test_new->id;
            $s_new->save();
            $s_array[$s->id] = $s_new->id;

        }

        $e_array =array();
        if($obj->extracts)
        foreach($obj->extracts as $e){
                $e_new = $e->replicate();
                $e_new->test_id = $test_new->id;
                if($e_new->section_id)
                $e_new->section_id = $s_array[$e_new->section_id];
                $e_new->save();
                $e_array[$e->id] = $e_new->id;
        }

        if($obj->mcq)
        foreach($obj->mcq as $i => $m){
            $m_new = $m->replicate();
            $m_new->test_id = $test_new->id;
            if($m_new->section_id)
                $m_new->section_id = $s_array[$m_new->section_id];
            if($m_new->extract_id)
                $m_new->extract_id = $e_array[$m_new->extract_id];
            $m_new->save();
        }

        if($obj->fillup)
        foreach($obj->fillup as $i => $m){
            $m_new = $m->replicate();
            $m_new->test_id = $test_new->id;
            if($m_new->section_id)
                $m_new->section_id = $s_array[$m_new->section_id];
            if($m_new->extract_id)
                $m_new->extract_id = $e_array[$m_new->extract_id];
            $m_new->save();
        }
        
        return redirect()->route($this->module.'.show',$test_new->id);
    }

    public function questions($id,Obj $obj,Request $request){

        $search = $request->search;
        $item = $request->item;
        $test = Obj::where('id',$id)->first();

        if($search){

            $data=array();
            $fillup = Fillup::where('test_id',$test->id)->where(function ($query) use ($item) {
                $query->where('label','LIKE',"%{$item}%")
                    ->orWhere('prefix','LIKE',"%{$item}%")
                     ->orWhere('answer','LIKE',"%{$item}%")
                    ->orWhere('suffix','LIKE',"%{$item}%")
                    ->orWhere('qno','LIKE',"%{$item}%")
                    ->orWhere('sno','LIKE',"%{$item}%");
                    })
                    ->orderBy('extract_id','asc')
                    ->orderBy('qno','asc')
                    ->get();
            foreach($fillup as $q){
                $q->fillup_id = $q->id;
                $data[$q->id] = $q;
            }

            $mcq = Mcq::where('test_id',$test->id)->where(function ($query) use ($item) {
                $query->where('question','LIKE',"%{$item}%")
                    ->orWhere('a','LIKE',"%{$item}%")
                     ->orWhere('b','LIKE',"%{$item}%")
                    ->orWhere('c','LIKE',"%{$item}%")
                    ->orWhere('qno','LIKE',"%{$item}%")
                    ->orWhere('sno','LIKE',"%{$item}%");
                    })
                    ->orderBy('extract_id','asc')
                    ->orderBy('qno','asc')
                    ->get();
            foreach($mcq as $q){
                $q->mcq_id = $q->id;
                $data[$q->id] = $q;
            } 
        }else{
            
            $data=array();
            foreach($test->fillup as $q){
                $q->fillup_id = $q->id;
                $data[$q->id] = $q;
            }

            foreach($test->mcq as $q){
                $q->mcq_id = $q->id;
                $data[$q->id] = $q;
            }
        }
        
        
        ksort($data);
        $app = $this;
        $app->test= $test;
       
        $view = $search ? 'qlist': 'questions';

        return view('appl.test.test.'.$view)
                ->with('data',$data)
                ->with('test',$test)
                ->with('app',$app);

    }


    public function analytics($id,Obj $obj,Request $request){

        $test = Obj::where('id',$id)->first();
        if(!$test){
             $test = Obj::where('slug',$id)->first();
             $id = $test->id;
        }

        $app = $this;
        $app->test= $test;

        if($test->status!=2 && $test->status!=3)
            $group = 'user_id';
        else
            $group = 'session_id';
         //dd(Carbon::now()->endOfWeek());

        if(request()->get('type')=='user'){
            $test->status =1;
            $group = 'user_id';
        }

        $data = [];
        $i=0;
        $score = [];
        $all = $request->get('all');
        $today = $request->get('today');

        $from = $request->get('from') ? Carbon::parse($request->get('from')) : $request->get('from') ;
        $to = $request->get('to') ? Carbon::parse($request->get('to')) : $request->get('to');

        if($today)
            $users = Attempt::where('test_id',$id)->whereDate('created_at', Carbon::today())->get()->groupBy($group);
        else if($from)
            $users = Attempt::where('test_id',$id)->whereBetween('created_at', [$from, $to])->get()->groupBy($group);
        else{
            if($group=='user_id')
                $users = Attempt::where('test_id',$id)->where('user_id','!=',0)->get()->groupBy($group);
            else
                $users = Attempt::where('test_id',$id)->where('user_id','=',0)->get()->groupBy($group);
        }    


        $counter =0;
        $total = 0;
        foreach($users as $i=>$attempt){
            $data[$i]['id'] = $i;
            $data[$i]['score'] =0;
            $score[$i]=0;
            foreach($attempt as $a){
                if(!isset($data[$i]['user'])){
                    if($test->status==2)
                    $data[$i]['session'] = $a->session; 
                    else if($test->status==3){
                        $data[$i]['session'] = $a->session;

                    }
                    else{
                        if($a->user)
                            $data[$i]['user'] = $a->user;
                    } 
                }
                if($a->accuracy==1){
                        $data[$i]['score']++;
                        $score[$i]++;
                        $total++;
                }
                if(in_array($a->answer,['A','B','C','D','E','F','G','H','
                    I'])){
                    if(!isset($data['qno'][$a->qno][$a->response]))
                        $data['qno'][$a->qno][$a->response] = 1;
                    else{
                        $data['qno'][$a->qno][$a->response]++;
                    }
                }elseif(strpos($a->answer, ',')!==false){
                    $bits = explode(',',$a->response);
                    foreach($bits as $b){
                        if(!isset($data['qno'][$a->qno][$b]))
                            $data['qno'][$a->qno][$b] = 1;
                        else{
                            $data['qno'][$a->qno][$b]++;
                        }
                    }
                }
            }
            $counter++;
        }

        

        arsort($score);
        $data['highest'] = 0;
        $data['lowest'] =400;
        foreach($score as $s){
            if($data['highest']<$s)
                $data['highest'] = $s;
            if($data['lowest']>$s)
                $data['lowest'] =$s;
        }
        if($counter)
        $data['avg'] = round($total/$counter,2);
        else
            $data['avg'] =0;
        $data['participants'] = count($users);

        if($test->testtype->name=='WRITING' || $test->testtype->name=='SPEAKING')
            $data['lowest'] ='-';

        if($data['lowest']==400)
            $data['lowest'] ='-';

        if($test->status==2){
            $view = 'oanalytics';

            $data['mcq'] = Attempt::where('test_id',$id)->get()->groupBy('mcq_id');
            $data['fillup'] = Attempt::where('test_id',$id)->get()->groupBy('fillup_id');
            $c=0;$ic=0;
            foreach($test->mcq_order as $t){
                $id = $t->id;
                $data['q'][$t->qno]['type']= 'mcq';

                $data['q'][$t->qno]['id'] =$id;
                $data['q'][$t->qno]['ques'] =$t;
                $c=0;$ic=0;
                

                if(isset($data['mcq'][$id]))
                foreach($data['mcq'][$id] as $q){
                    if($q->accuracy==1)
                        $c++;
                    else
                        $ic++;
                }
                $data['q'][$t->qno]['correct'] =$c;
                $data['q'][$t->qno]['incorrect'] =$ic;

            }

            foreach($test->fillup_order as $t){
                $id = $t->id;
                $data['q'][$t->qno]['type']= 'fillup';

                $data['q'][$t->qno]['id'] =$id;
                $data['q'][$t->qno]['ques'] =$t;
                $c=0;$ic=0;
                if(isset($data['fillup'][$id]))
                foreach($data['fillup'][$id] as $q){
                    if($q->accuracy==1)
                        $c++;
                    else
                        $ic++;
                }

                $data['q'][$t->qno]['correct'] =$c;
                $data['q'][$t->qno]['incorrect'] =$ic;
            }
            $total = $c + $ic;

            if($total!=0)
            foreach($data['qno'] as $k=>$qno){
                foreach(['A','B','C','D','E','F','G','H','
                    I'] as $o){
                    if(isset($qno[$o]))
                    $data['percent'][$k][$o] = round($qno[$o]/$total*100,0);
                    else
                    $data['percent'][$k][$o] =0;  
                }
            }

        }
        else
            $view = 'analytics';

        

        return view('appl.test.test.'.$view)
                ->with('obj',$test)
                ->with('users',$data)
                ->with('data',$data)
                ->with('total',$total)
                ->with('score',$score)
                ->with('try',1)
                ->with('app',$app);
    }


    public function qanalytics($id,Obj $obj,Request $request){

        $test = Obj::where('id',$id)->first();
        if(!$test){
             $test = Obj::where('slug',$id)->first();
             $id = $test->id;
        }

        $app = $this;
        $app->test= $test;

        $data = [];
        $i=0;
        $score = [];
        $all = $request->get('all');
        $today = $request->get('today');

        $from = $request->get('from') ? Carbon::parse($request->get('from')) : $request->get('from') ;
        $to = $request->get('to') ? Carbon::parse($request->get('to')) : $request->get('to');

        if($today){
            $data['mcq'] = Attempt::where('test_id',$id)->whereDate('created_at', Carbon::today())->get()->groupBy('mcq_id');
            $data['fillup'] = Attempt::where('test_id',$id)->whereDate('created_at', Carbon::today())->get()->groupBy('fillup_id');
        }
        else if($from){
           $data['mcq']  = Attempt::where('test_id',$id)->whereBetween('created_at', [$from, $to])->get()->groupBy('mcq_id');
           $data['fillup']  = Attempt::where('test_id',$id)->whereBetween('created_at', [$from, $to])->get()->groupBy('fillup_id');
        }
        else{
            $data['mcq'] = Attempt::where('test_id',$id)->get()->groupBy('mcq_id');
            $data['fillup'] = Attempt::where('test_id',$id)->get()->groupBy('fillup_id');
        } 




        $c=0;$ic=0;
        foreach($test->mcq_order as $t){
            $id = $t->id;
            $data['q'][$t->qno]['type']= 'mcq';

            $data['q'][$t->qno]['id'] =$id;
            $data['q'][$t->qno]['ques'] =$t;
            $c=0;$ic=0;
            

            if(isset($data['mcq'][$id]))
            foreach($data['mcq'][$id] as $q){
                if($q->accuracy==1)
                    $c++;
                else
                    $ic++;
            }
            $data['q'][$t->qno]['correct'] =$c;
            $data['q'][$t->qno]['incorrect'] =$ic;

        }

        foreach($test->fillup_order as $t){
            $id = $t->id;
            $data['q'][$t->qno]['type']= 'fillup';

            $data['q'][$t->qno]['id'] =$id;
            $data['q'][$t->qno]['ques'] =$t;
            $c=0;$ic=0;
            if(isset($data['fillup'][$id]))
            foreach($data['fillup'][$id] as $q){
                if($q->accuracy==1)
                    $c++;
                else
                    $ic++;
            }

            $data['q'][$t->qno]['correct'] =$c;
            $data['q'][$t->qno]['incorrect'] =$ic;
        }
        $total = $c + $ic;


        return view('appl.test.test.qanalytics')
                ->with('obj',$test)
                ->with('data',$data)
                ->with('total',$total)
                ->with('app',$app);
    }

    public function cache_delete($id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        /* delete cache */
        $filename = $this->cache_path.$this->app.'.'.$obj->slug.'.json'; 

        if(file_exists($filename)){
            unlink($filename);
            flash('cache delete!')->error();
        }
        else{
            flash('cache file not found!')->error();
        }

        Cache::delete('test_'.$id);
        Cache::delete('test_'.$obj->slug);
        

        return redirect()->route($this->module.'.show',$id);
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

        $types = Type::all();
        $categories = Category::where('status',1)->get();
        $groups = Group::where('status',1)->orderBy('id','desc')->get();
        $settings = json_decode($obj->settings);
        $client_list = Cache::get('client_list');


        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('types',$types)
                ->with('client_list',$client_list)
                ->with('categories',$categories)
                ->with('settings',$settings)
                ->with('groups',$groups)
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

            /* delete file request */
            if($request->get('deletefile')){
                if(Storage::disk('public')->exists($obj->file))
                    Storage::disk('public')->delete($obj->file);
                redirect()->route($this->module.'.show',[$id]);
            }

            /* If file is given upload and store path */
            if(isset($request->all()['file_'])){
                $file      = $request->all()['file_'];

                $path = Storage::disk('public')->putFile('extracts', $request->file('file_'));
                $request->merge(['file' => $path]);
            }

             /* delete image request */
            if($request->get('deleteimage')){
                if(Storage::disk('public')->exists($obj->image))
                    Storage::disk('public')->delete($obj->image);
                redirect()->route($this->module.'.show',[$id]);
            }

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

            $settings['noreport'] = 0;
            if($request->noreport){
                $settings['noreport'] = 1;
            }

            $settings['sidebox'] = 0;
            if($request->sidebox){
                $settings['sidebox'] = 1;
            }

            $settings['audio_permission'] = 0;
            if($request->audio_permission){
                $settings['audio_permission'] = 1;
            }

            $settings['back_re'] = 0;
            if($request->back_re){
                $settings['back_re'] = 1;
            }

            $settings['uftimer'] = 0;
            if($request->uftimer){
                $settings['uftimer'] = 1;
            }

            $settings['hide_player'] = 0;
            if($request->hide_player){
                $settings['hide_player'] = 1;
            }

            $request->merge(['settings' => json_encode($settings)]);

            /* If image is given upload and store path */
            if(isset($request->all()['image_'])){

                $file      = $request->all()['image_'];

                $filename = $request->get('slug').'.'.$file->getClientOriginalExtension();
                $path_ = Storage::disk('public')->putFileAs($this->module, $request->file('image_'),$filename);
                $request->merge(['image' => $path_]);

            }

            $sizes = [300,600,900,1200];
             if(isset($path_))
            foreach($sizes as $s)
                image_resize($path_,$s);

            if($request->get('description')){
                $user = \auth::user();
                /* upload images if any */
                $text = summernote_imageupload($user,$request->get('description'));
                
                /* merge the updated data in request */
                $request->merge(['description' => $text]);
            }

            if($request->get('details')){
                $user = \auth::user();
                /* upload images if any */
                $text = summernote_imageupload($user,$request->get('details'));
                
                /* merge the updated data in request */
                $request->merge(['details' => $text]);
            }

            Cache::forget('test_'.$obj->slug);

            if(isset($request->all()['file_'])){
            $obj = $obj->update($request->all()); 
            }else{
              $obj = $obj->update($request->except(['file']));  
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

        // remove file
        if(Storage::disk('public')->exists($obj->file))
            Storage::disk('public')->delete($obj->file);

        // remove image
        if(Storage::disk('public')->exists($obj->image)){
            Storage::disk('public')->delete($obj->image);
        }

        if($obj->mcq)
        foreach($obj->mcq as $i => $m){
            $m->delete();
        }

        if($obj->fillup)
        foreach($obj->fillup as $i => $m){
           $m->delete();
        }

        if($obj->extracts)
        foreach($obj->extracts as $e){
            $e->delete();
        }

         if($obj->sections)
        foreach($obj->sections as $s){
            $s->delete();
        }
        
        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index');
    }
}
