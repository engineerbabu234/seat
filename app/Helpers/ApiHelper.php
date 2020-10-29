<?php

namespace App\Helpers;

use Session;
use App;
use Carbon\Carbon;
use url;

class ApiHelper {
    public static function otpGenrator($number){ 
        $generator = "1357902468"; 
        $otp = ""; 
        for ($i = 1; $i <= $number; $i++) { 
            $otp .= substr($generator, (rand()%(strlen($generator))), 1); 
        }
        return $otp; 
    }

    public static function smsSendFunction($number,$otp,$message){
        $curl = curl_init();
        $api_key='24aec2f0-40cb-11ea-9fa5-0200cd936042';
        $url='http://2factor.in/API/V1/'.$api_key.'/SMS/'.$number.'/'.$otp.'/'.$message.'';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
        ),
        ));

        $response   = curl_exec($curl);
        $err         = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['status'=>false,'message'=>$err];
        } else {
            return ['status'=>true,'data'=>$response];
        }
    }

    public static function getDistance($lat1, $lon1, $lat2, $lon2, $unit) {
        $lat1 = (float) $lat1;
        $lon1 = (float) $lon1;
        $lat2 = (float) $lat2;
        $lon2 = (float) $lon2;
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                $miles=$miles * 1.609344;
                return round($miles,2);
            } else if ($unit == "N") {
                $miles=$miles * 0.8684;
                return round($miles,2);
            } else {
                return round($miles,2);
            }
        }
    }

    public static function sendNotificationAndroid($device_token,$data = array()){
        $registration_ids =  $device_token;
        $message = array(
            "message" => $data
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids'  => $registration_ids,
            'data'              => $message,
        );
        
        $headers = array(
            'Authorization: key=' . "AAAAZXFB65I:APA91bHhaEnhzMTw_z6C8vBABgnWo6qudsppjaC9VH4qm1mgNjTLvwBwA8orYHf9eD39xlut3k0cA-f2jpjg3mkoziMl-j7oVxDod798sc8zX3l0kEUazm3BjSYHR_PCNVzHj3Eae4uq",
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE){
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
    }
}


    