<?php

namespace App\Http\Controllers\Front_End;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\DeployLabel;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeSeat;
use App\Models\ReserveSeat;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Session;
use Validator;

class Seatrequest extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {

                Session::put("seat_login", true);
                Session::put("last_url", $request->fullUrl());
                $type = 'browser';

                if ($request->query('NFCCode') != '') {
                    $type = 'nfccode';
                } elseif ($request->query('QRCode') != '') {
                    $type = 'qrcode';
                }
                Session::put("code_type", $type);

                return redirect('/seatlogin');
            }

            return $next($request);
        });
    }

    /**
     * [browser description]
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author Jatin Bhatt
     */
    public function browser(Request $request, $id = null)
    {

        $inputs = $request->all();

        $user_role = Auth::User()->role;
        $apiaccess = Auth::User()->api_access;
        $Building = Building::get();

        $type = "browser";
        $code = "";
        if (isset($inputs['QRCode']) && $inputs['QRCode'] != '') {
            $type = "qrcode";
            $code = $inputs['QRCode'];
        } elseif (isset($inputs['NFCCode']) && $inputs['NFCCode'] != '') {
            $type = "nfccode";
            $code = $inputs['NFCCode'];
        } else {
            $type = "browser";
        }

        if ($apiaccess == 1 or $user_role == 1) {
            return view('seat_request/nfc_code', compact('Building', 'type', 'code'));
        } else {
            return view('seat_request/permission', compact('type'));
        }
    }

    /**
     * [nfccode description]
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author Jatin Bhatt
     */
    public function nfccode(Request $request, $id = null)
    {
        $type = "browser";
        $Building = Building::get();
        return view('seat_request/nfc_code', compact('Building', 'id', 'type'));
    }

    /**
     * [nfccode description]
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author Jatin Bhatt
     */
    public function qrcode(Request $request, $id = null)
    {
        $Building = Building::get();
        $type = "browser";
        return view('seat_request/qr_code', compact('Building', 'id', 'type'));

    }

    /**
     * [challenge description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function challenge(Request $request)
    {

        $inputs = $request->all();
        $rules['building_id'] = 'required';
        $rules['office_id'] = 'required';
        $rules['office_asset_id'] = 'required';
        $rules['seat_id'] = 'required';

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $type = $inputs['type'];
        $user_role = Auth::User()->role;

        $building = Building::where('building_id', $inputs['building_id'])->first();
        $office = Office::where('office_id', $inputs['office_id'])->first();
        $assets = OfficeAsset::where('id', $inputs['office_asset_id'])->first();
        $seat = OfficeSeat::where('seat_id', $inputs['seat_id'])->first();

        if ($user_role == 1) {

            $response = [
                'success' => true,
                'html' => view('seat_request/upload_nfc_code', compact('building', 'office', 'assets', 'seat', 'type'))->render(),
            ];

            return response()->json($response, 200);

        } else if ($user_role == 2) {

            $date = date('Y-m-d');
            $reserve_seat = ReserveSeat::where('user_id', Auth::User()->id)->where('reserve_date', $date)->where('office_asset_id', $assets->id)->where('seat_id', $seat->seat_id)->whereIn('status', ['1', '4'])->first();

            if ($type == 'nfccode') {

                if ($seat->nfc_code == '') {
                    $error_message = 'Cannot make a seat request as admin needs to activate the label';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                } else if ($inputs['nfc_code'] != $seat->nfc_code) {
                    $error_message = 'Incorrect NFC Code, please select the correct seat. If problem persists please speak to your admin ';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                }

            } elseif ($type == 'qrcode') {
                if ($seat->qr_code == '') {
                    $error_message = 'Cannot make a seat request as admin needs to activate the label';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                } else if ($inputs['qr_code'] != $seat->qr_code) {
                    $error_message = 'Incorrect QR Code, please select the correct seat. If problem persists please speak to your admin ';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                }
            } elseif ($type == 'browser') {
                if ($seat->browser == '') {
                    $error_message = 'Cannot make a seat request as admin needs to disable browser checkin/checkout for this office assets';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                }
            }

            if ($reserve_seat == '' && $assets->auto_book == 0) {
                $error_message = 'No Booking Detected, Please book and alternative seat using the portal';
                $response = [
                    'success' => true,
                    'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'error_message'))->render(),
                ];
                return response()->json($response, 200);
            } else if ($reserve_seat == '' && $assets->auto_book == 1) {
                $response = [
                    'success' => true,
                    'html' => view('seat_request/new_seat_booking', compact('building', 'office', 'assets', 'seat'))->render(),
                ];
                return response()->json($response, 200);
            }

            $response = [
                'success' => true,
                'html' => view('seat_request/page_request', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat'))->render(),
            ];

            return response()->json($response, 200);

        } else if ($user_role == 3) {

            $date = date('Y-m-d');
            $reserve_seat = ReserveSeat::where('reserve_date', $date)->where('seat_id', $seat->seat_id)->whereIn('status', ['1', '4'])->first();
            if ($type == 'nfccode') {

                if ($seat->nfc_code == '') {
                    $error_message = 'Cannot make a seat request as admin needs to activate the label';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                } else if ($inputs['nfc_code'] != $seat->nfc_code) {
                    $error_message = 'Incorrect NFC Code, please select the correct seat. If problem persists please speak to your admin ';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                }

            } elseif ($type == 'qrcode') {
                if ($seat->qr_code == '') {
                    $error_message = 'Cannot make a seat request as admin needs to activate the label';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                } else if ($inputs['qr_code'] != $seat->qr_code) {
                    $error_message = 'Incorrect QR Code, please select the correct seat. If problem persists please speak to your admin ';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                }
            } elseif ($type == 'browser') {
                if ($seat->browser == '') {
                    $error_message = 'Cannot make a seat request as admin needs to disable browser checkin/checkout for this office assets';
                    $response = [
                        'success' => true,
                        'html' => view('seat_request/seat_request_error_messages', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat', 'error_message'))->render(),
                    ];

                    return response()->json($response, 200);
                }
            }

            $response = [
                'success' => true,
                'html' => view('seat_request/seat_clean', compact('building', 'office', 'assets', 'seat', 'type', 'reserve_seat'))->render(),
            ];

            return response()->json($response, 200);
        }
    }

    /**
     * [message description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function message(Request $request)
    {
        $inputs = $request->all();

        $user_role = Auth::User()->role;
        return view('seat_request/page_request');
    }
    /**
     * [message description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function upload_code(Request $request)
    {
        $inputs = $request->all();

        $user_role = Auth::User()->role;
        if ($user_role == 1) {
            $seat_info = OfficeSeat::where('seat_id', $inputs['seat_id'])->first();
            $building = Building::where('building_id', $seat_info->building_id)->first();
            $office = Office::where('office_id', $seat_info->office_id)->first();
            $assets = OfficeAsset::where('id', $seat_info->office_asset_id)->first();
            $seat = OfficeSeat::where('seat_id', $seat_info->seat_id)->first();

            $unique_code = ApiHelper::generateRandomString();

            if ($inputs['type'] == 'qrcode') {
                $seat_info->qr_code = '1234456';

            } elseif ($inputs['type'] == 'nfccode') {
                $seat_info->nfc_code = '1234456';

            }
            $type = $inputs['type'];
            if ($seat_info->save()) {

                $response = [
                    'success' => true,
                    'html' => view('seat_request/success_code', compact('building', 'office', 'assets', 'seat', 'type'))->render(),
                ];

                return response()->json($response, 200);
            }

        }

    }

    /**
     * [message description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function checkin(Request $request, $id)
    {
        $inputs = $request->all();

        if ($inputs['type'] == 'qrcode') {
            $type = 1;
        } elseif ($inputs['type'] == 'nfccode') {
            $type = 2;
        } elseif ($inputs['type'] == 'browser') {
            $type = 3;
        }

        $user_role = Auth::User()->role;
        if ($user_role == 2) {
            $reserve_seat = ReserveSeat::where('user_id', Auth::User()->id)->where('reserve_seat_id', $id)->first();
            $reserve_seat->checkin = 1;
            $reserve_seat->checkin_method = $type;
            $reserve_seat->checkin_time = date('H:i:s');
            if ($reserve_seat->save()) {
                $response = [
                    'success' => true,
                    'time' => $reserve_seat->checkin_time,
                    'message' => 'Seat Check In successfull',
                ];

                $modal_type = "seat_request_success";
                $title = "Seat Check In Successfull";
                $message = "You Seat Check In Successfull";
                $what_is_next = "Nothing, You Have successfull Checkin seat";

                $response = [
                    'success' => true,
                    'time' => $reserve_seat->checkin_time,
                    'html' => view('modal_content', compact('modal_type', 'title', 'message', 'what_is_next'))->render(),
                ];

                if (isset($reserve_seat->seat_id)) {
                    $deploylabel = DeployLabel::where('seat_id', $reserve_seat->seat_id)->where('status', 2)->first();
                    if ($deploylabel) {
                        $deploylabel->status = '3';
                        $deploylabel->save();
                    }
                }

                return response()->json($response, 200);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Seat Check In Not successfull',
                ];
                return response()->json($response, 400);
            }

        }

    }

    /**
     * [message description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function checkout(Request $request, $id)
    {
        $inputs = $request->all();

        if ($inputs['type'] == 'qrcode') {
            $type = 1;
        } elseif ($inputs['type'] == 'nfccode') {
            $type = 2;
        } elseif ($inputs['type'] == 'browser') {
            $type = 3;
        }

        $user_role = Auth::User()->role;
        if ($user_role == 2) {
            $reserve_seat = ReserveSeat::where('user_id', Auth::User()->id)->whereIn('status', ['1', '4'])->where('reserve_seat_id', $id)->first();
            $reserve_seat->checkout = 1;
            $reserve_seat->checkout_time = date('H:i:s');
            if ($reserve_seat->save()) {

                $modal_type = "seat_request_success";
                $title = "Seat Check Out Successfull";
                $message = "You Seat Check Out Successfull";
                $what_is_next = "Nothing, You Have successfull Checkout seat";

                $response = [
                    'success' => true,
                    'time' => $reserve_seat->checkout_time,
                    'html' => view('modal_content', compact('modal_type', 'title', 'message', 'what_is_next'))->render(),
                ];

                return response()->json($response, 200);

            } else {
                $response = [
                    'success' => false,
                    'message' => 'Seat Check Out Not successfull',
                ];
                return response()->json($response, 400);
            }

        }

    }

    /**
     * [message description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function cleanseat(Request $request, $id)
    {

        $inputs = $request->all();
        $rules['cleaner_notes'] = 'required';
        $messages = [];

        $messages['cleaner_notes.required'] = 'Please Enter Clean Seat Notes';

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        if ($inputs['type'] == 'qrcode') {
            $type = 1;
        } elseif ($inputs['type'] == 'nfccode') {
            $type = 2;
        } elseif ($inputs['type'] == 'browser') {
            $type = 3;
        }

        $user_role = Auth::User()->role;
        if ($user_role == 3) {
            $reserve_seat = ReserveSeat::where('reserve_seat_id', $id)->first();
            $reserve_seat->cleaning = 1;
            $reserve_seat->clean_method = $type;
            $reserve_seat->cleaner_notes = trim($inputs['cleaner_notes']);
            $reserve_seat->clean_time = date('H:i:s');
            if ($reserve_seat->save()) {

                if (isset($reserve_seat->seat_id)) {
                    $deploylabel = DeployLabel::where('seat_id', $reserve_seat->seat_id)->where('status', 2)->first();
                    if ($deploylabel) {
                        $deploylabel->status = 3;
                        $deploylabel->save();
                    }
                }

                $modal_type = "seat_request_success";
                $title = "Seat Clean Successfull";
                $message = "You have Clean Seat Successfull";
                $what_is_next = "Nothing, You Have successfull Clean Seat";

                $response = [
                    'success' => true,
                    'time' => $reserve_seat->clean_time,
                    'html' => view('modal_content', compact('modal_type', 'title', 'message', 'what_is_next'))->render(),
                ];

                return response()->json($response, 200);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Seat Clean Not successfull',
                ];
                return response()->json($response, 400);
            }

        }

    }

    /**
     * [filteroffice description]
     * @param  Request $request [description]
     * @return [building_id] [building_id]
     * @author Jatin Bhatt
     */
    public function filterofficeList(Request $request, $building_id)
    {
        $Office = Office::where('building_id', $building_id)->get();
        if ($Office->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $Office]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    /**
     * [filterofficeassetsList description]
     * @param  Request $request [description]
     * @return [office_id]           [office_id]
     * @author Jatin Bhatt
     */
    public function filterofficeassetsList(Request $request, $office_id)
    {
        $OfficeAsset = OfficeAsset::where('office_id', $office_id)->get();
        if ($OfficeAsset->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $OfficeAsset]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    /**
     * [filterseatslist description]
     * @param  Request $request [description]
     * @return [office_assets_id]           [office_assets_id]
     * @author Jatin Bhatt
     */

    public function filterseatslist(Request $request, $office_assets_id)
    {
        $user_role = Auth::User()->role;
        $deploy_label = DeployLabel::where('office_asset_id', $office_assets_id)->whereIN('status', ['2', '3'])->pluck('office_asset_id');
        $OfficeSeat = OfficeSeat::whereIn('office_asset_id', $deploy_label)->get();
        if ($OfficeSeat->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $OfficeSeat]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

}
