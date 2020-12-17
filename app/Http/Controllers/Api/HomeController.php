<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Validation\Rule;
use App\Mail\NotifyMail;
use Validator;
use Auth;
use Hash;
use DB;
use Mail;
use App\Helpers\ImageHelper;

class HomeController extends Controller{

    public function createTenant(Request $request){

        $inputs  = $request->all();
        $rules = [
            'adminEmail'    => 'required|unique:users,email,null,id,deleted_at,NULL',
            'tenantName'    => 'required',
            'password'      => 'required|min:8',
            'planDetails'   => 'required'
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        
        $email = $inputs['adminEmail'];
        $email = explode('@',$email);
        $email = $email[0];

        $host = $email . '.' . request()->getHost();

        $insertData = array(
             'email'    => $inputs['adminEmail'],
             'password' => Hash::make($inputs['password']),
             'user_name' => $inputs['tenantName'],
             'role'      => '1',
             'approve_status' => '1'
         );
            DB::beginTransaction();

            try {

                $tenantId = DB::table('users')->insertGetId($insertData);
                $plandDetails = array(
                    "tenant_id"   => $tenantId,
                    'allow_Logo'   => $inputs['planDetails']['allowLogo']   ?? false,
                    'max_building' => $inputs['planDetails']['maxbuilding'] ?? '0',
                    'max_office'   => $inputs['planDetails']['maxoffice']   ?? '0',
                    'sub_domain'   => $email[0]
                );
                DB::table('tenant_details')->insertGetId($plandDetails);
                DB::commit();
                $inputs['tenantID']   = $tenantId;
                $inputs['sub_domain'] = $host;
                $User = User::find($tenantId);
                $user_email= $User->email;
            $userId = encrypt($User->id);
            $logo=env('Logo');
            if($logo){
                $Admin = User::where('role','1')->first();
                $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

            }else{
                $logo_url = asset('front_end/images/logo.png');
            }
            $userMailData = array(
                'name'          => $inputs['tenantName'],
                'email'         => $user_email,
                'password'      => $inputs['password'],
                'user_name'     => $inputs['tenantName'],
                'form_name'     => 'Support@gmail.com',
                'schedule_name' => 'weBOOK',
                'template'      => 'admin_signup_mail',
                'subject'       => 'Registration',
                'data'          => $User,
                'base_url'      => url('/admin'),
                'logo_url'      => $logo_url,
            );
            if(!empty($userMailData) && !empty($user_email && !is_null($user_email))){
             //   Mail::to($user_email)->send(new NotifyMail($userMailData));
            }

                return ['status'=>true,'message'=>'Successfully registered','data'=>$inputs];
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
                return ['status'=>false,'message'=>'Failed to registered'];
            }
    } 

    public function tenantList(Request $request){
        
        $tenats = User::select('users.id as tanantID','users.user_name as tenantName','users.email as adminEmail')
                        ->where('role','1')
                        ->where('active_status','1')
                        ->whereNull('deleted_at')
                        ->get();

        if($tenats->toArray()){
        return ['status'=>true,'message'=>'Record found','data'=>$tenats];
        }
        return ['status'=>false,'message'=>'Record not found'];
    }

    public function updateTenant(Request $request){
        $inputs   = $request->all();
        $tenantId = $request->tenantID;
        $rules = [
            'tenantID'      => 'required',
            'adminEmail'    => 'required|unique:users,email,'.$tenantId.',id,deleted_at,NULL',
            'tenantName'    => 'required',
            'planDetails'   => 'required'
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }

        $updateData = array(
             'email'    => $inputs['adminEmail'],
             'user_name' => $inputs['tenantName']
         );

            DB::beginTransaction();

            try {
                DB::table('users')->where('id',$tenantId)->update($updateData);
                $plandDetails = array(
                    'allow_logo'   => $inputs['planDetails']['allowLogo']   ?? false,
                    'max_building' => $inputs['planDetails']['maxbuilding'] ?? '0',
                    'max_office'   => $inputs['planDetails']['maxoffice']   ?? '0',
                    'max_seats'    => $inputs['planDetails']['maxseats']    ?? '0',
                );
                DB::table('tenant_details')->where('tenant_id',$tenantId)->update($plandDetails);
                DB::commit();
                return ['status'=>true,'message'=>'Successfully updated','data'=>$inputs];
            } catch (\Exception $e) {
                DB::rollback();
                return ['status'=>false,'message'=>'Failed to update'];
            }
    }

    public function removeTenant(Request $request){
        $inputs  = $request->all();
        $rules = [
            'tenantID'     =>' required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }

        $updateData = array(
             'deleted_at'    => date('Y-m-d H:i:s')
         );

        if(DB::table('users')->where('id',$inputs['tenantID'])->update($updateData)){
         return ['status'=>true,'message'=>'Successfully removed','data'=>$inputs];
        }
        return ['status'=>false,'message'=>'Failed to remove'];
    }

}