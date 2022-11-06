<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Mail\EmailActivation;
use App\Mail\WelcomeMail;

use App\Mail\usercreate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';




    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    protected function redirectTo()
    {
        if(session('link'))
            return redirect(session('link')); 
        else
            return '/home';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['numeric','min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' =>  Hash::make($data['password']),
            'activation_token' => mt_rand(10000,99999),
            'lastlogin_at' => date('Y-m-d H:i:s'),
            'sms_token' => mt_rand(1000,9999),
            'client_slug'=>client('slug')
        ]);

        if(isset($data['code']))
        if($data['code']){
            $user->coupon($data['code']);
        }

        $d = [];
        foreach($data as $a=>$b){
            if(substr( $a, 0, 8 )=='register'){
                $k = str_replace('register_','',$a);
                $d[$k] = $b;
            }
        }

        $user->data = json_encode($d);
        $user->save();

        $user->resend_sms($user->phone,$user->sms_token);
        Mail::to($user->email)->send(new EmailActivation($user));

        session('created','yes');
        return $user;
    }
}
