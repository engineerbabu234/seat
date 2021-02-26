<?php

namespace App\Helpers;

use App;
use App\Models\User;

class ApiHelper
{
    /**
     * [otpGenrator description]
     * @param  [type] $number [description]
     * @return [type]         [description]
     */
    public static function otpGenrator($number)
    {
        $generator = "1357902468";
        $otp = "";
        for ($i = 1; $i <= $number; $i++) {
            $otp .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $otp;
    }

    /**
     * [generateRandomString description]
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public static function generateRandomString($length = 25)
    {
        $characters = '0123456789@#&!ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * [getUniqueFileName description]
     * @return [type] [description]
     */
    public static function getUniqueFileName($length = 24)
    {
        return substr(sha1(microtime()), 0, $length);
    }

    /**
     * [smsSendFunction description]
     * @param  [type] $number  [description]
     * @param  [type] $otp     [description]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public static function smsSendFunction($number, $otp, $message)
    {
        $curl = curl_init();
        $api_key = '24aec2f0-40cb-11ea-9fa5-0200cd936042';
        $url = 'http://2factor.in/API/V1/' . $api_key . '/SMS/' . $number . '/' . $otp . '/' . $message . '';

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
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['status' => false, 'message' => $err];
        } else {
            return ['status' => true, 'data' => $response];
        }
    }

    /**
     * [getDistance description]
     * @param  [type] $lat1 [description]
     * @param  [type] $lon1 [description]
     * @param  [type] $lat2 [description]
     * @param  [type] $lon2 [description]
     * @param  [type] $unit [description]
     * @return [type]       [description]
     */
    public static function getDistance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $lat1 = (float) $lat1;
        $lon1 = (float) $lon1;
        $lat2 = (float) $lat2;
        $lon2 = (float) $lon2;
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                $miles = $miles * 1.609344;
                return round($miles, 2);
            } else if ($unit == "N") {
                $miles = $miles * 0.8684;
                return round($miles, 2);
            } else {
                return round($miles, 2);
            }
        }
    }

    /**
     * [sendNotificationAndroid description]
     * @param  [type] $device_token [description]
     * @param  array  $data         [description]
     * @return [type]               [description]
     */
    public static function sendNotificationAndroid($device_token, $data = array())
    {
        $registration_ids = $device_token;
        $message = array(
            "message" => $data,
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . "AAAAZXFB65I:APA91bHhaEnhzMTw_z6C8vBABgnWo6qudsppjaC9VH4qm1mgNjTLvwBwA8orYHf9eD39xlut3k0cA-f2jpjg3mkoziMl-j7oVxDod798sc8zX3l0kEUazm3BjSYHR_PCNVzHj3Eae4uq",
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
    }

    /**
     * [convert_new_date description]
     * @param  [type] $expired_option [day month week]
     * @param  [type] $expired_value [value]
     */
    public static function convert_new_date($expired_option, $expired_value)
    {
        if ($expired_option == 'Day') {
            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' days'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 day'));
            }
            return date('Y-m-d', strtotime($expired_date));

        } else if ($expired_option == 'Month') {

            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' months'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 month'));
            }
            return date('Y-m-d', strtotime($expired_date));

        } else if ($expired_option == 'Week') {
            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' weeks'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 week'));
            }
            return date('Y-m-d', strtotime($expired_date));
        }

    }

    /**
     * [timezone description]
     * @param  [type] $expired_option [timezone name]
     * @param  [type] $expired_value [value]
     */

    public static function timezone_list()
    {
        $timezones = array(
            'Pacific/Midway' => "(GMT-11:00) Midway Island",
            'US/Samoa' => "(GMT-11:00) Samoa",
            'US/Hawaii' => "(GMT-10:00) Hawaii",
            'US/Alaska' => "(GMT-09:00) Alaska",
            'US/Pacific' => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana' => "(GMT-08:00) Tijuana",
            'US/Arizona' => "(GMT-07:00) Arizona",
            'US/Mountain' => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua' => "(GMT-07:00) Chihuahua",
            'America/Mazatlan' => "(GMT-07:00) Mazatlan",
            'America/Mexico_City' => "(GMT-06:00) Mexico City",
            'America/Monterrey' => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
            'US/Central' => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern' => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
            'America/Bogota' => "(GMT-05:00) Bogota",
            'America/Lima' => "(GMT-05:00) Lima",
            'America/Caracas' => "(GMT-04:30) Caracas",
            'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz' => "(GMT-04:00) La Paz",
            'America/Santiago' => "(GMT-04:00) Santiago",
            'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            'Greenland' => "(GMT-03:00) Greenland",
            'Atlantic/Stanley' => "(GMT-02:00) Stanley",
            'Atlantic/Azores' => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca' => "(GMT) Casablanca",
            'Europe/Dublin' => "(GMT) Dublin",
            'Europe/Lisbon' => "(GMT) Lisbon",
            'Europe/London' => "(GMT) London",
            'Africa/Monrovia' => "(GMT) Monrovia",
            'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade' => "(GMT+01:00) Belgrade",
            'Europe/Berlin' => "(GMT+01:00) Berlin",
            'Europe/Bratislava' => "(GMT+01:00) Bratislava",
            'Europe/Brussels' => "(GMT+01:00) Brussels",
            'Europe/Budapest' => "(GMT+01:00) Budapest",
            'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
            'Europe/Madrid' => "(GMT+01:00) Madrid",
            'Europe/Paris' => "(GMT+01:00) Paris",
            'Europe/Prague' => "(GMT+01:00) Prague",
            'Europe/Rome' => "(GMT+01:00) Rome",
            'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
            'Europe/Skopje' => "(GMT+01:00) Skopje",
            'Europe/Stockholm' => "(GMT+01:00) Stockholm",
            'Europe/Vienna' => "(GMT+01:00) Vienna",
            'Europe/Warsaw' => "(GMT+01:00) Warsaw",
            'Europe/Zagreb' => "(GMT+01:00) Zagreb",
            'Europe/Athens' => "(GMT+02:00) Athens",
            'Europe/Bucharest' => "(GMT+02:00) Bucharest",
            'Africa/Cairo' => "(GMT+02:00) Cairo",
            'Africa/Harare' => "(GMT+02:00) Harare",
            'Europe/Helsinki' => "(GMT+02:00) Helsinki",
            'Europe/Istanbul' => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
            'Europe/Kiev' => "(GMT+02:00) Kyiv",
            'Europe/Minsk' => "(GMT+02:00) Minsk",
            'Europe/Riga' => "(GMT+02:00) Riga",
            'Europe/Sofia' => "(GMT+02:00) Sofia",
            'Europe/Tallinn' => "(GMT+02:00) Tallinn",
            'Europe/Vilnius' => "(GMT+02:00) Vilnius",
            'Asia/Baghdad' => "(GMT+03:00) Baghdad",
            'Asia/Kuwait' => "(GMT+03:00) Kuwait",
            'Africa/Nairobi' => "(GMT+03:00) Nairobi",
            'Asia/Riyadh' => "(GMT+03:00) Riyadh",
            'Europe/Moscow' => "(GMT+03:00) Moscow",
            'Asia/Tehran' => "(GMT+03:30) Tehran",
            'Asia/Baku' => "(GMT+04:00) Baku",
            'Europe/Volgograd' => "(GMT+04:00) Volgograd",
            'Asia/Muscat' => "(GMT+04:00) Muscat",
            'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan' => "(GMT+04:00) Yerevan",
            'Asia/Kabul' => "(GMT+04:30) Kabul",
            'Asia/Karachi' => "(GMT+05:00) Karachi",
            'Asia/Tashkent' => "(GMT+05:00) Tashkent",
            'Asia/Kolkata' => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg' => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty' => "(GMT+06:00) Almaty",
            'Asia/Dhaka' => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk' => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok' => "(GMT+07:00) Bangkok",
            'Asia/Jakarta' => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk' => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing' => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth' => "(GMT+08:00) Perth",
            'Asia/Singapore' => "(GMT+08:00) Singapore",
            'Asia/Taipei' => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi' => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk' => "(GMT+09:00) Irkutsk",
            'Asia/Seoul' => "(GMT+09:00) Seoul",
            'Asia/Tokyo' => "(GMT+09:00) Tokyo",
            'Australia/Adelaide' => "(GMT+09:30) Adelaide",
            'Australia/Darwin' => "(GMT+09:30) Darwin",
            'Asia/Yakutsk' => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane' => "(GMT+10:00) Brisbane",
            'Australia/Canberra' => "(GMT+10:00) Canberra",
            'Pacific/Guam' => "(GMT+10:00) Guam",
            'Australia/Hobart' => "(GMT+10:00) Hobart",
            'Australia/Melbourne' => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney' => "(GMT+10:00) Sydney",
            'Asia/Vladivostok' => "(GMT+11:00) Vladivostok",
            'Asia/Magadan' => "(GMT+12:00) Magadan",
            'Pacific/Auckland' => "(GMT+12:00) Auckland",
            'Pacific/Fiji' => "(GMT+12:00) Fiji",
        );
        return $timezones;
    }

    public static function getModelPaymentType()
    {
        $userinfo = User::where('role', 1)->first();
        if (isset($userinfo) && $userinfo->timezone != '') {
            return $userinfo->timezone;
        } else {
            return 'Europe/Dublin';
        }
    }

    public static function makeDir($path, $isPermission = false)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            if ($isPermission) {
                chmod($path, 0777);
            }
        }

    }

    public static function get_api_teleconference()
    {
        return array('1' => 'Zoom', '2' => 'Teams', '3' => 'Google Meet', '4' => 'Blue Jeans', '5' => 'Goto Meeting', '6' => 'Webex');
    }

    public static function get_api_contract()
    {
        return array('1' => 'Docusign', '2' => 'PandaDoc', '3' => 'EverSign', '4' => 'SignRequest', '5' => 'AdobeSign');
    }

    public static function get_api_notification()
    {
        return array('1' => 'Twillo');
    }

    public static function get_api_type()
    {
        return array('1' => 'Teleconference', '2' => 'Contract', '3' => 'Notification');
    }

    public static function get_country_phone_prefix()
    {
        $prefix = [
            '44' => 'UK (+44)',
            '1' => 'USA (+1)',
            '213' => 'Algeria (+213)',
            '376' => 'Andorra (+376)',
            '244' => 'Angola (+244)',
            '1264' => 'Anguilla (+1264)',
            '1268' => 'Antigua & Barbuda (+1268)',
            '54' => 'Argentina (+54)',
            '374' => 'Armenia (+374)',
            '297' => 'Aruba (+297)',
            '61' => 'Australia (+61)',
            '43' => 'Austria (+43)',
            '994' => 'Azerbaijan (+994)',
            '1242' => 'Bahamas (+1242)',
            '973' => 'Bahrain (+973)',
            '880' => 'Bangladesh (+880)',
            '1246' => 'Barbados (+1246)',
            '375' => 'Belarus (+375)',
            '32' => 'Belgium (+32)',
            '501' => 'Belize (+501)',
            '229' => 'Benin (+229)',
            '1441' => 'Bermuda (+1441)',
            '975' => 'Bhutan (+975)',
            '591' => 'Bolivia (+591)',
            '387' => 'Bosnia Herzegovina (+387)',
            '267' => 'Botswana (+267)',
            '55' => 'Brazil (+55)',
            '673' => 'Brunei (+673)',
            '359' => 'Bulgaria (+359)',
            '226' => 'Burkina Faso (+226)',
            '257' => 'Burundi (+257)',
            '855' => 'Cambodia (+855)',
            '237' => 'Cameroon (+237)',
            '1' => 'Canada (+1)',
            '238' => 'Cape Verde Islands (+238)',
            '1345' => 'Cayman Islands (+1345)',
            '236' => 'Central African Republic (+236)',
            '56' => 'Chile (+56)',
            '86' => 'China (+86)',
            '57' => 'Colombia (+57)',
            '269' => 'Comoros (+269)',
            '242' => 'Congo (+242)',
            '682' => 'Cook Islands (+682)',
            '506' => 'Costa Rica (+506)',
            '385' => 'Croatia (+385)',
            '53' => 'Cuba (+53)',
            '90392' => 'Cyprus North (+90392)',
            '357' => 'Cyprus South (+357)',
            '42' => 'Czech Republic (+42)',
            '45' => 'Denmark (+45)',
            '253' => 'Djibouti (+253)',
            '1809' => 'Dominica (+1809)',
            '1809' => 'Dominican Republic (+1809)',
            '593' => 'Ecuador (+593)',
            '20' => 'Egypt (+20)',
            '503' => 'El Salvador (+503)',
            '240' => 'Equatorial Guinea (+240)',
            '291' => 'Eritrea (+291)',
            '372' => 'Estonia (+372)',
            '251' => 'Ethiopia (+251)',
            '500' => 'Falkland Islands (+500)',
            '298' => 'Faroe Islands (+298)',
            '679' => 'Fiji (+679)',
            '358' => 'Finland (+358)',
            '33' => 'France (+33)',
            '594' => 'French Guiana (+594)',
            '689' => 'French Polynesia (+689)',
            '241' => 'Gabon (+241)',
            '220' => 'Gambia (+220)',
            '7880' => 'Georgia (+7880)',
            '49' => 'Germany (+49)',
            '233' => 'Ghana (+233)',
            '350' => 'Gibraltar (+350)',
            '30' => 'Greece (+30)',
            '299' => 'Greenland (+299)',
            '1473' => 'Grenada (+1473)',
            '590' => 'Guadeloupe (+590)',
            '671' => 'Guam (+671)',
            '502' => 'Guatemala (+502)',
            '224' => 'Guinea (+224)',
            '245' => 'Guinea - Bissau (+245)',
            '592' => 'Guyana (+592)',
            '509' => 'Haiti (+509)',
            '504' => 'Honduras (+504)',
            '852' => 'Hong Kong (+852)',
            '36' => 'Hungary (+36)',
            '354' => 'Iceland (+354)',
            '91' => 'India (+91)',
            '62' => 'Indonesia (+62)',
            '98' => 'Iran (+98)',
            '964' => 'Iraq (+964)',
            '353' => 'Ireland (+353)',
            '972' => 'Israel (+972)',
            '39' => 'Italy (+39)',
            '1876' => 'Jamaica (+1876)',
            '81' => 'Japan (+81)',
            '962' => 'Jordan (+962)',
            '7' => 'Kazakhstan (+7)',
            '254' => 'Kenya (+254)',
            '686' => 'Kiribati (+686)',
            '850' => 'Korea North (+850)',
            '82' => 'Korea South (+82)',
            '965' => 'Kuwait (+965)',
            '996' => 'Kyrgyzstan (+996)',
            '856' => 'Laos (+856)',
            '371' => 'Latvia (+371)',
            '961' => 'Lebanon (+961)',
            '266' => 'Lesotho (+266)',
            '231' => 'Liberia (+231)',
            '218' => 'Libya (+218)',
            '417' => 'Liechtenstein (+417)',
            '370' => 'Lithuania (+370)',
            '352' => 'Luxembourg (+352)',
            '853' => 'Macao (+853)',
            '389' => 'Macedonia (+389)',
            '261' => 'Madagascar (+261)',
            '265' => 'Malawi (+265)',
            '60' => 'Malaysia (+60)',
            '960' => 'Maldives (+960)',
            '223' => 'Mali (+223)',
            '356' => 'Malta (+356)',
            '692' => 'Marshall Islands (+692)',
            '596' => 'Martinique (+596)',
            '222' => 'Mauritania (+222)',
            '269' => 'Mayotte (+269)',
            '52' => 'Mexico (+52)',
            '691' => 'Micronesia (+691)',
            '373' => 'Moldova (+373)',
            '377' => 'Monaco (+377)',
            '976' => 'Mongolia (+976)',
            '1664' => 'Montserrat (+1664)',
            '212' => 'Morocco (+212)',
            '258' => 'Mozambique (+258)',
            '95' => 'Myanmar (+95)',
            '264' => 'Namibia (+264)',
            '674' => 'Nauru (+674)',
            '977' => 'Nepal (+977)',
            '31' => 'Netherlands (+31)',
            '687' => 'New Caledonia (+687)',
            '64' => 'New Zealand (+64)',
            '505' => 'Nicaragua (+505)',
            '227' => 'Niger (+227)',
            '234' => 'Nigeria (+234)',
            '683' => 'Niue (+683)',
            '672' => 'Norfolk Islands (+672)',
            '670' => 'Northern Marianas (+670)',
            '47' => 'Norway (+47)',
            '968' => 'Oman (+968)',
            '680' => 'Palau (+680)',
            '507' => 'Panama (+507)',
            '675' => 'Papua New Guinea (+675)',
            '595' => 'Paraguay (+595)',
            '51' => 'Peru (+51)',
            '63' => 'Philippines (+63)',
            '48' => 'Poland (+48)',
            '351' => 'Portugal (+351)',
            '1787' => 'Puerto Rico (+1787)',
            '974' => 'Qatar (+974)',
            '262' => 'Reunion (+262)',
            '40' => 'Romania (+40)',
            '7' => 'Russia (+7)',
            '250' => 'Rwanda (+250)',
            '378' => 'San Marino (+378)',
            '239' => 'Sao Tome & Principe (+239)',
            '966' => 'Saudi Arabia (+966)',
            '221' => 'Senegal (+221)',
            '381' => 'Serbia (+381)',
            '248' => 'Seychelles (+248)',
            '232' => 'Sierra Leone (+232)',
            '65' => 'Singapore (+65)',
            '421' => 'Slovak Republic (+421)',
            '386' => 'Slovenia (+386)',
            '677' => 'Solomon Islands (+677)',
            '252' => 'Somalia (+252)',
            '27' => 'South Africa (+27)',
            '34' => 'Spain (+34)',
            '94' => 'Sri Lanka (+94)',
            '290' => 'St. Helena (+290)',
            '1869' => 'St. Kitts (+1869)',
            '1758' => 'St. Lucia (+1758)',
            '249' => 'Sudan (+249)',
            '597' => 'Suriname (+597)',
            '268' => 'Swaziland (+268)',
            '46' => 'Sweden (+46)',
            '41' => 'Switzerland (+41)',
            '963' => 'Syria (+963)',
            '886' => 'Taiwan (+886)',
            '7' => 'Tajikstan (+7)',
            '66' => 'Thailand (+66)',
            '228' => 'Togo (+228)',
            '676' => 'Tonga (+676)',
            '1868' => 'Trinidad & Tobago (+1868)',
            '216' => 'Tunisia (+216)',
            '90' => 'Turkey (+90)',
            '7' => 'Turkmenistan (+7)',
            '993' => 'Turkmenistan (+993)',
            '1649' => 'Turks & Caicos Islands (+1649)',
            '688' => 'Tuvalu (+688)',
            '256' => 'Uganda (+256)',
            '380' => 'Ukraine (+380)',
            '971' => 'United Arab Emirates (+971)',
            '598' => 'Uruguay (+598)',
            '7' => 'Uzbekistan (+7)',
            '678' => 'Vanuatu (+678)',
            '379' => 'Vatican City (+379)',
            '58' => 'Venezuela (+58)',
            '84' => 'Vietnam (+84)',
            '84' => 'Virgin Islands - British (+1284)',
            '84' => 'Virgin Islands - US (+1340)',
            '681' => 'Wallis & Futuna (+681)',
            '969' => 'Yemen (North)(+969)',
            '967' => 'Yemen (South)(+967)',
            '260' => 'Zambia (+260)',
            '263' => 'Zimbabwe (+263)',
        ];

        return $prefix;
    }

}
