<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
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
        if($request->ajax()){
            $users =DB::table('users as u')
                        ->select('u.*')
                        ->where('u.role', '=', "2")
                        ->get();
            $number_key=1;
            foreach ($users as $key => $value) {
                $value->number_key=$number_key;
                $number_key++;
                $value->profile_image=ImageHelper::getProfileImage($value->profile_image);
            }
            return datatables()->of($users)->make(true);
        }
        $data['user_count'] =DB::table('users as u')->where('u.role', '=', "2")->count();
        $data['js'] = ['user/index.js'];
        return view('admin.user.index',compact('data'));
    }

    public function show(Request $request , $id){
        
        $data['js'] = ['user/show.js'];
        return view('admin.user.show',compact('data'));
    }

    public function activeStatusChange(Request $request){
        $inputs                     = $request->all();
        $status=$inputs['status'];
        if($status=='0'){
            $change_status='1';
        }else{
            $change_status='0';
        }
        
        $User                       = User::find($inputs['id']);
        $User->active_status        = $change_status;
        if($User->update()){
            if($status==0){
                return ['status' => 'success' , 'message' => 'User activated successfully', 'data'=>$User];
            }else{
                return ['status' => 'success' , 'message' => 'User deactivated successfully', 'data'=>$User]; 
            }
        }else{
           return ['status' => 'failed' , 'message' => 'Status updated failed'];   
        }
    }
    
    public function approveStatusChange(Request $request){
        $inputs                     = $request->all();
        $status=$inputs['status'];
        if($status=='1'){
            $change_status='1';
        }else{
            $change_status='2';
        }
        
        $User                       = User::find($inputs['id']);
        $User->approve_status        = $change_status;
        if($User->update()){
            if($status==1){
                $mailData = array(
                    'first_name'    => $User->user_name,
                    'email'         => $User->email,
                    'user_name'     => $User->user_name,
                    'form_name'     => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template'      => 'approved',
                    'subject'       => 'weBOOK',
                    'data'          => $User,
                    'base_url'      => url('/login'),  
                );
                if(!empty($mailData) && !empty($User->email && !is_null($User->email))){
                    Mail::to($User->email)->send(new NotifyMail($mailData));
                }else{
                    //return back()->with('error','Seat reservation failed,please try again');
                }
                return ['status' => 'success' , 'message' => 'User approved successfully', 'data'=>$User];
            }else{
                $mailData = array(
                    'first_name'    => $User->user_name,
                    'email'         => $User->email,
                    'user_name'     => $User->user_name,
                    'form_name'     => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template'      => 'rejected',
                    'subject'       => 'weBOOK',
                    'data'          => $User,
                    'base_url'      => url('/'),  
                );
                if(!empty($mailData) && !empty($User->email && !is_null($User->email))){
                    Mail::to($User->email)->send(new NotifyMail($mailData));
                }else{
                    //return back()->with('error','Seat reservation failed,please try again');
                }
                return ['status' => 'success' , 'message' => 'User rejected successfully', 'data'=>$User]; 
            }
        }else{
           return ['status' => 'failed' , 'message' => 'Status updated failed'];   
        }
    }
}
