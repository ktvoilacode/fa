<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Test\Test;

class Order extends Model
{
    
    protected $fillable = [
        'user_id',
        'product_id',
        'test_id',
        'order_id',
        'txn_id',
        'payment_mode',
        'bank_txn_id',
        'bank_name',
        'txn_amount',
        'details',
        'expiry',
        'status',
        // add all other fields
    ];


    public function product(){
        return $this->belongsTo('App\Models\Product\Product');
    }

    public function test(){
        return $this->belongsTo('App\Models\Test\Test');
    }


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function grantaccess($product_id,$test_id,$validity){
        $user = \auth::user();
        
        $this->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);

        $o_check = $this->where('order_id',$this->order_id)->first();
        while($o_check){
            $this->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);
            $o_check = Order::where('order_id',$this->order_id)->first();
            if(!$o_check)
                break;
        }

        $this->user_id = $user->id;
        $this->txn_amount = 0;
        $this->status=1;
        $this->txn_id = '';
        $this->payment_mode = 'FREE';
        $this->product_id = $product_id;
        $this->test_id = $test_id;
        $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.($validity*31).' days'));
        $this->expiry = $valid_till;
              
        $this->save();
    }

    public function coupon($product_id=null,$test_id=null,$coupon,$user=null){
            
            if(!$user)
                $user = \auth::user();
            
            if($product_id){
              $product = Product::where('id',$product_id)->first();
              $validity = $product->validity;
            }
            else
              $product = null;

            if($test_id){
              $test = Test::where('id',$test_id)->first();
              $validity = $test->validity;
            }
            else
              $test = null;

              //dd($response);
              $order = new Order();
              $order->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);

              $o_check = Order::where('order_id',$order->order_id)->first();
              while($o_check){
                $order->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);
                $o_check = Order::where('order_id',$order->order_id)->first();
                if(!$o_check)
                  break;
              }



              $order->user_id = $user->id;
              $order->txn_amount = 0;
              $order->status=1;
              $order->txn_id = '';
              $order->payment_mode = 'COUPON';
                //update coupon
                if(isset($coupon->unlimited)){
                   if(!$coupon->unlimited){
                  $coupon->status = 0;
                    if(\auth::user())
                        $coupon->user_id = \auth::user()->id;
                    else
                        $coupon->user_id = null;
                  $coupon->save();
                 } 
                 $order->txn_id = $coupon->code;
                }else{
                    $order->txn_id = $coupon;
                }
                
                
              

              //product check
            if($product_id){
              $p_check = Order::where('user_id',$user->id)->where('product_id',$product_id)->orderBy('created_at','desc')->first();
              if($p_check){
                $today = date('d-m-Y',time()); 
                $exp = date('d-m-Y',strtotime($p_check->expiry));
                if($exp<$today)
                    $p_check = 0;
              }else{
                $p_check=0;
              }
            }else
               $p_check=0;

              $order->product_id = $product_id;

              //test check
              if($test_id){
                  $t_check = Order::where('user_id',$user->id)->where('test_id',$test_id)->orderBy('created_at','desc')->first();
                  if($t_check){
                    $today = date('d-m-Y',time()); 
                    $exp = date('d-m-Y',strtotime($t_check->expiry));
                    if($exp<$today)
                        $t_check = 0;
                  }else{
                    $t_check = 0;
                  }
              }else
                $t_check = 0;
              $order->test_id = $test_id;

              $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.($validity*31).' days'));
              $order->expiry = $valid_till;
              


                   //dd($order);
              if(!$p_check && !$t_check)
              $order->save();
              return 1;

          

    }
}
