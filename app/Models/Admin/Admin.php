<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Test\Test;
use App\Models\Test\Group;
use App\Models\Product\Product;
use App\Models\Product\Coupon;
use App\Models\Product\Order;
use App\User;

class Admin extends Model
{


  public function userAnalytics()
  {
    // OPTIMIZED: Cache analytics data for 1 hour
    return Cache::remember('admin_user_analytics', 3600, function () {
      // OPTIMIZED: Single query to get all user statistics
      $user_stats = User::selectRaw('
        COUNT(*) as total,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW() - INTERVAL 1 YEAR) THEN 1 END) as last_year,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW()) THEN 1 END) as this_year,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW() - INTERVAL 1 MONTH) THEN 1 END) as last_month,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW()) THEN 1 END) as this_month
      ')->first();

      return [
        'total' => $user_stats->total,
        'last_year' => $user_stats->last_year,
        'this_year' => $user_stats->this_year,
        'last_month' => $user_stats->last_month,
        'this_month' => $user_stats->this_month,
      ];
    });
  }

  public function orderAnalytics()
  {
    // OPTIMIZED: Cache analytics data for 1 hour  
    return Cache::remember('admin_order_analytics', 3600, function () {
      // OPTIMIZED: Single query to get all order statistics
      $order_stats = Order::selectRaw('
        COUNT(*) as total,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW() - INTERVAL 1 YEAR) THEN 1 END) as last_year,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW()) THEN 1 END) as this_year,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW() - INTERVAL 1 MONTH) THEN 1 END) as last_month,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW()) THEN 1 END) as this_month
      ')->first();

      return [
        'total' => $order_stats->total,
        'last_year' => $order_stats->last_year,
        'this_year' => $order_stats->this_year,
        'last_month' => $order_stats->last_month,
        'this_month' => $order_stats->this_month,
      ];
    });
  }

  public static function whatsappWriting($phone, $name, $testname)
  {
    $curl = curl_init();

    // $phone = '919515125110';
    // $name = 'Ramesh';
    // $testname  = 'ABCD';

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.gupshup.io/wa/api/v1/template/msg',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'channel=whatsapp&source=919885817700&destination=' . $phone . '&src.name=tITI15zxQDfbob88gw58EkAS&template=%7B%22id%22%3A%22a9e7a658-018c-4e5d-ad90-77a0fbc803ec%22%2C%22params%22%3A%5B%22' . $name . '%22%2C%22' . $testname . '%22%5D%7D',
      CURLOPT_HTTPHEADER => array(
        'Cache-Control: no-cache',
        'Content-Type: application/x-www-form-urlencoded',
        'apikey: akhyhj14tf3w1aaspgvcqyn4xhio9g2l',
        'cache-control: no-cache'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    return 1;
  }

  public static function whatsappUserDetails($phone, $email, $pass)
  {
    $curl = curl_init();

    // $phone = '919515125110';
    // $email = 'packetcode@gmail.com';
    // $pass  = 'ABCD';

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.gupshup.io/wa/api/v1/template/msg',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'channel=whatsapp&source=919885817700&destination=' . $phone . '&src.name=tITI15zxQDfbob88gw58EkAS&template=%7B%22id%22%3A%22b6d6438d-2b18-411f-80c6-7f76ceb9a613%22%2C%22params%22%3A%5B%22' . $email . '%22%2C%22' . $pass . '%22%5D%7D',
      CURLOPT_HTTPHEADER => array(
        'Cache-Control: no-cache',
        'Content-Type: application/x-www-form-urlencoded',
        'apikey: akhyhj14tf3w1aaspgvcqyn4xhio9g2l',
        'cache-control: no-cache'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    return 1;
  }

  public static function sendWhatsApp($phone, $template, $variables)
  {
    $url = "https://graph.facebook.com/v13.0/102903359277453/messages";
    $token = env('wa_token'); //'EAAK0BmKuQgcBAAnGj9qbUZANSZAQMmp1ocnDWpNdqDqFWe0PIuCiFcZALygZBwiDgat9N0kfDv2ohAcVByhR01bjFmStzzXaLnjK6w5yZAVChxjv0JvNmYUP1gZCDRIpfXYN3X4JUOMpxtBPzsMtK6HL20r14UQzH1pZAVQ8uZA2wAQNz1eiWoYp8ZA2HrNSMFkKFF2ZACvxuMIgZDZD';
    $curl = curl_init();


    if (strlen($phone) == 10)
      $phone = '91' . $phone;

    if (count($variables) == 0) {
      $msg = '{
                "messaging_product": "whatsapp",
                "to": ' . $phone . ',
                "type": "template",
                "template": {
                    "name": "' . $template . '",
                    "language": {
                        "code": "en_US"
                    }
                }
            }';
    } elseif (count($variables) == 1) {
      $msg = '{
                    "messaging_product": "whatsapp",
                    "to": ' . $phone . ',
                    "type": "template",
                    "template": {
                        "name": "' . $template . '",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "' . $variables[0] . '"
                              }
                            ]
                          }
                        ]
                    }
                }';
    } else if (count($variables) == 2) {
      $msg = '{
                    "messaging_product": "whatsapp",
                    "to": ' . $phone . ',
                    "type": "template",
                    "template": {
                        "name": "' . $template . '",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "' . $variables[0] . '"
                              },
                              {
                                "type": "text",
                                "text": "' . $variables[1] . '"
                              }
                            ]
                          }
                        ]
                    }
                }';
    } else if (count($variables) == 3) {
      $msg = '{
                    "messaging_product": "whatsapp",
                    "to": ' . $phone . ',
                    "type": "template",
                    "template": {
                        "name": "' . $template . '",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "' . $variables[0] . '"
                              },
                              {
                                "type": "text",
                                "text": "' . $variables[1] . '"
                              },
                              {
                                "type": "text",
                                "text": "' . $variables[2] . '"
                              }
                            ]
                          }
                        ]
                    }
                }';
    } else if (count($variables) == 4) {
      $msg = '{
                    "messaging_product": "whatsapp",
                    "to": ' . $phone . ',
                    "type": "template",
                    "template": {
                        "name": "' . $template . '",
                        "language": {
                            "code": "en_US"
                        },
                         "components": [
                          {
                            "type": "body",
                            "parameters": [
                              {
                                "type": "text",
                                "text": "' . $variables[0] . '"
                              },
                              {
                                "type": "text",
                                "text": "' . $variables[1] . '"
                              },
                              {
                                "type": "text",
                                "text": "' . $variables[2] . '"
                              },
                              {
                                "type": "text",
                                "text": "' . $variables[3] . '"
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
      CURLOPT_POSTFIELDS => $msg,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    //dd($response);
    return 1;
  }


  public function groupCount()
  {
    return Cache::remember('admin_group_count', 3600, function () {
      return Group::count();
    });
  }
  
  public function testCount()
  {
    return Cache::remember('admin_test_count', 3600, function () {
      return Test::count();
    });
  }
  
  public function productCount()
  {
    return Cache::remember('admin_product_count', 3600, function () {
      return Product::count();
    });
  }
  
  public function couponCount()
  {
    return Cache::remember('admin_coupon_count', 3600, function () {
      return Coupon::count();
    });
  }
}
