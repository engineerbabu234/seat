<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('verifyEmail');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function verifyEmail($id){

        try {

            $id = decrypt($id);
            $user = User::find($id);
        if($user){

            if($user->email_verify_status != 1){
                 $user->email_verify_status = '1';

                 if($user->update()){
                    $logo=env('Logo');
                    $Admin = User::where('role','1')->first();
                    if($logo){
                        $logo_url =asset('uploads/profiles/'.$Admin->logo_image);
                        $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

                    }else{
                        $logo_url = asset('front_end/images/logo.png');
                    }

                    $AdminEmail=$Admin->email;

                    $mailData = array(
                        'first_name'    => 'Admin',
                        'email'         => $AdminEmail,
                        'user_name'     => $user->user_name,
                        'form_name'     => 'Support@gmail.com',
                        'schedule_name' => 'weBOOK',
                        'template'      => 'verify_admin',
                        'subject'       => 'weBOOK',
                        'data'          => $user,
                        'base_url'      => url('/admin'),
                        'logo_url'      => $logo_url,
                    );

                    if(!empty($mailData) && !empty($AdminEmail && !is_null($AdminEmail))){
                        Mail::to($AdminEmail)->send(new NotifyMail($mailData));
                    }

                    $data['status'] = '1';
                 }else{
                   $data['status'] = '2';
                 }
            }else{
              $data['status'] = '0';
            }
        }else{
            $data['status'] = '2';
        }
        } catch ( \Exception $e) {

            $data['status'] = '2';
        }
      return view('verify_email',compact('data'));
    }
}
