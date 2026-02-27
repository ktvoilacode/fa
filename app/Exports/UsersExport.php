<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $attempts= request()->session()->get('attempts');

        $userattempts = $attempts->keyBy('user_id');
        $ids = $attempts->pluck('user_id')->toArray();
        $usr = User::whereIn('id', $ids)->get()->keyBy('id');
         
        $data = [];
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
            $attempt = $d = $userattempts[$k];
            if($data)
            foreach($data as $name =>$value){
                $usr[$k]->$name = $value;
            }
            if($attempt->t1_score!=NULL)
            $usr[$k]->listening_band=lmband($attempt->t1_score);
            else
                $usr[$k]->listening_band = 'NA';
            if($attempt->t2_score!=NULL)
            $usr[$k]->reading_band=rmband($attempt->t2_score);
            else
                $usr[$k]->reading_band = 'NA';
            if($attempt->t3_score!=NULL)
            $usr[$k]->writing_band= $attempt->t3_score;
            else
                $usr[$k]->writing_band= 'NA';
            if($attempt->t4_score!=NULL)
            $usr[$k]->speaking_band=$attempt->t4_score;
            else
            $usr[$k]->speaking_band = 'NA';
            $usr[$k]->overall_band= overallband($attempt->t1_score,$attempt->t2_score,$attempt->t3_score,$attempt->t4_score);
            
        }

        $ux = new User();
        $ux->id = "Sno";
        $ux->name = "Name";
        $ux->email = "Email";
        $ux->phone = "Phone";
        if($data)
        foreach($data as $name =>$value){
            $ux->$name = $name;
        }
        $ux->lband= "Listening band";
        $ux->rband= "Reading band";
        $ux->wband= "Writing band";
        $ux->sband= "Speaking band";
        $ux->band= "Overall band";
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
