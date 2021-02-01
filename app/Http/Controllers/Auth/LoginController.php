<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;

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

    public function showLoginForm()
    {
        //Session::put('url.intended',URL::previous());
        return view('login');
    }

    public function showCleanerLoginForm()
    {
        //Session::put('url.intended',URL::previous());
        return view('cleaner.cleanerlogin');
    }

    public function showLoginFormAdmin()
    {
        //Session::put('url.intended',URL::previous());
        return view('admin.auth.login');
    }

    public function SeatrequestLoginForm(Request $request)
    {

        $inputs = $request->all();

        $code_type = Session::get('code_type');

        $img = asset("admin_assets/images/browser.png");
        if (isset($code_type) && $code_type == 'nfccode') {
            $img = asset("admin_assets/images/nfc.png");
        } elseif (isset($code_type) && $code_type == 'qrcode') {
            $img = asset("admin_assets/images/scan.png");
        }

        return view('seat_request.login', compact('img'));
    }

    protected function authenticated(Request $request, $user)
    {

        if ($request->session()->has('seat_login')) {
            Session::forget('seat_login');

            if ($request->session()->has('last_url')) {
                $url = Session::get('last_url');
                Session::forget('last_url');
                Session::forget('code_type');

                if ($user->role == 1) {
                    return redirect($url);
                } elseif ($user->role == 2 or $user->role == 3) {
                    if ($user->email_verify_status != '1') {
                        $this->guard()->logout();
                        $request->session()->invalidate();
                        return back()->with('error', 'Your email not verify , please verify your email');
                    } else {
                        if ($user->approve_status == '0') {
                            $this->guard()->logout();
                            $request->session()->invalidate();
                            return back()->with('error', 'Your account is not approved by the admin, please contact your administrator');
                        } elseif ($user->approve_status == '1') {
                            return redirect($url);
                        } elseif ($user->approve_status == '2') {
                            $this->guard()->logout();
                            $request->session()->invalidate();
                            return back()->with('error', 'Your account rejected by admin,Please contact weBook');
                        }
                    }
                }

                return redirect($url);
            } else {
                return redirect('/seatrequest');
            }
        }

        if ($request->role != $user->role) {
            $this->guard()->logout();
            $request->session()->invalidate();
            return back()->with('error', 'You are not login this page,please visit right login page');
        }
        if ($user->role == 1) {
            return redirect('admin/dashboard');
        } elseif ($user->role == 2 or $user->role == 3) {
            if ($user->email_verify_status != '1') {
                $this->guard()->logout();
                $request->session()->invalidate();
                return back()->with('error', 'Your email not verify , please verify your email');
            } else {
                if ($user->approve_status == '0') {
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return back()->with('error', 'Your account is not approved by the admin, please contact your administrator');
                } elseif ($user->approve_status == '1') {
                    return redirect('index');
                } elseif ($user->approve_status == '2') {
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return back()->with('error', 'Your account rejected by admin,Please contact weBook');
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
    public function logout(Request $request)
    {
        if (isset($request->seat_request) && $request->seat_request == 1) {
            $this->guard()->logout();
            $request->session()->invalidate();
            $type = $request->type;
            return view('seat_request.seat_logout', compact('type'));
        }

        if (Auth::user()->role == '1') {
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/admin')->with('success', 'Your account logout successfully');
        } else {
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/')->with('success', 'Your account logout successfully');
        }
        // Auth::guard('admin')->logout();
        // $request->session()->flush();
        // $request->session()->regenerate();
        //return redirect()->guest(route( 'admin.login' ));
    }

    public function seat_logout(Request $request)
    {

        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/seatrequest/seat_logout')->with('success', 'Your account logout successfully');

    }
}
