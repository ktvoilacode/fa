<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Product\Client;
use Closure;

class Subdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domain = request()->getHost();
        if( $domain!='prep.firstacademy.in' && $domain!='fa.test' && $domain!='project.test' && $domain!='prep.packetprep.com' && $domain!= 'gradable.in' && $domain!= 'test.piofx.com' ){
             
             $client = Cache::remember('client_'.$domain,2400,function() use($domain){
                return Client::where('domains','LIKE',"%{$domain}%")->first();
             });

             if(!$client){
                abort(404,'Site not found');
            }else{

                $client = json_decode($client); 
                   if(Storage::disk('s3')->exists('clients/'.$client->slug.'_logo.png')){
                        $client->logo = Storage::disk('s3')->url('clients/'.$client->slug.'_logo.png');
                    }
                    else if(Storage::disk('s3')->exists('clients/'.$client->slug.'_logo.jpg'))
                        $client->logo = Storage::disk('s3')->url('clients/'.$client->slug.'_logo.jpg');
                    else{
                        $client->logo = url('/').'/images/gradable.png';
                    } 

                        if($client->status==0)
                            abort(403,'Site is not published');
                        elseif($client->status==2)
                            abort(403,'Site is suspended');
                        elseif($client->status==3)
                            abort(403,'Site services are terminated');
                        $request->session()->put('client',$client);
                        $request->session()->put('config',json_decode($client->config));
            }

            
        }elseif($domain== 'test.piofx.com'){
            $client = new client();
            $client->slug = 'prep';
            $client->name = 'Piofx Media';
            $client->logo = url('/').'/images/piofx.png';
            $request->session()->put('client',$client);
        }
        elseif($domain== 'gradable.in' || $domain== 'fa.test'){
            $client = new client();
            $client->slug = 'prep';
            $client->name = 'Gradable';
            $client->logo = url('/').'/images/gradable.png';
            $request->session()->put('client',$client);
        }
        else{
            $client = new client();
            $client->slug = 'prep';
            $client->name = 'First Academy';
            $client->logo = url('/').'/images/logo.png';
            $request->session()->put('client',$client);
        }
        return $next($request);

    }
}
