<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use App\Models\Test\Test;
use App\Models\Test\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Attempt extends Model
{
    protected $fillable = [
        'test_id',
        'user_id',
        'mcq_id',
        'fillup_id',
        'qno',
        'response',
        'answer',
        'score',
        'accuracy',
        'session_id',
        'dynamic',
        'comment',
        'status',
        'marking',
        // add all other fields
    ];


    public function test()
    {
        return $this->belongsTo('App\Models\Test\Test');
    }

    public function getTest($id)
    {   
        $test = Test::where('id',$id)->first();
        return $test;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\Admin\Session');
    }

    public function writing()
    {
        return $this->hasOne('App\Models\Test\Writing');
    }

    public function fillup()
    {
        return $this->belongsTo('App\Models\Test\Fillup');
    }
    
    public function mcq()
    {
        return $this->belongsTo('App\Models\Test\Mcq');
    }

    public static function  tags($result){
        $mcq_id = [];
        $fillup_id =[];
        if(is_array($result)){
            foreach($result as $res){
                if($res['fillup_id']){
                    array_push($fillup_id, $res['fillup_id']);
                }
                elseif($res['mcq_id']){
                    array_push($mcq_id, $res['mcq_id']);
                }
            }
        }else{
            $mcq_id = $result->pluck('mcq_id')->toArray();
            $fillup_id = $result->pluck('fillup_id')->toArray();
        }
        
        $mcq_tags = DB::table('mcq_tag')->whereIn('mcq_id',$mcq_id)->get();
        $mcq_tag_ids = $mcq_tags->pluck('tag_id')->toArray();

        
        $fillup_tags = DB::table('fillup_tag')->whereIn('fillup_id',$fillup_id)->get();
        $fillup_tag_ids =$fillup_tags->pluck('tag_id')->toArray();
        

        $tags = Tag::whereIn('id',$mcq_tag_ids)->orWhereIn('id',$fillup_tag_ids)->get();

        if(!$tags)
            return null;

        $tags = $tags->groupBy('name');

        $data = [];
        $tagdata = [];
        foreach($tags as $name => $t){
            foreach($t as $a){

              $data[$name][$a->value]['correct'] = 0; 
              $data[$name][$a->value]['total'] = 0;
              $data[$name][$a->value]['percent'] = 0;
            }
            
        }

        //dd($result);
        foreach($result as $at){


            $q=0;
            if(is_array($result)){
                if($at['mcq_id'])
                $q = Mcq::where('id',$at['mcq_id'])->first();
                else if($at['fillup_id']) 
                $q = Fillup::where('id',$at['fillup_id'])->first();

            }else{
                if($at->mcq_id)
                $q = $at->mcq;
                else if($at->fillup_id) 
                $q = $at->fillup;
            }
            
            

            // if($q){
            //     if(isset($q->tags))
            //     foreach($q->tags as $tg){
            //         if(isset($at->accuracy)){
            //             if($at->accuracy==1){
            //                 $data[$tg->name][$tg->value]['correct']++;
            //             } 
            //         }else{
            //             if($at['accuracy']==1)
            //                  $data[$tg->name][$tg->value]['correct']++;
            //         }
                    
            //         $data[$tg->name][$tg->value]['total']++;
                    
            //     }
            // }
        }

        foreach($tags as $name => $t){
            foreach($t as $a){
              if($data[$name][$a->value]['total'])
              $data[$name][$a->value]['percent'] = (round($data[$name][$a->value]['correct']/$data[$name][$a->value]['total'],2))*100;
            }
            
        }

        return $data;
    }

    public function reading_band($score){
        if($score==39 || $score ==40)
            $band = 9;
        else if($score==37 || $score ==38)
            $band = 8.5;
        else if($score==35 || $score ==36)
            $band = 8;
        else if($score>=33 && $score <=34)
            $band = 7.5;
        else if($score>=30 && $score <=32)
            $band = 7;
        else if($score>=27 && $score <=29)
            $band = 6.5;
        else if($score>=23 && $score <=26)
            $band = 6;
        else if($score>=19 && $score <=22)
            $band = 5.5;
        else if($score>=15 && $score <=18)
            $band = 5;
        else if($score>=13 && $score <=14)
            $band = 4.5;
        else if($score>=10 && $score <=12)
            $band = 4;
        else if($score>=8 && $score <=9)
            $band = 3.5;
        else if($score>=6 && $score <=7)
            $band = 3;
        else if($score>=4 && $score <=5)
            $band = 2.5;
        else if($score>=2 && $score <=3)
            $band = 2;
        else 
            $band =0;
        return $band;

    }

    public function listening_band($score){

        if($score==39 || $score ==40)
            $band = 9;
        else if($score==37 || $score ==38)
            $band = 8.5;
        else if($score==35 || $score ==36)
            $band = 8;
        else if($score>=32 && $score <=34)
            $band = 7.5;
        else if($score>=30 && $score <=31)
            $band = 7;
        else if($score>=26 && $score <=29)
            $band = 6.5;
        else if($score>=23 && $score <=25)
            $band = 6;
        else if($score>=18 && $score <=22)
            $band = 5.5;
        else if($score>=16 && $score <=17)
            $band = 5;
        else if($score>=13 && $score <=15)
            $band = 4.5;
        else if($score>=11 && $score <=12)
            $band = 4;
        else if($score>=8 && $score <=10)
            $band = 3.5;
        else if($score>=5 && $score <=7)
            $band = 3;
        else if($score==3 && $score ==4)
            $band = 2.5;
        else if($score==2 )
            $band = 2;
        else if($score==1)
            $band = 1;
        else
            $band =0;
        return $band;

    }


    public function evaluate($request,$result){ 

        $score_params = ['pronunciation'=>0,'fluency'=>0,'understanding-and-completeness'=>0,'leximic-dextirity'=>0,'grammatical-proficiency'=>0];

        $json = [];

        $co=1;
        foreach($request->all() as $key =>$value){
            
          if(startsWithNumber($key))
          {
                $exp = explode('_', $key);
                $qno = $exp[0];
               
                $param = $exp[1];
                $json[$qno][$param]=$value;

               
          }


        }


         // if(count($json))
         // dd($result[0]);
        $counter=0;
        foreach($json as $qno =>$data){
    

            $r = $result->where('qno',$qno)->first();
            $total = 0; $count = 0;
            foreach($data as $param=>$sc){
                $total = $total + intval($sc);
                $count++;
            }
            if($count)
                $r->score = intval($total);
            else
                $r->score = 0;

            if($qno==1)
                $r->comment = $request->get('comments');

            
            
            $r->marking = json_encode($data);
            $r->status = 1;
            $counter++;
            $r->save();
        }


        


    }

    public function loadMarking($result){
        foreach($result as $r){
            $data[$r->qno] = json_decode($r->marking,true);
        }
        return $data;
    }

    public function scoreDuolingo($result){
        $param_count = ['pronunciation'=>0,'fluency'=>0,'understanding-and-completeness'=>0,'leximic-dextirity'=>0,'grammatical-proficiency'=>0];
        $param_score = ['pronunciation'=>0,'fluency'=>0,'understanding-and-completeness'=>0,'leximic-dextirity'=>0,'grammatical-proficiency'=>0];
        $param_percent = ['pronunciation'=>0,'fluency'=>0,'understanding-and-completeness'=>0,'leximic-dextirity'=>0,'grammatical-proficiency'=>0];

        $review = false;


        foreach($result as $r){

            $data = json_decode($r->marking,true);

            if(!$r->marking){
                $review = true;
                
                break;
            }else{
                
            }
            foreach($param_count as $p=>$v){
                if(isset($data[$p])){
                    $param_score[$p] = $param_score[$p] +$data[$p];
                    $param_count[$p]++;
                }
            }
        }

        $total =0;
        $s = 0;
        if($review)
        foreach($result as $r){
            if($r->status==1){
                $total++;
                if($r->accuracy==1)
                $s = $s+1;
            }
        }

        $score = 0;

        foreach($param_count as $p=>$v){
               if($param_count[$p]!=0)
               $param_percent[$p] = round(($param_score[$p]/(5*$param_count[$p])) * 100,2);
           $score = $score + $param_percent[$p];

        }

        $score = round($score/5,2);

        if($review){
            if($total)
                $score = $s/$total * 160;
            else
                $score=0;
            if($score<50)
                $score = $score + 20;
            $param_percent['score'] = round($score,2);
        }
        else
            $param_percent['score'] = $score;
        return $param_percent;


    }


}
