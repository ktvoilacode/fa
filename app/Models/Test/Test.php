<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Test\Attempt;
use App\Models\Admin\Session;
use Illuminate\Support\Facades\Cache;

class Test extends Model
{
    use Sortable;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'instructions',
        'file',
        'marks',
        'test_time',
        'status',
        'group_id',
        'price',
        'details',
        'image',
        'type_id',
        'validity',
        'level',
        'settings',
        'client_slug'
        // add all other fields
    ];

    public function testtype()
    {
        return $this->belongsTo('App\Models\Test\Type','type_id');
    }

    

    public function category()
    {
        return $this->belongsTo('App\Models\Test\Category');
    }

    public function sections()
    {
        return $this->hasMany('App\Models\Test\Section');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Test\Group');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product\Product');
    }

    public function fillup()
    {
        return $this->hasMany('App\Models\Test\Fillup');
    }

    public function extracts()
    {
        return $this->hasMany('App\Models\Test\Extract');
    }

    public function mcq()
    {
        
        return $this->hasMany('App\Models\Test\Mcq');
    }

    public function order($user=null)
    {

        if(!$user)
            return null;

        $orders = $user->orders()->whereIn('product_id',$this->products->pluck('id')->toArray())->orderBy('id','desc')->get();

        if($orders)
        foreach($orders as $order){
            if($order->status){
             
                return $order;
            }
        }
        $order = $user->orders()->where('test_id',$this->id)->orderBy('id','desc')->first();

        if($order){
            return $order;
        }

        return null;
    }

    public function fillup_order($data=null) {
        if($data){
            $data = Cache::remember('t_fill_order'.$this->id,360,function(){
            return $this->fillup()->orderBy('sno','asc')->get();
            });
        return $data;
        }else{
            return $this->fillup()->orderBy('sno','asc');
        }
        
    }

    public function mcq_order($data=null) {
        if($data){
            $data = Cache::remember('t_mcq_order'.$this->id,360,function(){
            return $this->mcq()->orderBy('sno','asc')->get();
            });
        return $data;
        }else{
            return $this->mcq()->orderBy('sno','asc');
        }
        
    }

    public function fillup_q1() {
        return $this->fillup()->where('qno',1)->first();
    }

    public function mcq_q1() {
        return $this->mcq()->where('qno',1)->first();
    }

    public function quescount(){
        return $this->fillup()->count() + $this->mcq()->count(); 
    }

    public function attemptcount(){
        $attempt = new Attempt();
        $att = $attempt->where('test_id',$this->id)->get();
        $a = $att->groupBy('session_id');

        $b = $att->where('session_id',null)->groupBy('user_id');
       
        $count = count($a) + count($b);
        return $count; 
    }

    public function sessionAttempt(){
        $session_id = request()->session()->getID();
        $attempt = Attempt::where('test_id',$this->id)->where('session_id',$session_id)->get();
        return $attempt; 
    }

    public function sessionData(){
        $session_id = request()->session()->getID();
        $u = Session::where('id',$session_id)->first();
        return $u; 
    }

      public static function duolingoRange($score){

        if($score>=0 && $score<=60)
            return '10 - 55';
        else if($score>61 && $score<=90)
            return '60 - 85';
        else if($score>91 && $score<=120)
            return '90 - 115';
        else 
            return '120 - 160';
    }

    public function duolingoComment($score){

        if($score>0 && $score<=60)
            $comment = "<ul><li>Can understand very basic English words and phrases.</li>
                        <li>Can understand straightforward information and express themselves in familiar contexts.</li></ul>";
        else if($score>61 && $score<=90)
            $comment = "<ul><li>Can understand the main points of concrete speech or writing on routine matters such as work and school.</li>
                        <li>Can describe experiences, ambitions, opinions, and plans, although with some awkwardness or hesitation.</li></ul>";
        else if($score>91 && $score<=120)
            $comment = "<ul><li>Can fulfill most communication goals, even on unfamiliar topics.</li>
                        <li>Can understand the main ideas of both concrete and abstract writing.</li>
                        <li>Can interact with proficient speakers fairly easily.</li></ul>";
        else 
            $comment = "<ul><li>Can understand a variety of demanding written and spoken language including some specialized language use situations.</li>
                        <li>Can grasp implicit, figurative, pragmatic, and idiomatic language.</li>
                        <li>Can use language flexibly and effectively for most social, academic, and professional purposes.</li></ul>";

        return $comment;
    }


    
}
