<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = 'email';

     

        return [
                $field => $request->get('email'),
                'password' => $request->password,
                'client_slug' =>client('slug')
            ];
            
        // if($request->client_slug){
        //         return [
        //         $field => $request->get($this->username()),
        //         'password' => $request->password,
        //         'client_slug' =>$request->client_slug
        //     ];
        // }else{
        //     return [
        //     $field => $request->get($this->username()),
        //     'password' => $request->password
        //     ];
        // }

        
    }


    public function authenticated(Request $request, $user)
    {

        $user->lastlogin_at = date('Y-m-d H:i:s');
        $user->save();
        
        $request->session()->put('username', $user->name);
        /*
        if($user->admin==1)
            return redirect()->intended($this->redirectPath());
        */

        $credits['unused'] =10000; 
        if(subdomain()!='prep'){
             $credits = Cache::get('credits_'.subdomain());
             if($credits['unused']<-10000 && \auth::user()->admin!=5){
                auth()->logout();
                return back()->with('warning', 'Your website is frozen. Kindly contact the administrator for the access.');
             }

        }
       
        
        
        if ($user->status==0) {
            auth()->logout();
            return back()->with('warning', 'Your account is in blocked state. Kindly contact the administrator for the access.');
        }



        if(session('link'))
            return redirect(session('link')); 
        else{
            if($user->admin!==0)
            return redirect()->route('admin');
            else
            return redirect()->intended($this->redirectPath());
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        $link = session('link');

        $request->session()->regenerate();

        session(['link' => $link]);

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }
}
