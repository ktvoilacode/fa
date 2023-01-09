<?php

// function to retrive data from the client settings
if (!function_exists('client')) {
    function client($key){
        $client = request()->session()->get('client');
        $value = null;
        //dd($client);
        if($key=='slug')
        {
            if(isset($client->$key))
                return  $client->$key;
            else
                return 'prep';
        }
        $config = json_decode($client->config);
        //check if the settings json has the direct key and value pair
        if(isset($client->$key))
            $value = $client->$key;
        elseif(isset($config->$key))
            $value = $config->$key;            
        return $value;
    }
}
if (! function_exists('image_resize')) {
    function image_resize($image_path,$size)
    {
        $base_folder = '/app/public/';
        $path = storage_path() . $base_folder . $image_path;

        $explode= explode('.', $image_path);
        
        $new_path = storage_path() . $base_folder .$explode[0];

        $imgr = Image::make($path)->encode('webp', 100);
       
        $imgr->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
        });
        $imgr->save($new_path.'_'.$size.'.webp');  

        $imgr2 = Image::make($path)->encode('jpg', 100);
        $imgr2->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
        });
        $imgr2->save($new_path.'_'.$size.'.jpg');      
        

        return true;
    }
}

if (! function_exists('image_jpg')) {
    function image_jpg($image_path,$size)
    {
        $base_folder = '/app/public/';
        $path = storage_path() . $base_folder . $image_path;

        $explode= explode('.', $image_path);
        
        $new_path = storage_path() . $base_folder .$explode[0];

        $imgr2 = Image::make($path)->encode('jpg', 100);
            $imgr2->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
            });
            $imgr2->save($new_path.'_'.$size.'.jpg'); 
        
        return true;
    }
}


if (! function_exists('random_color')) {

function random_color() {
    $a = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    $b = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    $c = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    return $a . $b . $c;
}
}
if (! function_exists('lband')) {
    function lband($score) {
       
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
}

if (! function_exists('lmband')) {
    function lmband($score) {
       
        if($score>19 && $score <=20)
            $band = 9;
        else if($score>17 && $score <=19)
            $band = 8.5;
        else if($score>16 && $score <=17)
            $band = 8;
        else if($score>14 && $score <=16)
            $band = 7.5;
        else if($score>13 && $score <=14)
            $band = 7;
        else if($score>12 && $score <=13)
            $band = 6.5;
        else if($score>10 && $score <=12)
            $band = 6;
        else if($score>9 && $score <=10)
            $band = 5.5;
        else if($score>8 && $score <=9)
            $band = 5;
        else if($score>=7 && $score <=8)
            $band = 4.5;
        else if($score>5 && $score <=7)
            $band = 4;
        else if($score>3 && $score <=5)
            $band = 3.5;
        else if($score==3 )
            $band = 3;
        else if($score==2 )
            $band = 2.5;
        else if($score==1)
            $band = 0;
        else
            $band =0;
        return $band;
    }
}

if (! function_exists('raband')) {
    function raband($score) {
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
}

if (! function_exists('rgband')) {
    function rgband($score) {
       if($score==40)
            $band = 9;
        else if($score==39)
            $band = 8.5;
        else if($score>=37 && $score<=38)
            $band = 8;
        else if($score==36)
            $band = 7.5;
        else if($score>=34 && $score <=35)
            $band = 7;
        else if($score>=32 && $score <=33)
            $band = 6.5;
        else if($score>=30 && $score <=31)
            $band = 6;
        else if($score>=25 && $score <=29)
            $band = 5.5;
        else if($score>=20 && $score <=24)
            $band = 5;
        else if($score>=16 && $score <=19)
            $band = 4.5;
        else if($score>=11 && $score <=15)
            $band = 4;
        else if($score>=6 && $score <=10)
            $band = 3.5;
        else if($score>=2 && $score <=5)
            $band = 2.5;
        else
            $band = 1;
       
        return $band;
    }
}

if (! function_exists('rmband')) {
    function rmband($score) {
       if($score==13)
            $band = 9;
        else if($score>=12 && $score <13)
            $band = 8.5;
        else if($score>=11 && $score<12)
            $band = 8;
        else if($score>=10 && $score <11)
            $band = 7.5;
        else if($score>=8 && $score <9)
            $band = 7;
        else if($score>=7 && $score <8)
            $band = 6.5;
        else if($score>=6 && $score <7)
            $band = 6;
        else if($score>5 && $score <6)
            $band = 5.5;
        else if($score>4 && $score <=5)
            $band = 5;
        else if($score>3 && $score <=4)
            $band = 4.5;
        else if($score>2 && $score <=3)
            $band = 4;
        else if($score>1 && $score <=2)
            $band = 3.5;
        else if($score>=0 && $score <=1)
            $band = 2.5;
        else
            $band = 1;
       
        return $band;
    }
}

if (! function_exists('overallband')) {
    function overallband($a,$b,$c,$d,$e=null) {

        if($e=='academic')
            $score = round((lband($a)+raband($b)+$c+$d)/4,2);
        elseif($e=='general')
            $score = round((lband($a)+rgband($b)+$c+$d)/4,2);
        else
            $score = round((lmband($a)+rmband($b)+$c+$d)/4,2);

       if($score>=8.5 && $score <9)
            $band = 9;
        else if($score>=8.0 && $score <8.5)
            $band = 8.5;
        else if($score>=7.5 && $score <8.0)
            $band = 8;
        else if($score>=7.0 && $score <7.5)
            $band = 7.5;
        else if($score>=6.5 && $score <7.0)
            $band = 7;
        else if($score>=6.0 && $score <6.5)
            $band = 6.5;
        else if($score>=5.5 && $score <6.0)
            $band = 6;
        else if($score>=5.0 && $score <5.5)
            $band = 5.5;
        else if($score>=4.5 && $score <5.0)
            $band = 5;
        else if($score>=4.0 && $score <4.5)
            $band = 4.5;
        else if($score>=3.5 && $score <4.0)
            $band = 4;
        else if($score>=3.0 && $score <3.5)
            $band = 3.5;
        else if($score>=2.5 && $score <3.0)
            $band = 3;
        else if($score>=2.0 && $score <2.5)
            $band = 2.5;
        else if($score>=1.5 && $score <2.0)
            $band = 2;
        else if($score>=1.0 && $score <1.5)
            $band = 1.5;
        else if($score>=0.5 && $score <1.0)
            $band = 1;
        else 
            $band =0;
        return $band;
    }
}

if (! function_exists('get_string_between')) {
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

if (! function_exists('get_string_before')) {
    function get_string_before($string){
        $arr = explode("[", $string);
        return $arr[0];
    }
}

if (! function_exists('get_string_after')) {
    function get_string_after($string){
        $arr = explode("]", $string);
        return $arr[1];
    }
}

if (! function_exists('delete_all_between')) {
function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
}
}

if (! function_exists('summernote_imageupload')) {
    function summernote_imageupload($user,$editor_data)
    {
    	$detail=$editor_data;
        if($detail){
            $dom = new \DomDocument();
            libxml_use_internal_errors(true);
            $dom->loadHtml(mb_convert_encoding($detail, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    
            $images = $dom->getElementsByTagName('img');

            foreach($images as $k => $img){

                $data = $img->getAttribute('src');

                if(strpos($data, ';'))
                {
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);

                    $base_folder = "/../storage/app/public/images/";
                    $image_name=  $user->id.'_'. time().'_'.$k.'_'.rand().'.png';
                    $temp_path = public_path() . $base_folder . 'temp_' . $image_name;
                    $path = public_path() . $base_folder . $image_name;
                    file_put_contents($temp_path, $data);
                    //resize
                    $imgr = Image::make($temp_path);
                    $imgr->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $imgr->save($path);

                    unlink(trim($temp_path));

                    $img->removeAttribute('src');
                    $img->setAttribute('src', url('/').'/storage/images/'.$image_name);
                    
                    $img->setAttribute('class', 'image');
                    $img->setAttribute('class', 'w-50');
                    $img->setAttribute('style', '');
                }
                
            }
            $detail = $dom->saveHTML();
        }
        return $detail;
    }
}


if (! function_exists('subdomain')) {
function subdomain() {
    $url = url()->full();
    if($_SERVER['HTTP_HOST'] == 'gradable.in' || $_SERVER['HTTP_HOST'] == 'fa.test' || $_SERVER['HTTP_HOST'] == 'prep.firstacademy.in' || $_SERVER['HTTP_HOST'] == 'firstacademy.gradable.in'  )
            return 'prep';

    $parsed = parse_url($url);
    $exploded = explode('.', $parsed["host"]);
     if(count($exploded) > 2){
        $parsed = parse_url($url);
            $exploded = explode('.', $parsed["host"]);
            $subdomain = $exploded[0];
            return $subdomain;
     }
     else
        return null;
    

}
}

if (! function_exists('domain')) {
function domain() {
    $url = url()->full();
    $parsed = parse_url($url);
    $exploded = explode('.', $parsed["host"]);
     if(count($exploded) > 2){
        $parsed = parse_url($url);
        $exploded = explode('.', $parsed["host"]);
        $domain = $exploded[1];
        
     }
     else{
         $parsed = parse_url($url);
        $exploded = explode('.', $parsed["host"]);
        $domain = $exploded[0];
     }


    if($domain == 'gradable' || $domain== 'fa')
            $domain  = 'prep';

    return $domain;

}
}

if (! function_exists('startsWithNumber')) {
function startsWithNumber($string) {
    return strlen($string) > 0 && ctype_digit(substr($string, 0, 1));
}
}


if (! function_exists('summernote_imageremove')) {
    function summernote_imageremove($editor_data)
    {
        $detail=$editor_data;
        if($detail){
            $dom = new \DomDocument();
            libxml_use_internal_errors(true);
            $dom->loadHtml(mb_convert_encoding($detail, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    
            $images = $dom->getElementsByTagName('img');

            foreach($images as $k => $img){
                $data = $img->getAttribute('src');

                $imgr= parse_url($data);
                if(file_exists(ltrim($imgr['path'],'/')))
                unlink(ltrim($imgr['path'],'/'));
            
            }
            $detail = $dom->saveHTML();
        }
        return $detail;
    }
}

if (! function_exists('scriptStripper')) {
    function scriptStripper($input)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
    }
}