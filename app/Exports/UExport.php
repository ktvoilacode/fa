<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\DB;

class UExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $usr = User::where('client_slug', subdomain())->get()->keyBy('id');
         
        foreach($usr as $k=>$u){
            unset($usr[$k]->created_at);
            unset($usr[$k]->updated_at);
            unset($usr[$k]->password);
            unset($usr[$k]->remember_token);
            unset($usr[$k]->sms_token);
            unset($usr[$k]->lastlogin_at);
            unset($usr[$k]->admin);
            unset($usr[$k]->email_verified_at);
            unset($usr[$k]->status);
            unset($usr[$k]->auto_password);
            unset($usr[$k]->activation_token);
            unset($usr[$k]->idno);
            unset($usr[$k]->id);
            unset($usr[$k]->user_id);
            unset($usr[$k]->enrolled);
            unset($usr[$k]->comment);
            unset($usr[$k]->info);
            $data = json_decode($usr[$k]->data,true);
            unset($usr[$k]->data);
            unset($usr[$k]->client_slug);
            if($data)
            foreach($data as $name =>$value){
                $usr[$k]->$name = $value;
            }
           
            
        }

        $ux = new User();
        $ux->id = "Sno";
        $ux->name = "Name";
        $ux->email = "Email";
        $ux->phone = "Phone";
        foreach($data as $name =>$value){
            $ux->$name = $name;
        }
       
        unset($ux->id);
        unset($ux->created_at);
        unset($ux->updated_at);
        unset($ux->password);
        unset($ux->remember_token);
        unset($ux->sms_token);
        unset($ux->lastlogin_at);
        unset($ux->admin);
        unset($ux->email_verified_at);
        unset($ux->status);
        unset($ux->auto_password);
        unset($ux->activation_token);
        unset($ux->idno);
        unset($ux->user_id);
        unset($ux->enrolled);
        unset($ux->comment);
        unset($ux->info);
        unset($ux->data);
        unset($ux->client_slug);
        $usr->prepend($ux);

        return $usr;
    }
}
