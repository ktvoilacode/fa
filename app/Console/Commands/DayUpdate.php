<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

use App\Models\Test\Writing;
use App\Mail\reviewnotify;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Mail;

class DayUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail once in a day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $writings = Writing::where('notify','!=',0)->get();
       foreach($writings as $w){
            $h = date('H')+5;
            if($w->notify==$h)
            {
                $test = $w->attempt->test;
                $user = $w->attempt->user;
                Mail::to($user->email)->send(new reviewnotify($user,$test));
                $w->notify = 0;
                $w->status = 1;
                $w->save();

                //send whatsapp
                $obj = $user;
                // send whatsapp
                $var =[];
                $var[0]= $obj->name;
                if(strlen($obj->phone)==10)
                    $phone = '91'.$obj->phone;
                else if(strlen($obj->phone)==12)
                    $phone = $obj->phone;
                $template = 'writing_evaluation';
                if(strlen($phone)==12){
                    Admin::sendWhatsapp($phone,$template,$var);
                }
                $this->info('Hourly Update has been send successfully');


            }
            
       }
       
    }
}
