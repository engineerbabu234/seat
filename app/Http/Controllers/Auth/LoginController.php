<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\Redirect;

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

    public function showLoginForm(){
        //Session::put('url.intended',URL::previous());
        return view('login');
    }
    public function showLoginFormAdmin(){
        //Session::put('url.intended',URL::previous());
        return view('admin.auth.login');
    }

    protected function authenticated(Request $request, $user){
        if($request->role!=$user->role){
            $this->guard()->logout();
            $request->session()->invalidate();
            return back()->with('error','You are not login this page,please visit right login page');
        }
        if($user->role==1){
            return redirect('admin/dashboard');
        }elseif($user->role==2){
            if($user->email_verify_status!='1'){
                $this->guard()->logout();
                $request->session()->invalidate();
                return back()->with('error','Your email not verify , please verify your email');
            }else{
                if($user->approve_status=='0'){
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return back()->with('error','Your account is not approved by the admin, please contact your administrator');
                }elseif($user->approve_status=='1'){
                    return redirect('index');
                }elseif($user->approve_status=='2'){
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return back()->with('error','Your account rejected by admin,Please contact weBook');
                }
            }    
        }
    }

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
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        if(Auth::user()->role=='1'){
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/admin')->with('success','Your account logout successfully');;
        }else{
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/')->with('success','Your account logout successfully');
        }
        // Auth::guard('admin')->logout();
        // $request->session()->flush();
        // $request->session()->regenerate();
        //return redirect()->guest(route( 'admin.login' ));
    }
}
