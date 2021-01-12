<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog\Blog as Obj;
use App\Models\Blog\Label;
use App\Models\Blog\Collection;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController extends Controller
{
     public function __construct(){
        $this->app      =   'blog';
        $this->module   =   'blog';
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
        
        $filename = 'blogindex.json';
        $filepath = $this->cache_path.$filename;
        if(Storage::disk('cache')->exists('pages/'.$filename) && !$request->refresh && !$item)
        {
            $objs = json_decode(file_get_contents($filepath));
            $objs = $this->paginateAnswers($objs,config('global.no_of_records'));

            $filename = 'dates.json';
            $filepath = $this->cache_path.$filename;
            $dates = json_decode(file_get_contents($filepath));

            $filename = 'categories.json';
            $filepath = $this->cache_path.$filename;
            $categories = json_decode(file_get_contents($filepath));


        }else{
            $objs = $obj->where('title','LIKE',"%{$item}%")
                    ->orWhere('slug','LIKE',"%{$item}%")
                    ->orWhere('body','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 

            $dates = DB::select("SELECT YEAR(created_at) AS YEAR, DATE_FORMAT(created_at, '%M') AS MONTH, DATE_FORMAT(created_at, '%m') AS MON, COUNT(*) AS TOTAL FROM blogs  GROUP BY YEAR, MONTH, MON ORDER BY YEAR DESC, MONTH DESC");

            $categories = Collection::get();
        }
           
        $view = $search ? 'list': 'index';
       

        /* update in cache folder */
        if($request->refresh){
            
            $this->refreshCache();

            $objs = $obj->where('title','LIKE',"%{$item}%")
                    ->orWhere('slug','LIKE',"%{$item}%")
                    ->orWhere('body','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 
        }

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('dates',$dates)
                ->with('obj',$obj)
                ->with('categories',$categories)
                ->with('app',$this);
    }

    protected function paginateAnswers(array $answers, $perPage = 10)
    {
        $page = Input::get('page', 1);

        $offset = ($page * $perPage) - $perPage;

        $paginator = new LengthAwarePaginator(
            array_slice($answers, $offset, $perPage, true),
            count($answers),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginator;
    }


    public function refreshCache(){

            // each blog article update cache
            $objs = Obj::orderBy('created_at','desc')->get();
            foreach($objs as $obj){ 
                $filename = $obj->slug.'.json';
                $filepath = $this->cache_path.$filename;
                $obj->tags = $obj->tags;
                $obj->categories = $obj->categories;
                $obj->related = $obj->related();
                file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
            }

            //dates
            $filename = 'dates.json';
            $filepath = $this->cache_path.$filename;
            $dates = DB::select("SELECT YEAR(created_at) AS YEAR, DATE_FORMAT(created_at, '%M') AS MONTH, DATE_FORMAT(created_at, '%m') AS MON, COUNT(*) AS TOTAL FROM blogs  GROUP BY YEAR, MONTH, MON ORDER BY YEAR DESC, MONTH DESC");
            file_put_contents($filepath, json_encode($dates,JSON_PRETTY_PRINT));

            // index
            $filename = 'blogindex.json';
            $filepath = $this->cache_path.$filename;
            $objs = Obj::orderBy('created_at','desc')
                    ->get(); 
            foreach($objs as $obj){
                $obj->tags = $obj->tags;
                $obj->categories = $obj->categories;
            }
            file_put_contents($filepath, json_encode($objs,JSON_PRETTY_PRINT));

            

            //categories
            $filename = 'categories.json';
            $filepath = $this->cache_path.$filename;
            $categories = Collection::get();
            file_put_contents($filepath, json_encode($categories,JSON_PRETTY_PRINT));

            //years
            $years = DB::select("SELECT YEAR(created_at) AS YEAR,  COUNT(*) AS TOTAL FROM blogs  GROUP BY YEAR ORDER BY YEAR DESC");
            foreach($years as $year){
                $items = Obj::whereYear('created_at',$year->YEAR)->get();
                foreach($items as $obj){
                    $obj->tags = $obj->tags;
                    $obj->categories = $obj->categories;
                }
                $filename = $year->YEAR.'.json';
                $filepath = $this->cache_path.$filename;
                file_put_contents($filepath, json_encode($items,JSON_PRETTY_PRINT));
            }
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
        $employees = User::whereIn('admin',[1,2])->get();
        $labels = Label::get();
        $collections = Collection::get();

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('datetimepicker',true)
                ->with('labels',$labels)
                ->with('collections',$collections)
                ->with('app',$this);
    }


    public function tooltip(Request $r){
        $filename = $this->cache_path.'tooltip.json';
        $code ='';
        $obj = new Obj();
        $this->authorize('create', $obj);
        if($r->code){
            $code = $r->code;
            file_put_contents($filename,$code);
            flash('Successfully updated the file!')->success();
        }else{
            if(file_exists($filename))
            $code = file_get_contents($filename);
        }
        return view('appl.blog.blog.tooltip')
            ->with('code',$code)
            ->with('filename',$filename)->with('obj',$obj)->with('app',$this);
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
            $arr["error"] = 0;
            

            if (!$request->get('title')) {
                $arr["error"] =1;
                $arr["message"] = "Title cannot be empty";
            }

            if (!$request->get('meta_title')) {
                $arr["error"] =1;
                $arr["message"] = "Meta Title cannot be empty";
            }

            if (!$request->get('meta_description')) {
                $arr["error"] =1;
                $arr["message"] = "Meta description cannot be empty";
            }


            if(!$request->get('slug')){
                $request->merge(['slug' => strtolower(str_replace(' ','-',$request->get('title')))]);
            }

            $blog_exists = $obj->where('slug',$request->get('slug'))->first();
            if ($blog_exists) {
                $arr["error"] =1;
                $arr["message"] = 'Blog('.$blog_exists->slug.') slug already exists in database. ';
            }

            if(!$request->get('created_at')){
                $request->merge(['created_at' => date('Y-m-d H:i:s')]);
            }

            if($request->get('body')){
                $request->merge(['body' => summernote_imageupload($user,$request->get('body'))]);
            }

            if($request->get('conclusion')){
                $request->merge(['conclusion' => summernote_imageupload($user,$request->get('conclusion'))]);
            }

            

            if(!$request->get('meta_title')){
                $request->merge(['meta_title' => $request->get('title')]);
            }

            /* If image is given upload and store path */
            if(isset($request->all()['file'])){
                $file      = $request->all()['file'];
                $path = Storage::disk('public')->putFile('images', $request->file('file'));
                $request->merge(['image' => $path]);
            }else{
                $request->merge(['image' => ' ']);
            }
            

            if($arr["error"]){
                    flash($arr['message'])->success();
                     return redirect()->back()->withInput();;
            }
            

            $categories = $request->get('category');
            $tags = $request->get('tag');

             /* create a new entry */
            $id = $obj->create($request->all())->id;


            $obj = Obj::where('id',$id)->first();
            // create tags
            if($tags)
            foreach($tags as $tag){
                if(!$obj->labels->contains($tag))
                    $obj->labels()->attach($tag);
            }

            //create categories
            if($categories)
            foreach($categories as $category){
                if(!$obj->categories->contains($category))
                    $obj->categories()->attach($category);
            }

            //update cachec
                $filename = $obj->slug.'.json';
                $filepath = $this->cache_path.$filename;
                $obj->tags = $obj->tags;
                $obj->categories = $obj->categories;
                $obj->related = $obj->related();
                file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
        
            //all cache update
            $objs = Obj::orderBy('created_at','desc')->get();
            foreach($objs as $obj){ 
                $filename = $obj->slug.'.json';
                $filepath = $this->cache_path.$filename;
                $obj->tags = $obj->tags;
                $obj->categories = $obj->categories;
                $obj->related = $obj->related();
                file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
            }

            //dates
            $dates = DB::select("SELECT YEAR(created_at) AS YEAR, DATE_FORMAT(created_at, '%M') AS MONTH, DATE_FORMAT(created_at, '%m') AS MON, COUNT(*) AS TOTAL FROM blogs  GROUP BY YEAR, MONTH, MON ORDER BY YEAR DESC, MONTH DESC");
            $filename = 'dates.json';
            $filepath = $this->cache_path.$filename;
            file_put_contents($filepath, json_encode($dates,JSON_PRETTY_PRINT));
        

            
            flash('A new blog ('.$request->get('slug').') item is created!')->success();
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
    public function show($slug)
    {
        $filename = $slug.'.json';
        $filepath = $this->cache_path.$filename;


        if(Storage::disk('cache')->exists('pages/'.$filename))
        {
            $obj = json_decode(file_get_contents($filepath));
            $filename = 'dates.json';
            $filepath = $this->cache_path.$filename;
            $dates = json_decode(file_get_contents($filepath));

            $filename = 'categories.json';
            $filepath = $this->cache_path.$filename;
            $categories = json_decode(file_get_contents($filepath));

        }else{

            $obj = Obj::where('slug',$slug)->first();

            $dates = DB::select("SELECT YEAR(created_at) AS YEAR, DATE_FORMAT(created_at, '%M') AS MONTH, DATE_FORMAT(created_at, '%m') AS MON, COUNT(*) AS TOTAL FROM blogs  GROUP BY YEAR, MONTH, MON ORDER BY YEAR DESC, MONTH DESC");

            $categories = Collection::get();
        }

        if($obj->test){
            $filename = $this->cache_path_test.'test.'.$obj->test.'.json'; 
            if(file_exists($filename)){
              $this->test = json_decode(file_get_contents($filename));
            }
            else{
              $this->test = Test::where('slug',$obj->test)->first();
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
            }
        }


        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('categories',$categories)->with('app',$this)->with('dates',$dates)->with('answers',0);
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
        $employees = User::whereIn('admin',[1,2])->get();

        $labels = Label::get();
        $collections = Collection::get();

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('datetimepicker',true)
                ->with('labels',$labels)
                ->with('collections',$collections)
                ->with('employees',$employees)
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
            $user = \auth::user();
            $obj = Obj::where('id',$id)->first();
            $this->authorize('update', $obj);

            /* delete file request */
            if($request->get('deletefile')){
                if(Storage::disk('public')->exists($obj->image)){
                    Storage::disk('public')->delete($obj->image);
                }
                redirect()->route($this->module.'.show',[$obj->slug]);
            }

            /* If file is given upload and store path */
            if(isset($request->all()['file'])){
                $file      = $request->all()['file'];
                $path = Storage::disk('public')->putFile('images', $request->file('file'));
                $request->merge(['image' => $path]);
            }

            if($request->get('body')){
                $request->merge(['body' => summernote_imageupload($user,$request->get('body'))]);
            }

            if($request->get('conclusion')){
                $request->merge(['conclusion' => summernote_imageupload($user,$request->get('conclusion'))]);
            }


            $obj->update($request->all()); 

            $categories = $request->get('category');
            $tags = $request->get('tag');

            $category_list =  Collection::orderBy('created_at','desc')
                        ->get()->pluck('id')->toArray();
            //update tags
            if($categories){
                foreach($category_list as $category){
                    if(in_array($category, $categories)){
                        if(!$obj->categories->contains($category))
                            $obj->categories()->attach($category);
                    }else{
                        if($obj->categories->contains($category))
                            $obj->categories()->detach($category);
                    }
                } 
            }else{
                $obj->categories()->detach();
            } 

            $tag_list =  Label::orderBy('created_at','desc')
                        ->get()->pluck('id')->toArray();
            //update tags
            if($tags){
                foreach($tag_list as $tag){
                if(in_array($tag, $tags)){
                    if(!$obj->labels->contains($tag))
                        $obj->labels()->attach($tag);
                }else{
                    if($obj->labels->contains($tag))
                        $obj->labels()->detach($tag);
                }
                
                }
            }else{
                $obj->labels()->detach();
            } 

            //autotooltip
            $filename = $this->cache_path.'tooltip.json';
            $tooltip = json_decode(file_get_contents($filename));
            //$searchVal =[];
            //$replaceVal = [];
            if($tooltip)
            foreach($tooltip as $item=>$value){
                $searchVal = $item;
                $replaceVal = '<a href="#" data-toggle="tooltip" title="'.$value.'">'.$item.'</a>';
                //array_push($searchVal, $item);
                //array_push($replaceVal, $value);
                
                
                $e = $obj->body;
                $obj->body = str_replace($searchVal, $replaceVal, $e); 
                $obj->conclusion= str_replace($searchVal, $replaceVal, $obj->conclusion);
            }

            //update cache
            $filename = $obj->slug.'.json';
                $filepath = $this->cache_path.$filename;
                $obj->tags = $obj->tags;
                $obj->categories = $obj->categories;
                 $obj->related = $obj->related();
                file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
        
            //dates
            $dates = DB::select("SELECT YEAR(created_at) AS YEAR, DATE_FORMAT(created_at, '%M') AS MONTH, DATE_FORMAT(created_at, '%m') AS MON, COUNT(*) AS TOTAL FROM blogs  GROUP BY YEAR, MONTH, MON ORDER BY YEAR DESC, MONTH DESC");
            $filename = 'dates.json';
            $filepath = $this->cache_path.$filename;
            file_put_contents($filepath, json_encode($dates,JSON_PRETTY_PRINT));


            flash('('.$obj->slug.') blog item is updated!')->success();
            return redirect()->route('page.view',$obj->slug);
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
        $obj->labels()->detach();
        $obj->collections()->detach();

        $obj->delete();

        flash('('.$obj->slug.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index');
    }
}
