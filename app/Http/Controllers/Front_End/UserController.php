<?php

namespace App\Http\Controllers\Front_End;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Helpers\ImageHelper;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use Hash;
use Redirect,Response,DB,Config;
use Datatables;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;

class UserController extends Controller{
    public function index(Request $request){
        $data['js'] = ['user/index.js'];
        return view('sign_up',compact('data'));
    }

    public function signProcess(Request $request){
        $inputs   = $request->all();
        $host = request()->getHost();
        $hostArr = explode('.',$host);
        $subDomain = $hostArr[0];
        $tenantData = DB::table('tenant_details')->where('sub_domain',$subDomain)->first();
        
        $tenantId = null;
        if($tenantData){
            $tenantId = $tenantData->tenant_id;
        }

        $rules = [
            'user_name'     => 'required',
            'job_profile'   => 'required',
            'email'         => ['required', Rule::unique('users', 'email')->where('tenant_id',$tenantId)->where('role', '2')],
            'password'      => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'  => 'required|min:8',

        ];

        $this->validate($request,$rules);

        $profile_image=null;
        if($request->hasFile('profile_image')) {
            $profile_image = str_random('10').'_'.time().'.'.request()->profile_image->getClientOriginalExtension();
            request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
        }

        $User                     = new User;
        $User->role               = '2';
        $User->user_name          = $inputs['user_name'];
        $User->email              = $inputs['email'];
        $User->job_profile        = $inputs['job_profile'];
        $User->password           = Hash::make($inputs['password']);
        $User->tenant_id          = $tenantId;

        if($profile_image){
            $User->profile_image  = $profile_image;
        }

        $User->save();
        if($User){
            // $admin_mail='ganeshdhamande7@gmail.com';
            // $mailData = array(
            //     // 'first_name'    => $User->user_name,
            //     // 'email'         => $User->email,
            //     // 'user_name'     => $User->user_name,
            //     'first_name'    => 'Admin',
            //     'email'         => $admin_mail,
            //     'user_name'     => 'Admin',
            //     'form_name'     => 'Support@gmail.com',
            //     'schedule_name' => 'weBOOK',
            //     'template'      => 'signup',
            //     'subject'       => 'weBOOK',
            //     'data'          => $User,
            //     'base_url'      => url('/admin'),
            // );
            // if(!empty($mailData) && !empty($admin_mail && !is_null($admin_mail))){
            //     Mail::to($admin_mail)->send(new NotifyMail($mailData));
            // }else{
            //     //return back()->with('error','Seat reservation failed,please try again');
            // }

            $user_email= $inputs['email'];
            $userId = encrypt($User->id);
            $logo=env('Logo');
            if($logo){
                $Admin = User::where('role','1')->first();
                $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

            }else{
                $logo_url = asset('front_end/images/logo.png');
            }
            $userMailData = array(
                'name'          => $inputs['user_name'],
                'email'         => $user_email,
                'user_name'     => $inputs['user_name'],
                'form_name'     => 'Support@gmail.com',
                'schedule_name' => 'weBOOK',
                'template'      => 'user_verification',
                'subject'       => 'Verify Email',
                'data'          => $User,
                'base_url'      => url('/verify/email/'.$userId),
                'logo_url'      => $logo_url,
            );
            if(!empty($userMailData) && !empty($user_email && !is_null($user_email))){
                Mail::to($user_email)->send(new NotifyMail($userMailData));
            }else{
                //return back()->with('error','Seat reservation failed,please try again');
            }
            return Redirect('/login')->with('success','Your registration successfully,please check your email and verify your email');
        }else{
            return Redirect('/sign_up')->with('error','Your registration failed,please try again');
        }
    }
}

