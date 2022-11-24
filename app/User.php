<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Models\Test\Test;
use App\Models\Product\Product;
use App\Models\Product\Order;
use App\Models\Product\Coupon;
use App\Models\Test\Attempt;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable 
{
 
    use Sortable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','lastlogin_at','status',
        'activation_token','sms_token','user_id','idno','admin','auto_password','enrolled','comment','info','client_slug','data'
    ];

     public $sortable = ['idno',
                        'name',
                        'email',
                        'phone',
                        'created_at',
                        'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getUser($id){
        return $this->where('id',$id)->first();
    }

    public function getUserByEmail($email){
        return $this->where('email',$email)->first();
    }

    public function isAdmin(){
        if(in_array($this->admin,[1,2,3,4,5]))
            return true;
        else
            return false;
    }

    public function isSuperAdmin(){
        if(in_array($this->admin,[1]))
            return true;
        else
            return false;
    }

    public static function duolingoRange($score){

        return $score;
        if($score>=0 && $score<=60)
            return '10 - 55';
        else if($score>61 && $score<=90)
            return '60 - 85';
        else if($score>91 && $score<=120)
            return '90 - 115';
        else 
            return '120 - 160';
    }


    public function referral($id)
    {
        $user = $this->find($id);
        return $user;
    }

    public function orders(){
        return $this->hasMany('App\Models\Product\Order');
    }

    public function tracks(){
        return $this->belongsToMany('App\Models\Course\Track');
    }

    public function sessions(){
        return $this->belongsToMany('App\Models\Course\Sess');
    }

    public function attempts(){
        return $this->hasMany('App\Models\Test\Attempt');

    }

    public function check_Order($user_id,$test_id){
        return Order::where('user_id',$user_id)->where('test_id',$test_id)->first();
    }

    public function create_order($user_id,$referral_name,$product_id,$test_id,$validity){
        $order = new Order();
        $order->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);

        $o_check = Order::where('order_id',$order->order_id)->first();
        while($o_check){
            $order->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);
            $o_check = Order::where('order_id',$order->order_id)->first();
            if(!$o_check)
              break;
        }

        $order->user_id = $user_id;
        $order->txn_amount = 0;
        $order->status=1;
        $order->txn_id = '';
        $order->payment_mode = 'REFERRAL';
        $order->txn_id = $referral_name;

        $order->product_id = $product_id;
        $order->test_id = $test_id;

        $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.($validity*31).' days'));
        $order->expiry = $valid_till;
        $order->save();
    }

    public function hasTest($test_id){
        $order = $this->orders()->where('test_id',$test_id)->orderBy('id','desc')->first();
        return $order;
    }

    public function hasProduct($product_id){
        $order = $this->orders()->where('product_id',$product_id)->orderBy('id','desc')->first();
        return $order;
    }

    public function tests(){
        $test_id = DB::table('attempts')
                ->select('test_id')
                ->where('user_id', $this->id)
                ->orderBy('id','desc')
                ->pluck('test_id')->toArray();
                 
                $ids = array_unique($test_id);
                 
        $tests = Test::whereIn('id',$test_id)->get();
        return $tests;
    }

     public function testCount(){
        $test_id = DB::table('attempts')
                ->select('test_id')
                ->where('user_id', $this->id)
                ->orderBy('id','desc')
                ->pluck('test_id')->toArray();
        $ids = array_unique($test_id);
        return count($ids);
    }

    public function testAccess($id,$slug=null){
        $order = $this->orders()->where('test_id',$id)->orderBy('id','desc')->first();
        
        $attempt = Attempt::where('test_id',$id)->where('user_id',$this->id)->first();


        if($attempt)
            return true;

        $current_test= session()->get('current_test');
        
        if($current_test)
        if($slug == $current_test){
           
            return false;
        }

        if($order){
            if(strtotime($order->expiry) < strtotime(date('Y-m-d'))){
                $test = Test::where('id',$id)->first();
                $products = $test->products->pluck('id')->toArray();
                $orders = $this->orders()->whereIn('product_id',$products)->orderBy('id','desc')->get();
                foreach($orders as $o){
                    if(strtotime($o->expiry) > strtotime(date('Y-m-d')))
                        return true;
                }
            }else{
                return true;
            }
        }else{

            $test = Test::where('id',$id)->first();
            if($test){
                $products = $test->products->pluck('id')->toArray();
                $orders = $this->orders()->whereIn('product_id',$products)->orderBy('id','desc')->get();
                foreach($orders as $o){
                    if(strtotime($o->expiry) > strtotime(date('Y-m-d')))
                        return true;
                }

            }
                
        }
        $order2 = $this->orders()->where('product_id',$id)->orderBy('id','desc')->first();
        if($order2){
            if(strtotime($order2->expiry) > strtotime(date('Y-m-d')))
                return true;
            else
                return false;
        }
        return false;
    }

    public function attempt($id){

        if(request()->get('refresh'))
        {
            Cache::forget('mytests_'.$this->id);
        }

        $allattempts = Cache::get('mytests_'.$this->id);


        if(!$allattempts){
            $allattempts = Attempt::where('user_id',$this->id)->get()->groupBy('test_id');
            Cache::put('mytests_'.$this->id,$allattempts,60);
        }
        
        $keys = array_keys($allattempts->toArray());

        if(in_array($id,$keys)){
            return true;
        }
        return false;

        // $attempt = Attempt::where('test_id',$id)->where('user_id',$this->id)->first();
        // if($attempt){
        //     return true;
        // }else
        //     return false;
        
    }

    public function testscore($user_id,$test_id){
        $attempt = Attempt::where('test_id',$test_id)->where('user_id',$user_id)->get();



        $score = 0;
        $total = 0;
        foreach($attempt as $r){
                if($r->accuracy==1)
                  $score++;
              $total++;
        }
        if(count($attempt)){
            //echo $attempt;
            return 'Score - '.$score.' / '.$total;
        }
        else
            return null;
    }

    public function get_testscore($user_id,$test_id){
        //$attempt = Attempt::where('test_id',$test_id)->where('user_id',$user_id)->get();

        $attempts = Cache::remember('attempted_'.$this->id, 240, function() use ($user_id){
            return Attempt::where('user_id',$user_id)->get();
        });

        $score = 0;
        $attempted =  false;
        $total = 0;
        foreach($attempts as $r){
            if($r->test_id == $test_id){
                if($r->accuracy==1)
                  $score++;
              $total++;
              $attempted = true;
            }
                
        }
        if($attempted){
            //echo $attempt;
            return 'Score - '.$score.' / '.$total;
        }
        else
            return null;
    }

    public function attempted($user_id,$test_id){
        $attempt = Attempt::where('test_id',$test_id)->where('user_id',$user_id)->first();

        if($attempt)
            return true;
        else
            return false;
    }

    public function has_attempted($test_id){
        $user_id = $this->id;
        $attempts = Cache::remember('attempted_'.$this->id, 240, function() use ($user_id){
            return Attempt::where('user_id',$user_id)->get();
        });

        foreach($attempts as $a){
            if($a->test_id == $test_id)
                return true;
        }

        return false;
    }


     public function resend_sms($numbers,$code){
        if(strlen($numbers)==10)
            $numbers = "+91".$numbers;

        $url = "https://2factor.in/API/V1/b2122bd6-9856-11ea-9fa5-0200cd936042/SMS/".$numbers."/".$code;
        $d = $this->curl_get_contents($url);
     
        
    }

    function curl_get_contents($url)
    {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }

    public function resend_sms2($numbers,$code){
                // Authorisation details.
        $username = "info@firstacademy.in";
        $hash = "5f40765f506a1348748d2adc498e88275b5a046763fd8b948ee289970e1cc938";


        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "0";

        // Data for text message. This is the text message data.
        $sender = "FIIRST"; // This is who the message appears to be from.
        
        $message = "Thank you for registering with First Academy. Your verification code is ".$code;
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);
    }

    public function coupon($coupon){
        $coupon = Coupon::where('code',$coupon)->where('client_slug',client('slug'))->first();
         
         if($coupon){
             if($coupon->status==0 && !request()->get('email')){
                abort('403','Coupon code expired');
            }

            $order = new Order();
            foreach($coupon->products as $p){
                $order->coupon($p->id,null,$coupon,$this);
            }

            foreach($coupon->tests as $t){
                $order->coupon(null,$t->id,$coupon,$this);
            }
         }
       
    }


}
