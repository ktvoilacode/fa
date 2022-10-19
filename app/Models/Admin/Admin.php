<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Test\Test;
use App\Models\Test\Group;
use App\Models\Product\Product;
use App\Models\Product\Coupon;
use App\Models\Product\Order;
use App\User;

class Admin extends Model
{
    

    public function userAnalytics(){
    	$data = array();

    	$data['total'] = User::count();

    	$last_year = (new \Carbon\Carbon('first day of last year'))->year;
        $this_year = (new \Carbon\Carbon('first day of this year'))->year;

        $last_year_first_day = (new \Carbon\Carbon('first day of January '.$last_year))->startofMonth()->toDateTimeString();
        $this_year_first_day = (new \Carbon\Carbon('first day of January '.$this_year))->startofMonth()->toDateTimeString();

        $last_year_count  = User::where('created_at','>', $last_year_first_day)->where('created_at','<', $this_year_first_day)->count();
        $this_year_count  = User::where(DB::raw('YEAR(created_at)'), '=', $this_year)->count();

        $data['last_year'] =$last_year_count;
        $data['this_year'] = $this_year_count;


        $last_month_first_day = (new \Carbon\Carbon('first day of last month'))->startofMonth()->toDateTimeString();
        $this_month_first_day = (new \Carbon\Carbon('first day of this month'))->startofMonth()->toDateTimeString();
        
        $last_month  = User::where('created_at','>', $last_month_first_day)->where('created_at','<', $this_month_first_day)->count();
        $this_month  = User::where(DB::raw('MONTH(created_at)'), '=', date('n'))->count();

        $data['last_month'] = $last_month;
        $data['this_month'] = $this_month;

        return $data;
    }

     public function orderAnalytics(){
        $data = array();

        $data['total'] = Order::count();

        $last_year = (new \Carbon\Carbon('first day of last year'))->year;
        $this_year = (new \Carbon\Carbon('first day of this year'))->year;

        $last_year_first_day = (new \Carbon\Carbon('first day of January '.$last_year))->startofMonth()->toDateTimeString();
        $this_year_first_day = (new \Carbon\Carbon('first day of January '.$this_year))->startofMonth()->toDateTimeString();

        $last_year_count  = Order::where('created_at','>', $last_year_first_day)->where('created_at','<', $this_year_first_day)->count();
        $this_year_count  = Order::where(DB::raw('YEAR(created_at)'), '=', $this_year)->count();

        $data['last_year'] =$last_year_count;
        $data['this_year'] = $this_year_count;


        $last_month_first_day = (new \Carbon\Carbon('first day of last month'))->startofMonth()->toDateTimeString();
        $this_month_first_day = (new \Carbon\Carbon('first day of this month'))->startofMonth()->toDateTimeString();
        
        $last_month  = Order::where('created_at','>', $last_month_first_day)->where('created_at','<', $this_month_first_day)->count();
        $this_month  = Order::where(DB::raw('MONTH(created_at)'), '=', date('n'))->count();

        $data['last_month'] = $last_month;
        $data['this_month'] = $this_month;

        return $data;
    }


    public static function sendWhatsApp($phone,$template,$variables){
            $url = "https://graph.facebook.com/v13.0/102903359277453/messages";
            $token = env('wa_token');//'EAAK0BmKuQgcBAAnGj9qbUZANSZAQMmp1ocnDWpNdqDqFWe0PIuCiFcZALygZBwiDgat9N0kfDv2ohAcVByhR01bjFmStzzXaLnjK6w5yZAVChxjv0JvNmYUP1gZCDRIpfXYN3X4JUOMpxtBPzsMtK6HL20r14UQzH1pZAVQ8uZA2wAQNz1eiWoYp8ZA2HrNSMFkKFF2ZACvxuMIgZDZD';
            $curl = curl_init();


            if(count($variables)==0){
                $msg = '{
                "messaging_product": "whatsapp",
                "to": '.$phone.',
                "type": "template",
                "template": {
                    "name": "'.$template.'",
                    "language": {
                        "code": "en_US"
                    }
                }
            }';
            }elseif(count($variables)==1){
                 $msg = '{
                    "messaging_product": "whatsapp",
                    "to": '.$phone.',
                    "type": "template",
                    "template": {
                        "name": "'.$template.'",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "'.$variables[0].'"
                              }
                            ]
                          }
                        ]
                    }
                }';
            }else if(count($variables)==2){
                $msg = '{
                    "messaging_product": "whatsapp",
                    "to": '.$phone.',
                    "type": "template",
                    "template": {
                        "name": "'.$template.'",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "'.$variables[0].'"
                              },
                              {
                                "type": "text",
                                "text": "'.$variables[1].'"
                              }
                            ]
                          }
                        ]
                    }
                }';
            }else if(count($variables)==3){
                $msg = '{
                    "messaging_product": "whatsapp",
                    "to": '.$phone.',
                    "type": "template",
                    "template": {
                        "name": "'.$template.'",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "'.$variables[0].'"
                              },
                              {
                                "type": "text",
                                "text": "'.$variables[1].'"
                              },
                              {
                                "type": "text",
                                "text": "'.$variables[2].'"
                              }
                            ]
                          }
                        ]
                    }
                }';
            }else if(count($variables)==4){
                $msg = '{
                    "messaging_product": "whatsapp",
                    "to": '.$phone.',
                    "type": "template",
                    "template": {
                        "name": "'.$template.'",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "'.$variables[0].'"
                              },
                              {
                                "type": "text",
                                "text": "'.$variables[1].'"
                              },
                              {
                                "type": "text",
                                "text": "'.$variables[2].'"
                              },
                              {
                                "type": "text",
                                "text": "'.$variables[3].'"
                              }
                            ]
                          }
                        ]
                    }
                }';
            }
           
           //dd($msg);

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$msg,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$token
              ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            //dd($response);
            return 1;

    }


    public function groupCount(){
    	return Group::count();
    }
    public function testCount(){
    	return Test::count();
    }
    public function productCount(){
    	return Product::count();
    }
    public function couponCount(){
    	return count(Coupon::all());
    }
}
