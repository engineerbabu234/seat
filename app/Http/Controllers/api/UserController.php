<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Trip;
use App\Models\Commission;
use App\Models\DriverRating;
use App\Models\VehicleTypeCharge;
use App\Models\Notification;
use App\Models\TransactionHistory;
use App\Http\Controllers\api\PaymentController;
use App\Helpers\ImageHelper;
use App\Helpers\ApiHelper;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use Hash;
use Redirect,Response,DB,Config;
use Datatables;

class UserController extends Controller{

    public function getNearestDriver(Request $request){
        $langData               = trans('api_user');
        $inputs                 = $request->all();
        $rules = [
            'user_id'          => 'required',
        ];

        $message = [
            'user_id.required' => $langData['user_id'],
        ];

        $validator = Validator::make($inputs, $rules,$message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }

        $userStatus=User::where('id',$inputs['user_id'])->first();
        if($userStatus){
            if($userStatus->lat){
                $lat1=$userStatus->lat;
            }
            if($userStatus->lat){
                $lng1=$userStatus->lng;
            }
            $drivers=User::select('id as user_id', 'user_name', 'email', 'phone_number', 'address', 'lat', 'lng', 'profile_image')
                                ->where('id','!=',$inputs['user_id'])
                                ->where('role','=','3')
                                ->get();
            foreach ($drivers as $key => $value) {
                $value->profile_image  = ImageHelper::getProfileImage($value->profile_image);
                if($value->lat){
                    $lat2=$value->lat;
                }
                if($value->lng){
                    $lng2=$value->lng;
                }
                $unit='K';
                $getDistance=ApiHelper::getDistance($lat1, $lng1, $lat2, $lng2, $unit);
                $value->distance=$getDistance;
            }

            $driversDataShow=[];
            foreach ($drivers as $key1 => $value2) {
                if(round($value2->distance,'0')<='2'){
                    array_push($driversDataShow,$value2);
                }
            }
           if($driversDataShow){
                return response(['status' => true , 'message' => $langData['record_found'], 'data'=>$driversDataShow]); 
           }else{
                return response(['status' => false , 'message' => $langData['record_not_found']]); 
           }
             
        }else{
            return response(['status' => false , 'message' => $langData['user_id_not_exits']]);
        }
    } 

    public function GetDrivingDistance(Request $request){
        $langData                       = trans('api_user');
        $inputs                         = $request->all();
        $rules = [
            'from_lat'                  => 'required',
            'from_lng'                  => 'required',
            'to_lat'                    => 'required',
            'to_lng'                    => 'required',
            'vehicle_type_id'           => 'required',
        ];

        $message = [
            'from_lat.required'         => $langData['from_lat'],
            'from_lng.required'         => $langData['from_lng'],
            'to_lat.required'           => $langData['to_lat'],
            'to_lng.required'           => $langData['to_lng'],
            'to_lng.required'           => $langData['to_lng'],
            'vehicle_type_id'          => $langData['vehicle_type_id'],
        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        $lat1   = $inputs['from_lat'];
        $long1  = $inputs['from_lng'];
        $lat2   = $inputs['to_lat'];
        $long2  = $inputs['to_lng'];

        
        $url    = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyA3T8_AmenL4X39_bh9-JgT0v2bXR8QnoQ";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        if($response_a){
            $data=[];
            if($response_a['status']=='OK'){
                $dist                   = $response_a['rows'][0]['elements'][0]['distance']['text'];
                $time                   = $response_a['rows'][0]['elements'][0]['duration']['text'];
                $dist_val               = $response_a['rows'][0]['elements'][0]['distance']['value'];
                $time_val               = $response_a['rows'][0]['elements'][0]['duration']['value'];
                $data['distance']       = $dist;
                $data['time']           = $time;
                $dist_val = round($dist_val/1000,'0');
                
                $charge=0;
                for ($i=1; $i <= $dist_val ; $i++) { 
                   if($i==1){
                        $VehicleTypeCharge=VehicleTypeCharge::where('charge_type','1')->where('vehicle_type_id',$inputs['vehicle_type_id'])->first();
                        $charge+=$VehicleTypeCharge->trip_charge;
                   }else{
                        $VehicleTypeCharge=VehicleTypeCharge::where('charge_type','2')->where('vehicle_type_id',$inputs['vehicle_type_id'])->first();
                        $charge+=$VehicleTypeCharge->trip_charge;
                   }
                }

                $price                  = $charge;
                $data['distance_val']   = $dist_val;
                $data['time_val']       = round($time_val/60,'0');
                $data['price']          = round($price,'0');
                return response(['status' => true , 'message' => $langData['record_found'] ,'data'=> $data]);
            }else{
                return response(['status' => false , 'message' => 'record_not_found']);
            }
        }else{
            return response(['status' => false , 'message' => $langData['record_not_found'] ]);
        }
    }
    

    public function postTrip(Request $request){
        $langData                       = trans('api_user');
        $inputs                         = $request->all();
        $rules = [
            'user_id'                   => 'required',
            //'from'                      => 'required',
            'from_lat'                  => 'required',
            'from_lng'                  => 'required',
            'to'                        => 'required',
            'to_lat'                    => 'required',
            'to_lng'                    => 'required',
            'charge'                    => 'required',
            'payment_method'            => 'required',
        ];
        $message = [
            'user_id.required'          => $langData['user_id'],
            //'from.required'             => $langData['from'],
            'from_lat.required'         => $langData['from_lat'],
            'from_lng.required'         => $langData['from_lng'],
            'to.required'               => $langData['to'],
            'to_lat.required'           => $langData['to_lat'],
            'to_lng.required'           => $langData['to_lng'],
            'charge.required'           => $langData['charge'],
            'payment_method.required'   => $langData['payment_method'],

        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        //==============User Check==============
        $UserStaust = User::where('role','2')->where('id',$inputs['user_id'])->first();
        if($UserStaust){
            if($inputs['from']){
                $from=$inputs['from'];
            }else{
                $geolocation = $inputs['from_lat'].','.$inputs['from_lng'];
                $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false&key=AIzaSyA3T8_AmenL4X39_bh9-JgT0v2bXR8QnoQ'; 
                $file_contents = file_get_contents($request);
                $json_decode = json_decode($file_contents);
                if($json_decode->results[0]->formatted_address){
                    $from=$json_decode->results[0]->formatted_address;
                }else{
                    $from='';
                }
            }
            
            $otp = ApiHelper::otpGenrator(4);
            $trip_reference_id='CM'.time().ApiHelper::otpGenrator(4);
            $Trip                       = new Trip;
            $Trip->trip_reference_id    = $trip_reference_id;
            $Trip->user_id              = $inputs['user_id'];
            $Trip->from                 = $from;
            $Trip->from_lat             = $inputs['from_lat'];
            $Trip->from_lng             = $inputs['from_lng'];
            $Trip->to                   = $inputs['to'];
            $Trip->to_lat               = $inputs['to_lat'];
            $Trip->to_lng               = $inputs['to_lng'];
            $Trip->charge               = $inputs['charge'];
            $Trip->otp                  = $otp;
            $Trip->payment_method       = $inputs['payment_method'];
            if($Trip->save()){

                $device_tokens=[];
                $Drivers = User::select('id' , 'lat' , 'lng' , 'notification_status' , 'device_type' , 'device_token', 'language')->where('role','3')->where('active_status','1')->where('approve_status','1')->get();
                $device_tokens=[];
                if($Drivers){
                    $notificationDataInsert=[];
                    foreach ($Drivers as $key1 => $value1) {
                        $lat1 = $value1->lat;
                        $lng1 = $value1->lng;
                        $lat2 = $UserStaust->lat;
                        $lng2 = $UserStaust->lng;
                        $unit = 'K';
                        $distance=ApiHelper::getDistance($lat1, $lng1, $lat2, $lng2, $unit);

                        $value1->distance="$distance";
                    }

                   
                    foreach ($Drivers as $key2 => $value2) {
                        if(round($value2->distance,'0') <='120'){
                            if($value2->notification_status == '1'){
                                if($value2->language=='en'){
                                    $title              = 'New Rides';
                                    $body               = 'New rides post by '.$UserStaust->user_name;
                                    $notification_key   = 'New_Rides';
                                }elseif($value2->language=='es'){
                                    $title              = 'Nuevos paseos';
                                    $body               = 'Nuevos paseos publicados por'.$UserStaust->user_name;
                                    $notification_key   = 'New_Rides';
                                }else{
                                    $title              = 'New Rides';
                                    $body               = 'New rides post by '.$UserStaust->user_name;
                                    $notification_key   = 'New_Rides'; 
                                }
                
                                if($value2->device_type== 'android'){
                                    $notificationData = [
                                        'key'             => $notification_key,
                                        'title'           => $title,
                                        'body'            => $body,
                                        'trip_id'         => $Trip->trip_id,
                                        'sender_id'       => $value2->id,
                                        'receiver_id'     => $UserStaust->id,
                                    ];

                                    $tmp                = [];
                                    $tmp['sender_id']   = $UserStaust->id;
                                    $tmp['receiver_id'] = $value2->id;
                                    $tmp['title']       = $title;
                                    $tmp['message']     = $body;
                                    $tmp['json_data']   = json_encode($notificationData);
                                    array_push($notificationDataInsert, $tmp);

                                    $device_token=[$value2->device_token];
                                    $notificationStatus=ApiHelper::sendNotificationAndroid($device_token,$notificationData);
                                }
                            }
                        }
                    }
                    Notification::insert($notificationDataInsert); // Eloquent approach
                }
                return response(['status' => true , 'message' => $langData['post_trip_success'],'data'=>$Trip]);
            }else{
                return response(['status' => false , 'message' => $langData['post_trip_error'] ]);
            }
        }else{
           return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]); 
        }
    }

    public function tripStatusChange(Request $request){
        $langData   = trans('api_user');
        $inputs = $request->all();
        $rules = [
            'user_id'            => 'required',
            'trip_id'            => 'required',
            'status'             => 'required',
            
        ];
         $message = [
            'user_id.required'   => $langData['user_id'],
            'trip_id.required'   => $langData['trip_id'],
            'status.required'    => $langData['status'],

        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        //==============User Check==============
        $UserStaust = User::where('role','2')->where('id',$inputs['user_id'])->first();
        if($UserStaust){
            $Trip=Trip::where('trip_id',$inputs['trip_id'])->first();
            if($Trip){
                $Trip->status                   = $inputs['status'];
                if(!empty($inputs['reason'])){
                    $Trip->reason               = $inputs['reason'];
                    $Trip->reason_description   = $inputs['reason_description'];
                }
                if($Trip->save()){
                    $driver_id = $Trip->driver_id;
                    $Driver = User::select('id' , 'lat' , 'lng' , 'notification_status' , 'device_type' , 'device_token', 'language')->where('role','3')->where('active_status','1')->where('approve_status','1')->where('id',$driver_id)->first();
                    if($Trip->status=='3'){
                        if($Driver->language=='en'){
                            $title              = 'Cancel Rides';
                            $body               = 'Your rides cancel by '.$UserStaust->user_name;
                            $notification_key   = 'Cancel_Ride';
                        }elseif($Driver->language=='es'){
                            $title              = 'Cancelar viajes';
                            $body               = 'Tus viajes se cancelan por '.$UserStaust->user_name;
                            $notification_key   = 'Cancel_Ride';
                        }else{
                            $title              = 'Cancel Rides';
                            $body               = 'Your rides cancel by '.$UserStaust->user_name;
                            $notification_key   = 'Cancel_Ride';
                        }
                    }else{

                    }
                    if($Driver){
                        if($Driver->notification_status == '1'){
                            if($Driver->device_type== 'android'){
                                $notificationData = [
                                    'key'             => $notification_key,
                                    'title'           => $title,
                                    'body'            => $body,
                                    'trip_id'         => $Trip->trip_id,
                                    'sender_id'       => $Driver->id,
                                    'receiver_id'     => $UserStaust->id,
                                ];

                                $Notification = new Notification();
                                $Notification->sender_id=$UserStaust->id;
                                $Notification->receiver_id=$Driver->id;
                                $Notification->title=$body;
                                $Notification->sender_id=$UserStaust->id;
                                $Notification->json_data=json_encode($notificationData);
                                $Notification->save();

                                $device_token=[$Driver->device_token];
                                $notificationStatus=ApiHelper::sendNotificationAndroid($device_token,$notificationData);
                            }
                        }
                    }

                    if($Trip->status=='3'){
                        return response(['status' => true , 'message' => $langData['trip_cancelled'] ]);
                    }else{
                        return response(['status' => true , 'message' => $langData['trip_other'] ]);
                    } 
                }else{
                    return response(['status' => false , 'message' => $langData['trip_cancelled_error'] ]);
                }
            }else{
                return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]);
            }
        }else{
            return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]);  
        }
    }

    public function getTrips(Request $request){
        $langData               = trans('api_user');
        $inputs                 = $request->all();
        $rules = [
            'user_id'           => 'required',
        ];
         $message = [
            'user_id.required'  => $langData['user_id'],
        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        //==============User Check==============
        $UserStaust = User::where('role','2')->where('id',$inputs['user_id'])->first();
        if($UserStaust){
            $Trip=Trip::where('user_id',$inputs['user_id'])->orderBy('trip_id','desc')->get();
            if($Trip){
                return response(['status' => true , 'message' => $langData['record_found'],'data'=>$Trip]);
            }else{
                return response(['status' => false , 'message' => $langData['record_not_found'] ]);
            }
        }else{
            return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]);  
        }
    }

    public function tripDetail(Request $request){
        $langData   = trans('api_user');
        $inputs = $request->all();
        $rules = [
            'user_id'            => 'required',
            'trip_id'            => 'required',
        ];
         $message = [
            'user_id.required'   => $langData['user_id'],
            'trip_id.required'   => $langData['trip_id'],
        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        //==============User Check==============
        $UserStaust = User::where('role','2')->where('id',$inputs['user_id'])->first();
        if($UserStaust){
            $Commission= Commission::where('type','1')->first();
            if($Commission){
                $Trip=DB::table('trips as t')
                ->select('t.*', 'u.lat as user_lat', 'u.lng as user_lng', 'd.lat as driver_lat', 'd.lng as driver_lng', 'd.user_name as driver_user_name', 'd.phone_number as driver_phone_number', 'd.profile_image as driver_profile_image', 'd.vehicle_number', 'd.vehicle_color', 'd.vehicle_registration_number', 'd.licence_number')
                ->leftJoin('users as d','t.driver_id','=','d.id')
                ->leftJoin('users as u','t.user_id','=','u.id')
                ->where('trip_id',$inputs['trip_id'])
                ->first();
                if($Trip){
                    $Trip->driver_profile_image=ImageHelper::getProfileImage($Trip->driver_profile_image);
                    $commission_price   = $Commission->price;
                    $Trip->taxes        = $commission_price;
                    $Trip->ride_fire    = $commission_price+$Trip->charge;
                    $Trip->rating       = '4';
                    return response(['status' => true , 'message' => $langData['record_found'],'data'=>$Trip]);
                }else{
                    return response(['status' => false , 'message' => $langData['record_not_found'] ]);
                }
            }else{
                return response(['status' => false , 'message' => $langData['commission_not_found'] ]);
            }
        }else{
            return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]);  
        }
    }


    
    public function driverRating(Request $request){
        $langData   = trans('api_user');
        $inputs = $request->all();
        $rules = [
            'user_id'            => 'required',
            'trip_id'            => 'required',
            'rating'             => 'required',
            'review'             => 'required',
        ];
         $message = [
            'user_id.required'   => $langData['user_id'],
            'trip_id.required'   => $langData['trip_id'],
            'rating.required'    => $langData['rating'],
            'review.required'    => $langData['review'],
        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        //==============User Check==============
        $UserStaust = User::where('role','2')->where('id',$inputs['user_id'])->first();
        if($UserStaust){
            $Trip=Trip::where('trip_id',$inputs['trip_id'])->first();
            if($Trip){
                $DriverRating = DriverRating::where('trip_id',$Trip->trip_id)->where('driver_id',$Trip->driver_id)->where('trip_id',$Trip->trip_id)->first();
                if(!$DriverRating){
                    $DriverRating               = new DriverRating();
                    $DriverRating->user_id      = $Trip->user_id;
                    $DriverRating->driver_id    = $Trip->driver_id;
                    $DriverRating->trip_id      = $Trip->trip_id;
                    $DriverRating->rating       = $inputs['rating'];
                    $DriverRating->review       = $inputs['review'];
                    if($DriverRating->save()){
                        $Trip->status="6";
                        $Trip->update();
                        return response(['status' => true , 'message' => $langData['rating_success'] ]);
                    }else{
                        return response(['status' => false , 'message' => $langData['rating_error'] ]);
                    }
                }else{
                    return response(['status' => false , 'message' => $langData['already_rated'] ]);
                }
            }else{
                return response(['status' => false , 'message' => $langData['trip_not_found'] ]);
            }
        }else{
            return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]);  
        }
    }

    public function tripPayment(Request $request){
        $langData   = trans('api_user');
        $inputs = $request->all();

        $rules = [
            'user_id'            => 'required',
            'trip_id'            => 'required',
        ];

        $message = [
            'user_id.required'   => $langData['user_id'],
            'trip_id.required'   => $langData['trip_id'],
        ];

        $validator = Validator::make($inputs, $rules , $message);
        if ($validator->fails()) {
            $errors =  $validator->errors()->all();
            return response(['status' => false , 'message' => $errors[0]] , 200);              
        }
        //==============User Check==============
        $UserStaust = User::where('role','2')->where('id',$inputs['user_id'])->first();
        if($UserStaust){
            $Trip=Trip::where('trip_id',$inputs['trip_id'])->first();
            if($Trip){
                if($Trip->payment_method=='1'){//1 is Cod payment
                    $Driver = User::select('id' , 'lat' , 'lng' , 'notification_status' , 'device_type' , 'device_token', 'language')->where('role','3')->where('active_status','1')->where('approve_status','1')->where('id',$Trip->driver_id)->first();
                    if($Driver){
                        if($Driver->language=='en'){
                            $title              = 'Payment Successfully';
                            $body               = 'Your payment successfully by '.$UserStaust->user_name;
                            $notification_key   = 'Payment_Success';
                        }elseif($Driver->language=='es'){
                            $title              = 'Pago exitoso';
                            $body               = 'Su pago con éxito por '.$UserStaust->user_name;
                            $notification_key   = 'Payment_Success';
                        }else{
                            $title              = 'Payment Successfully';
                            $body               = 'Your payment successfully by '.$UserStaust->user_name;
                            $notification_key   = 'Payment_Success';
                        }
                    
                        if($Driver->notification_status == '1'){
                            if($Driver->device_type== 'android'){
                                $notificationData = [
                                    'key'             => $notification_key,
                                    'title'           => $title,
                                    'body'            => $body,
                                    'trip_id'         => $Trip->trip_id,
                                    'sender_id'       => $UserStaust->id,
                                    'receiver_id'     => $Driver->id,
                                ];

                                $Notification = new Notification();
                                $Notification->sender_id=$UserStaust->id;
                                $Notification->receiver_id=$Driver->id;
                                $Notification->title=$body;
                                $Notification->sender_id=$UserStaust->id;
                                $Notification->json_data=json_encode($notificationData);
                                $Notification->save();

                                $device_token=[$Driver->device_token];
                                $notificationStatus=ApiHelper::sendNotificationAndroid($device_token,$notificationData);
                            }
                        }
                    }
                    return response(['status' => true , 'message' => $langData['trip_payment_success']]);

                }elseif($Trip->payment_method=='2'){//2 is card payment
                    $BrainTree                      = new PaymentController();
                    $data['token']                  = $BrainTree->getBraintreeToken();
                    $data['amount']                 = $Trip->charge;
                    $data['for_payment']            = '1';
                    $data['trip_id']                = $Trip->trip_id;
                    $data['user_id']                = $UserStaust->id;

                    return view('paypal.payment',compact('data'));                    
                }else{
                    return response(['status' => false , 'message' => $langData['payment_method_not_found'] ]);
                }
            }else{
                return response(['status' => false , 'message' => $langData['trip_not_found'] ]);
            }
        }else{
            return response(['status' => false , 'message' => $langData['user_id_not_exits'] ]);
        }
    }

    public function cardPayment(Request $request){
        $inputs = $request->all();
        $BrainTree = new PaymentController();

        $response  = $BrainTree->cardPayment([
            'amount'  => $inputs['amount'],
            'nonce'   => $inputs['nonce'],
        ]);

        if($response['status']){
            $insertData = array();
            $TransactionHistory                         = new TransactionHistory();
            $TransactionHistory->user_id                = $inputs['user_id'];
            $TransactionHistory->paypal_charge          = '50';
            $TransactionHistory->payment_method         = '2';//card payment
            $TransactionHistory->payment_type           = '1';
            $TransactionHistory->transaction_id         = $response['data']['id'];
            $TransactionHistory->currency               = $response['data']['currency_iso_Code'];
            $TransactionHistory->amount                 = $response['data']['amount'];
            $TransactionHistory->transaction_status     = '1';

            if($inputs['for_payment']=='1'){

                $TransactionHistory->trip_id               = $inputs['trip_id'];

            }else{

            }
            if($TransactionHistory->save()){
                if($inputs['for_payment']=='1'){
                    $Trip                  = Trip::find($inputs['trip_id']);
                    $Trip->payment_status  = '1';
                    $Trip->update();
                    $UserStaust = User::select('id','user_name')->where('role','2')->where('active_status','1')->where('id',$Trip->user_id)->first();
                    
                    $Driver = User::select('id' , 'lat' , 'lng' , 'notification_status' , 'device_type' , 'device_token', 'language')->where('role','3')->where('active_status','1')->where('approve_status','1')->where('id',$Trip->driver_id)->first();
                    if($Driver){
                        if($Driver->language=='en'){
                            $title              = 'Payment Successfully';
                            $body               = 'Your payment successfully by '.$UserStaust->user_name;
                            $notification_key   = 'Payment_Success';
                        }elseif($Driver->language=='es'){
                            $title              = 'Pago exitoso';
                            $body               = 'Su pago con éxito por '.$UserStaust->user_name;
                            $notification_key   = 'Payment_Success';
                        }else{
                            $title              = 'Payment Successfully';
                            $body               = 'Your payment successfully by '.$UserStaust->user_name;
                            $notification_key   = 'Payment_Success';
                        }
                        if($Driver->notification_status == '1'){
                            if($Driver->device_type== 'android'){
                                $notificationData = [
                                    'key'             => $notification_key,
                                    'title'           => $title,
                                    'body'            => $body,
                                    'trip_id'         => $Trip->trip_id,
                                    'sender_id'       => $UserStaust->id,
                                    'receiver_id'     => $Driver->id,
                                ];

                                $Notification = new Notification();
                                $Notification->sender_id=$UserStaust->id;
                                $Notification->receiver_id=$Driver->id;
                                $Notification->title=$title;
                                $Notification->message=$body;
                                $Notification->json_data=json_encode($notificationData);
                                $Notification->save();

                                $device_token=[$Driver->device_token];
                                $notificationStatus=ApiHelper::sendNotificationAndroid($device_token,$notificationData);
                            }
                        }
                    }
                }else{

                }
                return view('paypal.success');
            }else{
                return view('paypal.cancel');
            }
        }else{
            return view('paypal.cancel');
        }
    }
}