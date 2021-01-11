<?php

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