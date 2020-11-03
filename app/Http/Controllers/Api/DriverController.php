<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\DriverCancelTrip;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class DriverController extends Controller
{
    public function getTrips(Request $request)
    {
        $langData = trans('api_driver');
        $inputs = $request->all();
        $rules = [
            'user_id' => 'required',
        ];
        $message = [
            'user_id.required' => $langData['user_id'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        //==============User Check==============
        $UserStaust = User::where('role', '3')->where('id', $inputs['user_id'])->first();
        if ($UserStaust) {
            $Trip = Trip::where('driver_id', $inputs['user_id'])->orderBy('trip_id', 'desc')->get();
            if ($Trip) {
                return response(['status' => true, 'message' => $langData['record_found'], 'data' => $Trip]);
            } else {
                return response(['status' => false, 'message' => $langData['record_not_found']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function tripDetail(Request $request)
    {
        $langData = trans('api_user');
        $inputs = $request->all();
        $rules = [
            'user_id' => 'required',
            'trip_id' => 'required',
        ];
        $message = [
            'user_id.required' => $langData['user_id'],
            'trip_id.required' => $langData['trip_id'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        //==============User Check==============
        $UserStaust = User::where('role', '3')->where('id', $inputs['user_id'])->first();

        if ($UserStaust) {
            $Commission = Commission::where('type', '1')->first();
            if ($Commission) {
                $Trip = DB::table('trips as t')
                    ->select('t.*', 'u.user_name', 'u.phone_number', 'u.profile_image', 'u.lat as user_lat', 'u.lng as user_lng', 'd.lat as driver_lat', 'd.lng as driver_lng', 'd.vehicle_number', 'd.vehicle_color', 'd.vehicle_registration_number', 'd.licence_number')
                    ->leftJoin('users as u', 't.user_id', '=', 'u.id')
                    ->leftJoin('users as d', 't.driver_id', '=', 'd.id')
                    ->where('t.trip_id', $inputs['trip_id'])
                    ->first();
                if ($Trip) {
                    $Trip->profile_image = ImageHelper::getProfileImage($Trip->profile_image);
                    $commission_price = $Commission->price;
                    $Trip->taxes = $commission_price;
                    $Trip->ride_fire = $commission_price + $Trip->charge;
                    $Trip->rating = '4';
                    return response(['status' => true, 'message' => $langData['record_found'], 'data' => $Trip]);
                } else {
                    return response(['status' => false, 'message' => $langData['record_not_found']]);
                }
            } else {
                return response(['status' => false, 'message' => $langData['commission_not_found']]);
            }
        } else {
            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function tripStatusChange(Request $request)
    {
        $langData = trans('api_driver');
        $inputs = $request->all();
        $rules = [
            'user_id' => 'required',
            'trip_id' => 'required',
            'status' => 'required',

        ];
        $message = [
            'user_id.required' => $langData['user_id'],
            'trip_id.required' => $langData['trip_id'],
            'status.required' => $langData['status'],

        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        //==============User Check==============
        $UserStaust = User::where('role', '3')->where('id', $inputs['user_id'])->first();
        if ($UserStaust) {
            $Trip = Trip::where('trip_id', $inputs['trip_id'])->first();
            if ($Trip) {
                if ($inputs['status'] == '1') {
                    $Trip->driver_id = $inputs['user_id'];
                }
                $Trip->status = $inputs['status'];
                if (!empty($inputs['reason'])) {
                    $Trip->reason = $inputs['reason'];
                    $Trip->reason_description = $inputs['reason_description'];
                }
                if (!empty($inputs['otp'])) {
                    $Trip->otp_verify = '1';
                }
                if ($Trip->save()) {
                    $user_id = $Trip->user_id;
                    $User = User::select('id', 'lat', 'lng', 'notification_status', 'device_type', 'device_token', 'language')->where('role', '2')->where('active_status', '1')->where('id', $user_id)->first();
                    if ($Trip->status == '1') {
                        if ($User->language == 'en') {
                            $title = 'Accepted Ride';
                            $body = 'Your rides accepted by ' . $UserStaust->user_name;
                            $notification_key = 'Accepted_Ride';
                        } elseif ($User->language == 'es') {
                            $title = 'Paseo aceptado';
                            $body = 'Tus paseos aceptados por ' . $UserStaust->user_name;
                            $notification_key = 'Accepted_Ride';
                        } else {
                            $title = 'Paseo aceptado';
                            $body = 'Your rides accepted by ' . $UserStaust->user_name;
                            $notification_key = 'Accepted_Ride';
                        }
                    } elseif ($Trip->status == '2') {
                        if ($User->language == 'en') {
                            $title = 'Cancel Ride';
                            $body = 'Your rides cancel by ' . $UserStaust->user_name;
                            $notification_key = 'Cancel_Ride';
                        } elseif ($User->language == 'es') {
                            $title = 'Cancelar viaje';
                            $body = 'Sus viajes se cancelan por' . $UserStaust->user_name;
                            $notification_key = 'Cancel_Ride';
                        } else {
                            $title = 'Cancel Ride';
                            $body = 'Your rides cancel by ' . $UserStaust->user_name;
                            $notification_key = 'Cancel_Ride';
                        }

                    } elseif ($Trip->status == '4') {
                        if ($User->language == 'en') {
                            $title = 'Otp Verified';
                            $body = 'Your rides otp verified by ' . $UserStaust->user_name;
                            $notification_key = 'Otp_Verified';
                        } elseif ($User->language == 'es') {
                            $title = 'Otp verificado';
                            $body = 'Tus viajes otp verificados por ' . $UserStaust->user_name;
                            $notification_key = 'Otp_Verified';
                        } else {
                            $title = 'Otp Verified';
                            $body = 'Your rides otp verified by ' . $UserStaust->user_name;
                            $notification_key = 'Otp_Verified';
                        }

                    } elseif ($Trip->status == '5') {
                        if ($User->language == 'en') {
                            $title = 'Completed Ride';
                            $body = 'Your rides completed by ' . $UserStaust->user_name;
                            $notification_key = 'Complete_Ride';
                        } elseif ($User->language == 'es') {
                            $title = 'Paseo completado';
                            $body = 'Tus paseos completados por' . $UserStaust->user_name;
                            $notification_key = 'Complete_Ride';
                        } else {
                            $title = 'Completed Ride';
                            $body = 'Your rides completed by ' . $UserStaust->user_name;
                            $notification_key = 'Complete_Ride';
                        }

                    } else {
                        if ($User->language == 'en') {
                            $title = 'Cancel Ride';
                            $body = 'Your rides cancel by ' . $UserStaust->user_name;
                            $notification_key = 'Cancel_Ride';
                        } elseif ($User->language == 'es') {
                            $title = 'Cancelar viaje';
                            $body = 'Sus viajes se cancelan por ' . $UserStaust->user_name;
                            $notification_key = 'Cancel_Ride';
                        } else {
                            $title = 'Cancel Ride';
                            $body = 'Your rides cancel by ' . $UserStaust->user_name;
                            $notification_key = 'Cancel_Ride';
                        }
                    }

                    if ($User) {
                        if ($User->notification_status == '1') {
                            if ($User->device_type == 'android') {
                                $notificationData = [
                                    'key' => $notification_key,
                                    'title' => $title,
                                    'body' => $body,
                                    'trip_id' => $Trip->trip_id,
                                    'status' => $Trip->status,
                                    'payment_method' => $Trip->payment_method,
                                    'sender_id' => $UserStaust->id,
                                    'receiver_id' => $User->id,
                                ];

                                $Notification = new Notification();
                                $Notification->sender_id = $UserStaust->id;
                                $Notification->receiver_id = $User->id;
                                $Notification->title = $title;
                                $Notification->message = $body;
                                $Notification->sender_id = $UserStaust->id;
                                $Notification->json_data = json_encode($notificationData);
                                $Notification->save();

                                $device_token = [$User->device_token];
                                $notificationStatus = ApiHelper::sendNotificationAndroid($device_token, $notificationData);
                            }
                        }
                    }
                    if ($Trip->status == '1') {

                        return response(['status' => true, 'message' => $langData['trip_accpted']]);
                    } elseif ($Trip->status == '2') {

                        return response(['status' => true, 'message' => $langData['trip_cancelled']]);
                    } elseif ($Trip->status == '4') {

                        return response(['status' => true, 'message' => $langData['otp_verify']]);
                    } elseif ($Trip->status == '5') {

                        return response(['status' => true, 'message' => $langData['trip_completed']]);
                    } else {

                        return response(['status' => true, 'message' => $langData['trip_other']]);
                    }
                } else {

                    return response(['status' => false, 'message' => $langData['trip_cancelled_error']]);
                }
            } else {

                return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
            }
        } else {

            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function getRequests(Request $request)
    {
        $langData = trans('api_driver');
        $inputs = $request->all();
        $rules = [
            'user_id' => 'required',
        ];
        $message = [
            'user_id.required' => $langData['user_id'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        //==============User Check==============
        $UserStaust = User::where('role', '3')->where('id', $inputs['user_id'])->first();
        if ($UserStaust) {
            $Trip = DB::table('trips as t')
                ->select('t.*', 'u.user_name', 'u.profile_image')
                ->join('users as u', 'u.id', '=', 't.user_id')
                ->leftJoin('driver_cancel_trips as dct', 'dct.trip_id', '=', 't.trip_id')
            //->where('dct.driver_id','!=',$UserStaust->id)
                ->whereNull('t.driver_id')
                ->whereNull('t.deleted_at')
                ->orderBy('t.trip_id', 'desc')
                ->get();

            if ($Trip->toArray()) {
                foreach ($Trip as $key1 => $value1) {
                    $value1->profile_image = ImageHelper::getProfileImage($value1->profile_image);
                    $lat1 = $value1->from_lat;
                    $lng1 = $value1->from_lng;
                    $lat2 = $UserStaust->lat;
                    $lng2 = $UserStaust->lng;
                    $unit = 'K';
                    $distance = ApiHelper::getDistance($lat1, $lng1, $lat2, $lng2, $unit);
                    $value1->distance = "$distance";
                    $created_at_new = strtotime("+15 minutes", strtotime($value1->created_at));
                    $value1->created_at_new = $created_at_new;

                    $value1->current_time = strtotime(date('Y-m-d h:i:s'));
                    $value1->current_time_1 = date('Y-m-d h:i:s');
                }

                $TripShow = [];
                //return $Trip;
                foreach ($Trip as $key2 => $value2) {
                    if (round($value2->distance, '0') <= '120') {
                        //echo date('Y-m-d h:i:s');
                        //echo $value2->created_at_new;
                        if ($value2->current_time <= $value2->created_at_new) {
                            array_push($TripShow, $value2);
                        }
                    }
                }
                //return $TripShow;
                if ($TripShow) {

                    return response(['status' => true, 'message' => $langData['record_found'], 'data' => $TripShow]);
                } else {

                    return response(['status' => false, 'message' => $langData['record_not_found']]);
                }

            } else {

                return response(['status' => false, 'message' => $langData['record_not_found']]);
            }
        } else {

            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }

    public function tripCancel(Request $request)
    {
        $langData = trans('api_user');
        $inputs = $request->all();
        $rules = [
            'user_id' => 'required',
            'trip_id' => 'required',
        ];
        $message = [
            'user_id.required' => $langData['user_id'],
            'trip_id.required' => $langData['trip_id'],
        ];

        $validator = Validator::make($inputs, $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response(['status' => false, 'message' => $errors[0]], 200);
        }
        //==============User Check==============
        $UserStaust = User::where('role', '3')->where('id', $inputs['user_id'])->first();
        if ($UserStaust) {
            $DriverCancelTrip = new DriverCancelTrip();
            $DriverCancelTrip->driver_id = $UserStaust->id;
            $DriverCancelTrip->trip_id = $inputs['trip_id'];
            if ($DriverCancelTrip->save()) {

                return response(['status' => true, 'message' => $langData['trip_cancelled']]);
            } else {

                return response(['status' => false, 'message' => $langData['trip_cancelled_error']]);
            }
        } else {

            return response(['status' => false, 'message' => $langData['user_id_not_exits']]);
        }
    }
}
