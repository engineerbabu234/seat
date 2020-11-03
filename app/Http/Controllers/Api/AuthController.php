<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiHelper;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\DriverDocument;
use App\Models\Notification;
use App\Models\Page;
use App\Models\User;
use App\Models\VehicleType;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Response;
use Validator;

class AuthController extends Controller
{
    public function getVehicleType(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $VehicleType = VehicleType::get();
        if ($VehicleType) {
            foreach ($VehicleType as $key => $value) {
                $value->image = ImageHelper::getVehicleTypeImage($value->image);
            }
            return response(['status' => true, 'message' => $langData['record_found'], 'data' => $VehicleType]);
        } else {
            return response(['status' => false, 'message' => $langData['record_not_found']]);
        }
    }

    public function updateAddress(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
            'address.required' => $langData['address'],
            'lat.required' => $langData['lat'],
            'lng.required' => $langData['lng'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $userStatus = User::where('id', $inputs['id'])->first();
        if ($userStatus) {
            //===================Image Upload Hear============
            $User = User::find($userStatus->id);
            $User->address = $inputs['address'];
            $User->lat = $inputs['lat'];
            $User->lng = $inputs['lng'];
            if ($User->save()) {
                return response(['status' => true, 'message' => $langData['profile_update_success']]);
            } else {
                return response(['status' => true, 'message' => $langData['profile_update_error']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function getLatLng(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $userStatus = User::select('lat', 'lng')->where('id', $inputs['id'])->first();
        if ($userStatus) {
            return response(['status' => true, 'message' => $langData['record_found'], 'data' => $userStatus]);
        } else {
            return response(['status' => false, 'message' => $langData['record__not_found']]);
        }
    }

    public function signUp(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'role' => 'required',
            'user_name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->where('role', $inputs['role'])],

            'phone_number' => ['required', Rule::unique('users', 'phone_number')->where('role', $inputs['role'])],
            'password' => 'required',
            'device_type' => 'required',
            'device_token' => 'required',
        ];

        $message = [
            'role.required' => $langData['role'],
            'user_name.required' => $langData['user_name'],
            'email.required' => $langData['email'],
            'email.unique' => $langData['email_unique'],

            'phone_number.required' => $langData['phone_number'],
            'phone_number.unique' => $langData['phone_number_unique'],
            'password.required' => $langData['password'],
            'device_type.required' => $langData['device_type'],
            'device_token.required' => $langData['device_token'],

        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        //===================Image Upload Hear============
        $profile_image = null;
        if ($request->hasFile('profile_image')) {
            $profile_image = str_random('10') . '_' . time() . '.' . request()->profile_image->getClientOriginalExtension();
            request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
        }

        $User = new User;
        $User->role = $inputs['role'];
        $User->user_name = $inputs['user_name'];
        $User->email = $inputs['email'];
        $User->phone_number = $inputs['phone_number'];
        $User->password = Hash::make($inputs['password']);
        $User->device_type = $inputs['device_type'];
        $User->device_token = $inputs['device_token'];
        $User->api_status = '0';

        if ($profile_image) {
            $User->profile_image = $profile_image;
        }
        if (!empty($inputs['lang'])) {
            $User->language = $inputs['lang'];
        }
        $User->save();
        if ($User) {
            $User->profile_image = ImageHelper::getProfileImage($User->profile_image);
            return response(['status' => true, 'message' => $langData['registration_success'], 'data' => $User]);
        } else {
            return response(['status' => false, 'message' => $langData['registration_error']]);
        }
    }

    public function updateDetails1(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
            'vehicle_type' => 'required',
            'vehicle_number' => 'required',
            'vehicle_color' => 'required',
            'vehicle_registration_number' => 'required',
            'licence_number' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
            'vehicle_type.required' => $langData['vehicle_type'],
            'vehicle_number.required' => $langData['vehicle_number'],
            'vehicle_color.required' => $langData['vehicle_color'],
            'vehicle_registration_number.required' => $langData['vehicle_registration_number'],
            'licence_number.required' => $langData['licence_number'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        $userStatus = User::where('id', $inputs['id'])->where('role', '3')->first();
        if ($userStatus) {
            //===================Image Upload Hear============
            $User = User::find($userStatus->id);
            $User->vehicle_type_id = $inputs['vehicle_type'];
            $User->vehicle_number = $inputs['vehicle_number'];
            $User->vehicle_color = $inputs['vehicle_color'];
            $User->vehicle_registration_number = $inputs['vehicle_registration_number'];
            $User->licence_number = $inputs['licence_number'];
            $User->api_status = '1';
            if ($User->save()) {
                return response(['status' => true, 'message' => $langData['profile_update_success'], 'data' => $User]);
            } else {
                return response(['status' => true, 'message' => $langData['profile_update_error']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function updateDetails2(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
            'selfie_image' => 'required',
            'vehicle_image' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
            'selfie_image.required' => $langData['selfie_image'],
            'vehicle_image.required' => $langData['vehicle_image'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        $userStatus = User::where('id', $inputs['id'])->where('role', '3')->first();
        if ($userStatus) {
            //===================Image Upload Hear============
            $vehicle_image = null;
            if ($request->hasFile('vehicle_image')) {
                if (request()->vehicle_image->getClientOriginalExtension()) {
                    $ext1 = request()->vehicle_image->getClientOriginalExtension();
                } else {
                    $ext1 = 'png';
                }
                $vehicle_image = str_random('10') . '_' . time() . '.' . $ext1;
                request()->vehicle_image->move(public_path('uploads/vehicle_image/'), $vehicle_image);
            }

            $selfie_image = null;
            if ($request->hasFile('selfie_image')) {
                if (request()->selfie_image->getClientOriginalExtension()) {
                    $ext2 = request()->selfie_image->getClientOriginalExtension();
                } else {
                    $ext2 = 'png';
                }
                $selfie_image = str_random('10') . '_' . time() . '.' . $ext2;
                request()->selfie_image->move(public_path('uploads/selfie_image/'), $selfie_image);
            }

            $image1 = null;
            if ($request->hasFile('image1')) {
                if (request()->image1->getClientOriginalExtension()) {
                    $ext3 = request()->image1->getClientOriginalExtension();
                } else {
                    $ext3 = 'png';
                }
                $image1 = str_random('10') . '_' . time() . '.' . $ext3;
                request()->image1->move(public_path('uploads/document/'), $image1);
            }

            $image2 = null;
            if ($request->hasFile('image2')) {
                if (request()->image2->getClientOriginalExtension()) {
                    $ext4 = request()->image2->getClientOriginalExtension();
                } else {
                    $ext4 = 'png';
                }
                $image2 = str_random('10') . '_' . time() . '.' . $ext4;
                request()->image2->move(public_path('uploads/document/'), $image2);
            }

            $image3 = null;
            if ($request->hasFile('image3')) {
                if (request()->image3->getClientOriginalExtension()) {
                    $ext5 = request()->image3->getClientOriginalExtension();
                } else {
                    $ext5 = 'png';
                }
                $image3 = str_random('10') . '_' . time() . '.' . $ext5;
                request()->image3->move(public_path('uploads/document/'), $image3);
            }

            $image4 = null;
            if ($request->hasFile('image4')) {
                if (request()->image4->getClientOriginalExtension()) {
                    $ext6 = request()->image4->getClientOriginalExtension();
                } else {
                    $ext6 = 'png';
                }
                $image4 = str_random('10') . '_' . time() . '.' . $ext6;
                request()->image4->move(public_path('uploads/document/'), $image4);
            }
            $imageData = [];
            if ($image1) {
                array_push($imageData, $image1);
            }
            if ($image2) {
                array_push($imageData, $image2);
            }
            if ($image3) {
                array_push($imageData, $image3);
            }
            if ($image4) {
                array_push($imageData, $image4);
            }
            $User = User::find($userStatus->id);
            $User->vehicle_image = $vehicle_image;
            $User->selfie_image = $selfie_image;
            $User->api_status = '2';
            if ($User->save()) {
                $documentData = [];
                foreach ($imageData as $key => $value) {
                    $temp = [];
                    $temp['document'] = $value;
                    $temp['user_id'] = $User->id;
                    array_push($documentData, $temp);
                }
                DriverDocument::insert($documentData);
                return response(['status' => true, 'message' => $langData['profile_update_success'], 'data' => $User]);
            } else {
                return response(['status' => true, 'message' => $langData['profile_update_error']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function login(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'role' => 'required',
            'email' => 'required',
            'password' => 'required',
            'device_type' => 'required',
            'device_token' => 'required',
        ];

        $message = [
            'role.required' => $langData['role'],
            'email.required' => $langData['email'],
            'password.required' => $langData['password'],
            'device_type.required' => $langData['device_type'],
            'device_token.required' => $langData['device_token'],
        ];
        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $emailStatus = User::where('role', $inputs['role'])->where('email', $inputs['email'])->first();
        if ($emailStatus) {
            if (password_verify($request->password, $emailStatus->password)) {
                $User = User::find($emailStatus['id']);
                $User->device_type = $inputs['device_type'];
                $User->device_token = $inputs['device_token'];
                if ($inputs['lang']) {
                    $User->language = $inputs['lang'];
                }
                $User->update();
                $User->profile_image = ImageHelper::getProfileImage($User->profile_image);
                return response(['status' => true, 'message' => $langData['login_success'], 'data' => $User]);
            } else {
                return response(['status' => false, 'message' => $langData['invalid_password']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['email_not_exist']]);
        }
    }

    public function forgotPassword(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'role' => 'required',
            'email' => 'required',
        ];

        $message = [
            'role.required' => $langData['role'],
            'email.required' => $langData['email'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $emailStatus = User::where('role', $inputs['role'])->where('email', $inputs['email'])->first();
        if ($emailStatus) {
            $otp = ApiHelper::otpGenrator(4);
            $mail_data = array(
                'first_name' => $emailStatus->user_name,
                'email' => $emailStatus->email,
                'user_name' => $emailStatus->user_name,
                'form_name' => 'ganeshdhamande7@gmail.com',
                'schedule_name' => 'Moyamarket',
                'template' => 'email_otp_send',
                'subject' => 'FruitMart',
                'otp' => $otp,
            );

            $User = User::find($emailStatus['id']);
            $User->otp = $otp;
            $User->otp_verify_status = '0';
            $User->update();
            if (!empty($mail_data) && !empty($emailStatus->email && !is_null($emailStatus->email))) {
                Mail::to($emailStatus->email)->send(new NotifyMail($mail_data));
            } else {
                return response(['status' => false, 'message' => $langData['otp_not_send']]);
            }
            return response(['status' => true, 'message' => $langData['otp_send_success'], 'data' => $User]);
        } else {
            return response(['status' => false, 'message' => $langData['email_not_exist']]);
        }
    }

    public function forgotPasswordChange(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
            'otp' => 'required',
            'password' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
            'otp.required' => $langData['otp'],
            'password.required' => $langData['password'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $userStatus = User::where('id', $inputs['id'])->first();
        if ($userStatus) {
            $otpStatus = User::where('id', $inputs['id'])->where('otp', $inputs['otp'])->first();
            if ($otpStatus) {
                $User = User::find($userStatus['id']);
                $User->password = Hash::make($inputs['password']);
                $User->otp_verify_status = '1';
                if ($User->update()) {
                    return response(['status' => true, 'message' => $langData['change_password_success']]);
                } else {
                    return response(['status' => false, 'message' => $langData['change_password_error']]);
                }

            } else {
                return response(['status' => false, 'message' => $langData['otp_wrong_error']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function getProfile(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $userStatus = User::where('id', $inputs['id'])->first();
        if ($userStatus) {
            $userStatus->profile_image = ImageHelper::getProfileImage($userStatus->profile_image);
            return response(['status' => true, 'message' => $langData['record_found'], 'data' => $userStatus]);
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function profileUpdate(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
            'role' => 'required',
            'user_name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->where('role', $inputs['role'])->ignore($inputs['id'], 'id')],

            'phone_number' => ['required', Rule::unique('users', 'phone_number')->where('role', $inputs['role'])->ignore($inputs['id'], 'id')],
        ];

        $message = [
            'id.required' => $langData['id'],
            'role.required' => $langData['role'],
            'user_name.required' => $langData['user_name'],
            'email.required' => $langData['email'],
            'phone_number.required' => $langData['phone_number'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        $userStatus = User::where('id', $inputs['id'])->first();
        if ($userStatus) {
            //===================Image Upload Hear============
            $profile_image = null;
            if ($request->hasFile('profile_image')) {
                $profile_image = str_random('10') . '_' . time() . '.' . request()->profile_image->getClientOriginalExtension();
                request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
            }

            $User = User::find($userStatus->id);
            $User->user_name = $inputs['user_name'];
            $User->email = $inputs['email'];
            $User->phone_number = $inputs['phone_number'];
            if ($profile_image) {
                $User->profile_image = $profile_image;
            }
            if ($User->update()) {

                $User = User::select('id', 'role', 'user_name', 'email', 'phone_number', 'address', 'lat', 'lng', 'profile_image')->find($User->id);
                if ($User) {
                    $User->profile_image = ImageHelper::getProfileImage($User->profile_image);
                }

                return response(['status' => true, 'message' => $langData['profile_update_success'], 'data' => $User]);
            } else {
                return response(['status' => true, 'message' => $langData['profile_update_error']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function getNotfication(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();

        $rules = [
            'id' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
        ];

        if ($inputs['id']) {
            $notificationData = Notification::where('receiver_id', $inputs['id'])->orderBy('notification_id', 'desc')->get();
            if ($notificationData->toArray()) {
                return response(['status' => true, 'message' => $langData['record_found'], 'data' => $notificationData]);
            } else {
                return response(['status' => false, 'message' => $langData['record_not_found']]);
            }
        }
    }

    public function notificationUpdateStatus(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();

        $rules = [
            'id' => 'required',
        ];

        $message = [
            'id.required' => $langData['id'],
        ];
        $userStatus = User::where('id', $inputs['id'])->first();
        if ($userStatus) {
            if ($inputs['noti_status']) {
                $User = User::find($userStatus->id);
                $User->device_type = $inputs['device_type'];
                $User->device_token = $inputs['device_token'];
                $User->update();
            } else {
                $User = User::find($userStatus->id);
                $User->device_type = $inputs['device_type'];
                $User->device_token = '';
                $User->update();
            }
            if ($User) {
                return response(['status' => true, 'message' => $langData['notification_success']]);
            } else {
                return response(['status' => false, 'message' => $langData['notification_error']]);
            }

        } else {
            return response(['status' => false, 'message' => $langData['record_not_found']]);
        }
    }

    public function changePassword(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();
        $rules = [
            'id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required',
        ];
        $message = [
            'id.required' => $langData['id'],
            'old_password.required' => $langData['old_password'],
            'new_password.required' => $langData['new_password'],
        ];
        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }

        $User = User::find($inputs['id']);
        if (!(Hash::check($request->old_password, $User->password))) {
            return response(['status' => false, 'message' => $langData['old_password_not_match']], 200);
        } elseif (strcmp($request->old_password, $request->new_password) == 0) {
            return response(['status' => false, 'message' => $langData['old_password_new_password_same']], 200);
        }

        $User = User::find($inputs['id']);
        $User->password = Hash::make($inputs['new_password']);
        if ($User->update()) {
            return response(['status' => true, 'message' => $langData['password_success']], 200);
        }
        return response(['status' => false, 'message' => $langData['password_error']], 200);
    }

    public function page(Request $request)
    {
        $langData = trans('api_auth');
        $inputs = $request->all();

        $Page = Page::where('type', $inputs['page_type'])->first();
        if ($Page) {
            return response(['status' => true, 'message' => $langData['record_found'], 'data' => $Page]);
        } else {
            return response(['status' => false, 'message' => $langData['record_not_found']]);
        }
    }
}
