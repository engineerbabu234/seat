<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\ApiConnections;
use App\Models\Building;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeSeat;
use App\Models\Quesionaire;
use App\Models\Question;
use App\Models\ReserveSeat;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Validator;

class OfficeAssetController extends Controller
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->viewPath = "admin.office_asset.";
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index(Request $request, $office_id = null)
    {
        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND office_asset.title like '%{$search}%'";
                $whereStr .= " OR offices.office_name like '%{$search}%'";
                $whereStr .= " OR buildings.building_name like '%{$search}%'";
            }

            $columns = ['office_asset.id', 'office_asset.is_covid_test', 'office_asset.office_id', 'office_asset.updated_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description', 'office_asset.quesionaire_id', 'office_asset.asset_type'];

            $officeAssets = OfficeAsset::select($columns)->leftJoin("offices", "offices.office_id", "office_asset.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->whereRaw($whereStr, $whereParams)->orderBy('id', 'desc');

            if (isset($office_id) && $office_id != "") {
                $officeAssets = $officeAssets->where("office_asset.office_id", $office_id);

            }

            if ($officeAssets) {
                $total = $officeAssets->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $officeAssets = $officeAssets->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $officeAssets = $officeAssets->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $officeAssets = $officeAssets->get();

            $final = [];

            $total_quesionaire = 0;
            $number_key = 1;
            foreach ($officeAssets as $key => $value) {
                $total_seats = OfficeSeat::where('office_asset_id', $value->id)->whereNull('deleted_at')->get();
                if (isset($value->quesionaire_id) && $value->quesionaire_id != '') {
                    $quesionaire_info = explode(",", $value->quesionaire_id);
                    $quesionaire_total = count($quesionaire_info);

                } else {
                    $quesionaire_total = 0;
                }
                $asset_type = '';
                if ($value->asset_type == 1) {
                    $asset_type .= '<span class="iconWrap iconSize_32" data-trigger="hover" title="Desks" data-content="Desks" ><img alt="Desks" src="' . asset('admin_assets') . '/images/desks.png" class="icon bl-icon"></span>';
                } else if ($value->asset_type == 2) {
                    $asset_type .= '<span class="iconWrap iconSize_32" data-trigger="hover" title="Carpark Spaces" data-content="Carpark Spaces" ><img  alt="Carpark Spaces" src="' . asset('admin_assets') . '/images/carparking.png" class="icon bl-icon"> </span>';
                } else if ($value->asset_type == 3) {
                    $asset_type .= '<span class="iconWrap iconSize_32" data-trigger="hover" title="Collaboration Spaces" data-content="Collaboration Spaces" ><img  alt="Collaboration Spaces" src="' . asset('admin_assets') . '/images/colobration.png" class="icon bl-icon"></span> ';
                } else if ($value->asset_type == 4) {
                    $asset_type .= '<span class="iconWrap iconSize_32" data-trigger="hover" title="Meeting Room Spaces" data-content="Meeting Room Spaces" ><img  alt="Meeting Room Spaces" src="' . asset('admin_assets') . '/images/meetings.png" class="icon bl-icon"></span> ';
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['title'] = $value->title;
                $final[$key]['total_seats'] = count($total_seats);
                $final[$key]['total_quesionaire'] = $quesionaire_total;
                $final[$key]['total_documents'] = 0;
                $final[$key]['is_covid_test'] = $value->is_covid_test;
                $final[$key]['asset_type'] = $asset_type;
                $final[$key]['updated_at'] = date('d/m/Y', strtotime($value->updated_at));
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }

        $apiconnections = ApiConnections::where('api_type', '1')->whereNull('deleted_at')->get();
        $buildings = Building::whereNull('deleted_at')->get();
        return view($this->viewPath . 'index', compact('buildings', 'apiconnections'));
    }

    /**
     * [saveOfficeAsset description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveOfficeAsset(Request $request)
    {
        $inputs = $request->all();

        $rules = [];
        if ($inputs['seat_clean'] == 1) {
            $rules['cleanstart_time'] = 'required|different:checkin_start_time|after:checkout_end_time';
            $rules['cleanend_time'] = 'required|after:cleanstart_time';
        }

        if ($inputs['checkin'] == 1) {
            $rules['checkin_start_time'] = 'required';
            $rules['checkin_end_time'] = 'required|after:checkin_start_time';
            $rules['checkout_start_time'] = 'required||after:checkin_end_time';
            $rules['checkout_end_time'] = 'required|after:checkout_start_time';
        }

        $rules['building_id'] = 'required';
        $rules['office_id'] = 'required';
        $rules['title'] = 'required';
        $rules['preview_image'] = 'required';

        if (($inputs['asset_type'] == '3' or $inputs['asset_type'] == '4') && $inputs['conference_management'] == 1) {
            $rules['conference_endpoint'] = 'required';
            $rules['teleconferance_name'] = 'required';
        }

        $messages = [];
        $messages['building_id.required'] = 'Please select Building';
        $messages['office_id.required'] = 'Please select Office';
        $messages['title.required'] = 'Please Add Office Assets Title';
        $messages['preview_image.required'] = 'Please Upload Image';

        $messages['conference_endpoint.required'] = 'Please select conference endpoint';
        $messages['teleconferance_name.required'] = 'Please select teleconferance name';

        $messages['checkout_start_time.required'] = 'Please select checkout Start time';
        $messages['checkout_start_time.after'] = 'CheckOut Start Time must be after the ChecklIn End Time';
        $messages['checkout_end_time.required'] = 'Please select checkout End time';
        $messages['checkout_end_time.after'] = 'End time must Greater then Start time';

        $messages['cleanstart_time.required'] = 'Please select Clean Start time';
        $messages['cleanend_time.required'] = 'Please select Clean End time';
        $messages['cleanend_time.after'] = 'End time Greter then Start time';
        $messages['cleanstart_time.different'] = 'Clean Start time not equal to Checkin time';
        $messages['cleanstart_time.after'] = 'Clean Start Time must be Greter then Checkout end time';

        $messages['checkin_start_time.required'] = 'Please select checkin Start time';
        $messages['checkin_end_time.required'] = 'Please select checkin End time';
        $messages['checkin_end_time.after'] = 'End time Greter then Start time';
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        // $image_64 = null;
        // $image_64 = $inputs['preview_image'];

        // $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        // $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        // $image = str_replace($replace, '', $image_64);

        // $image = str_replace(' ', '+', $image);

        // $imageName = str_random('10') . '_' . time() . '.' . $extension;
        // $destinationPath = ImageHelper::$getOfficeAssetsImagePath;

        // $uploadPath = $destinationPath . '/' . $imageName;

        // if (file_put_contents($uploadPath, base64_decode($image))) {
        //     $preview_image = $imageName;
        // }

        $fileName = null;
        if ($request->hasFile('preview_image')) {
            $fileName = str_random('10') . '_' . time() . '.' . request()->preview_image->getClientOriginalExtension();
            request()->preview_image->move(public_path('uploads/office_asset/'), $fileName);
        }

        $checkin_start_time = '';
        $checkin_end_time = '';
        $checkout_start_time = '';
        $checkout_end_time = '';
        $required_after_checkout = '';
        if ($inputs['checkin'] == 1) {
            $checkin_start_time = date('H:i A', strtotime($inputs['checkin_start_time']));
            $checkin_end_time = date('H:i A', strtotime($inputs['checkin_end_time']));
            $checkout_start_time = date('H:i A', strtotime($inputs['checkout_start_time']));
            $checkout_end_time = date('H:i A', strtotime($inputs['checkout_end_time']));
            $required_after_checkout = isset($inputs['required_after_checkout']) ? 1 : 0;
        } else {
            $checkin_start_time = '';
            $checkin_end_time = '';
            $checkout_start_time = '';
            $checkout_end_time = '';
            $required_after_checkout = isset($inputs['required_after_checkout']) ? 1 : 0;
        }

        $cleanstart_time = '';
        $cleanend_time = '';

        if ($inputs['seat_clean'] == 1) {
            $cleanstart_time = date('H:i A', strtotime($inputs['cleanstart_time']));
            $cleanend_time = date('H:i A', strtotime($inputs['cleanend_time']));
        } else {
            $cleanstart_time = '';
            $cleanend_time = '';
        }

        $email_user_link = '';
        $conference_endpoint = '';
        $teleconferance_name = '';
        $conference_management = 0;
        if (isset($inputs['conference_management']) && $inputs['conference_management'] == 1) {
            $conference_management = 1;
            $conference_endpoint = $inputs['conference_endpoint'];
            $teleconferance_name = $inputs['teleconferance_name'];
            $email_user_link = isset($inputs['email_user_link']) ? 1 : 0;
        } else {
            $email_user_link = '';
            $conference_endpoint = '';
            $teleconferance_name = '';
            $conference_management = 0;
        }

        $OfficeAsset = new OfficeAsset();
        $OfficeAsset->user_id = Auth::id();
        $OfficeAsset->building_id = $inputs['building_id'];
        $OfficeAsset->office_id = $inputs['office_id'];
        $OfficeAsset->title = $inputs['title'];
        $OfficeAsset->description = $inputs['description'];
        $OfficeAsset->checkin = $inputs['checkin'];
        $OfficeAsset->book_within = $inputs['book_within'];

        $OfficeAsset->checkin_start_time = $checkin_start_time;
        $OfficeAsset->checkin_end_time = $checkin_end_time;
        $OfficeAsset->checkout_start_time = $checkout_start_time;
        $OfficeAsset->checkout_end_time = $checkout_end_time;

        $OfficeAsset->cleanstart_time = $cleanstart_time;
        $OfficeAsset->cleanend_time = $cleanend_time;
        $OfficeAsset->required_after_checkout = $required_after_checkout;

        $OfficeAsset->auto_realese = isset($inputs['auto_realese']) ? 1 : 0;
        $OfficeAsset->auto_book = isset($inputs['auto_book']) ? 1 : 0;
        $OfficeAsset->seat_clean = $inputs['seat_clean'];
        $OfficeAsset->asset_type = $inputs['asset_type'];
        $OfficeAsset->preview_image = $preview_image;

        $OfficeAsset->register_noshow = isset($inputs['register_noshow']) ? 1 : 0;
        $OfficeAsset->nfc = isset($inputs['nfc']) ? 1 : 0;
        $OfficeAsset->qr = isset($inputs['qr']) ? 1 : 0;
        $OfficeAsset->browser = isset($inputs['browser']) ? 1 : 0;
        $OfficeAsset->token = isset($inputs['token']) ? 1 : 0;
        $OfficeAsset->presence = isset($inputs['presence']) ? 1 : 0;

        $OfficeAsset->conference_management = $conference_management;
        $OfficeAsset->email_user_link = $email_user_link;
        $OfficeAsset->conference_endpoint = $conference_endpoint;
        $OfficeAsset->teleconferance_name = $teleconferance_name;

        $OfficeAsset->billing_managment = $inputs['billing_managment'];
        $OfficeAsset->daily_cost = isset($inputs['daily_cost']) ? $inputs['daily_cost'] : '';

        if ($OfficeAsset->save()) {
            $response = [
                'success' => true,
                'message' => 'Office Asset Added success',
                'assset_id' => $OfficeAsset->id,
                'building_id' => $inputs['building_id'],
                'office_id' => $inputs['office_id'],
            ];
        } else {
            return back()->with('error', 'Office Assets Added failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [editOfficeAsset description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function editOfficeAsset(Request $request, $assetId)
    {
        $officeAsset = OfficeAsset::find($assetId);

        $buildings = Building::whereNull('deleted_at')->get();
        $Office = Office::where('building_id', $officeAsset->building_id)->whereNull('deleted_at')->get();
        $apiconnections = ApiConnections::where('api_type', '1')->whereNull('deleted_at')->get();
        $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);
        $checkin_time = date('H:i:s', strtotime($officeAsset->checkin_time));
        $checkin_start_time = date('H:i:s', strtotime($officeAsset->checkin_start_time));
        $checkin_end_time = date('H:i:s', strtotime($officeAsset->checkin_end_time));
        $checkout_start_time = date('H:i:s', strtotime($officeAsset->checkout_start_time));
        $checkout_end_time = date('H:i:s', strtotime($officeAsset->checkout_end_time));
        $cleanstart_time = date('H:i:s', strtotime($officeAsset->cleanstart_time));
        $cleanend_time = date('H:i:s', strtotime($officeAsset->cleanend_time));
        $response = [
            'success' => true,
            'checkin_time' => $checkin_time,
            'checkin_start_time' => $checkin_start_time,
            'checkin_end_time' => $checkin_end_time,
            'checkout_start_time' => $checkout_start_time,
            'checkout_end_time' => $checkout_end_time,
            'cleanstart_time' => $cleanstart_time,
            'cleanend_time' => $cleanend_time,
            'html' => view($this->viewPath . 'edit', compact('officeAsset', 'buildings', 'Office', 'assets_image', 'apiconnections'))->render(),
        ];

        return response()->json($response, 200);
    }

    /**
     * [updateOfficeAsset description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function updateOfficeAsset(Request $request, $assetId)
    {
        $inputs = $request->all();

        $messages = [];

        $rules['building_id'] = 'required';
        $rules['office_id'] = 'required';
        $rules['title'] = 'required';
        $rules['preview_image'] = 'required';

        if ($inputs['seat_clean'] == 1) {
            $rules['cleanstart_time'] = 'required|different:checkin_start_time|after:checkout_end_time';
            $rules['cleanend_time'] = 'required|after:cleanstart_time';
        }

        if ($inputs['checkin'] == 1) {
            $rules['checkin_start_time'] = 'required';
            $rules['checkin_end_time'] = 'required|after:checkin_start_time';
            $rules['checkout_start_time'] = 'required||after:checkin_end_time';
            $rules['checkout_end_time'] = 'required|after:checkout_start_time';
        }

        if (($inputs['asset_type'] == '3' or $inputs['asset_type'] == '4') && $inputs['conference_management'] == 1) {
            $rules['conference_endpoint'] = 'required';
            $rules['teleconferance_name'] = 'required';
        }

        $messages['building_id.required'] = 'Please select Building';
        $messages['office_id.required'] = 'Please select Office';
        $messages['title.required'] = 'Please Add Office Assets Title';
        $messages['preview_image.required'] = 'Please Upload Image';

        $messages['conference_endpoint.required'] = 'Please select conference endpoint';
        $messages['teleconferance_name.required'] = 'Please select teleconferance name';

        $messages['checkout_start_time.required'] = 'Please select checkout Start time';
        $messages['checkout_start_time.after'] = 'CheckOut Start Time must be after the ChecklIn End Time';
        $messages['checkout_end_time.required'] = 'Please select checkout End time';
        $messages['checkout_end_time.after'] = 'End time must Greater then Start time';

        $messages['cleanstart_time.required'] = 'Please select Clean Start time';
        $messages['cleanend_time.required'] = 'Please select Clean End time';
        $messages['cleanend_time.after'] = 'End time Greter then Start time';
        $messages['cleanstart_time.different'] = 'Clean Start time not equal to Checkin time';
        $messages['cleanstart_time.after'] = 'Clean Start Time must be Greter then Checkout end time';

        $messages['checkin_start_time.required'] = 'Please select checkin Start time';
        $messages['checkin_end_time.required'] = 'Please select checkin End time';
        $messages['checkin_end_time.after'] = 'End time Greter then Start time';

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        // if ($inputs['preview_image']) {

        //     $oldimage = OfficeAsset::find($assetId);
        //     $new_image = explode('office_asset/', $inputs['preview_image']);

        //     if (isset($new_image[1]) && ($oldimage->preview_image == $new_image[1])) {
        //     } else {
        //         $image_64 = null;
        //         $image_64 = $inputs['preview_image'];

        //         $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        //         $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        //         $image = str_replace($replace, '', $image_64);
        //         $image = str_replace(' ', '+', $image);
        //         $imageName = str_random('10') . '_' . time() . '.' . $extension;
        //         $destinationPath = ImageHelper::$getOfficeAssetsImagePath;

        //         $uploadPath = $destinationPath . '/' . $imageName;

        //         //remove old image
        //         $this->remove_office_assets_image($assetId);

        //         if (file_put_contents($uploadPath, base64_decode($image))) {
        //             $preview_image = $imageName;
        //         }
        //     }
        // }

        $fileName = null;
        if ($request->hasFile('preview_image')) {
            $fileName = str_random('10') . '_' . time() . '.' . request()->preview_image->getClientOriginalExtension();
            request()->preview_image->move(public_path('uploads/office_asset/'), $fileName);
        }

        $checkin_start_time = '';
        $checkin_end_time = '';
        $checkout_start_time = '';
        $checkout_end_time = '';
        $required_after_checkout = '';
        if ($inputs['checkin'] == 1) {
            $checkin_start_time = date('H:i A', strtotime($inputs['checkin_start_time']));
            $checkin_end_time = date('H:i A', strtotime($inputs['checkin_end_time']));
            $checkout_start_time = date('H:i A', strtotime($inputs['checkout_start_time']));
            $checkout_end_time = date('H:i A', strtotime($inputs['checkout_end_time']));
            $required_after_checkout = isset($inputs['required_after_checkout']) ? 1 : 0;
        } else {
            $checkin_start_time = '';
            $checkin_end_time = '';
            $checkout_start_time = '';
            $checkout_end_time = '';
            $required_after_checkout = isset($inputs['required_after_checkout']) ? 1 : 0;
        }

        $cleanstart_time = '';
        $cleanend_time = '';

        if ($inputs['seat_clean'] == 1) {
            $cleanstart_time = date('H:i A', strtotime($inputs['cleanstart_time']));
            $cleanend_time = date('H:i A', strtotime($inputs['cleanend_time']));
        } else {
            $cleanstart_time = '';
            $cleanend_time = '';
        }

        $email_user_link = '';
        $conference_endpoint = '';
        $teleconferance_name = '';
        $conference_management = 0;
        if ($inputs['conference_management'] == 1) {
            $conference_management = 1;
            $conference_endpoint = $inputs['conference_endpoint'];
            $teleconferance_name = $inputs['teleconferance_name'];
            $email_user_link = isset($inputs['email_user_link']) ? 1 : 0;
        } else {
            $conference_management = 0;
            $email_user_link = '';
            $conference_endpoint = '';
            $teleconferance_name = '';
        }

        $OfficeAsset = OfficeAsset::find($assetId);
        $OfficeAsset->user_id = Auth::id();
        $OfficeAsset->building_id = $inputs['building_id'];
        $OfficeAsset->office_id = $inputs['office_id'];
        $OfficeAsset->title = $inputs['title'];
        $OfficeAsset->book_within = $inputs['book_within'];
        $OfficeAsset->description = $inputs['description'];
        $OfficeAsset->checkin = $inputs['checkin'];

        $OfficeAsset->auto_realese = isset($inputs['auto_realese']) ? 1 : 0;
        $OfficeAsset->auto_book = isset($inputs['auto_book']) ? 1 : 0;
        $OfficeAsset->seat_clean = $inputs['seat_clean'];
        $OfficeAsset->asset_type = $inputs['asset_type'];
        $OfficeAsset->checkin_start_time = $checkin_start_time;
        $OfficeAsset->checkin_end_time = $checkin_end_time;
        $OfficeAsset->checkout_start_time = $checkout_start_time;
        $OfficeAsset->checkout_end_time = $checkout_end_time;

        $OfficeAsset->cleanstart_time = $cleanstart_time;
        $OfficeAsset->cleanend_time = $cleanend_time;
        $OfficeAsset->required_after_checkout = $required_after_checkout;

        $OfficeAsset->register_noshow = isset($inputs['register_noshow']) ? 1 : 0;

        $OfficeAsset->nfc = isset($inputs['nfc']) ? 1 : 0;
        $OfficeAsset->qr = isset($inputs['qr']) ? 1 : 0;
        $OfficeAsset->browser = isset($inputs['browser']) ? 1 : 0;
        $OfficeAsset->token = isset($inputs['token']) ? 1 : 0;
        $OfficeAsset->presence = isset($inputs['presence']) ? 1 : 0;

        $OfficeAsset->conference_management = $conference_management;
        $OfficeAsset->email_user_link = $email_user_link;
        $OfficeAsset->conference_endpoint = $conference_endpoint;
        $OfficeAsset->teleconferance_name = $teleconferance_name;

        $OfficeAsset->billing_managment = $inputs['billing_managment'];
        $OfficeAsset->daily_cost = isset($inputs['daily_cost']) ? $inputs['daily_cost'] : '';

        if (isset($preview_image)) {
            $OfficeAsset->preview_image = $preview_image;
            $OfficeAsset->asset_canvas = '';
        }

        if ($OfficeAsset->save()) {
            $response = [
                'success' => true,
                'message' => 'Office Asset Updated success',
            ];
        } else {
            return back()->with('error', 'Office Assets Update failed,please try again');
        }

        return response()->json($response, 200);

    }

    /**
     * [deleteAsset description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function deleteAsset(Request $request, $assetId)
    {
        $OfficeSeat = OfficeSeat::where('office_asset_id', $assetId)->delete();

        $officeAsset = OfficeAsset::find($assetId);
        $officeAsset->delete();

        $response = [
            'success' => true,
            'message' => 'OfficeAsset successfull Removed',
        ];

        return response()->json($response, 200);
    }

    public function send_cancle_email_delete($office_asset_id)
    {

        $reserve_seat = ReserveSeat::where('office_asset_id', $office_asset_id)->get();

        if ($reserve_seat) {
            foreach ($reserve_seat as $key => $value) {

                $this->send_email_cancel_seat($value->reserve_seat_id);
            }
        }

    }

    public function send_email_cancel_seat($seat_id)
    {
        $logo = env('Logo');
        if ($logo) {
            $Admin = User::where('role', '1')->first();
            $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

        } else {
            $logo_url = asset('front_end/images/logo.png');
        }
        $todaydate = date('Y-m-d');
        $ReserveSeatData = DB::table('reserve_seats as rs')
            ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name', 'u.email')
            ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
            ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
            ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
            ->whereNull('rs.deleted_at')
            ->whereNotIn('rs.status', ['2', '3'])
            ->where('rs.reserve_date', '>=', $todaydate)
            ->where('rs.reserve_seat_id', $seat_id)
            ->get();

        foreach ($ReserveSeatData as $key => $value) {

            $mailData = array(
                'first_name' => $value->user_name,
                'email' => $value->email,
                'user_name' => $value->user_name,
                'form_name' => 'paul@datagov.ai',
                'schedule_name' => 'weBOOK',
                'template' => 'admin_reservation_cancel',
                'subject' => 'weBOOK Reservation Cancelled Due To Admin Remove Office Assets',
                'data' => $value,
                'logo_url' => $logo_url,
            );
            if (!empty($mailData) && !empty($value->email && !is_null($value->email))) {
                Mail::to($value->email)->send(new NotifyMail($mailData));
            }

            $seat_info = ReserveSeat::where('reserve_seat_id', $value->reserve_seat_id)->first();
            $seat_info->status = '2';
            $seat_info->save();
        }
    }

    /**
     * [getoffices description]
     * @param  Request $request     [description]
     * @param  [type]  $building_id [description]
     * @return [type]               [description]
     */
    public function getoffices(Request $request, $building_id)
    {
        $office = Office::select("office_name", "office_id")->where('building_id', $building_id)->get();

        $response = [
            'success' => true,
            'data' => $office,
        ];

        return response()->json($response, 200);
    }

    /**
     * [Get office Assets description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function getofficeassets(Request $request, $assetId)
    {
        $columns = ['office_asset.id', 'office_asset.asset_canvas', 'buildings.building_id', 'office_asset.preview_image', 'office_asset.office_id', 'office_asset.created_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description'];
        $whereStr = '1 = ?';
        $whereParams = [1];
        $whereStr .= ' AND office_asset.id = ' . $assetId;

        $officeAsset = OfficeAsset::select($columns)->leftJoin("offices", "offices.office_id", "office_asset.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->whereRaw($whereStr, $whereParams)->orderBy('id', 'desc')->first();

        $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);

        $response = [
            'success' => true,
            'html' => view($this->viewPath . 'add_seats', compact('officeAsset', 'assets_image'))->render(),
        ];

        return response()->json($response, 200);
    }

    /**
     * [remove_office_assets_image description]
     * @param  [type] $assets_id [description]
     * @return [type]            [description]
     */
    public function remove_office_assets_image($assets_id)
    {
        $OfficeAsset = OfficeAsset::find($assets_id);
        $destinationPath = ImageHelper::$getOfficeAssetsImagePath;
        $removepath = $destinationPath . '/' . $OfficeAsset->preview_image;
        if (file_exists(public_path($removepath))) {
            unlink(public_path($removepath));
        } else {
            return false;
        }
    }

    /**
     * [addseat description]
     * @param Request $request [description]
     */
    public function addseat(Request $request)
    {
        $inputs = $request->all();
        $officeAsset = OfficeAsset::find($inputs['seat_assets_id']);
        $rules = [];
        $rules = [
            'seat_no' => ['required', Rule::unique('seats', 'seat_no')->where('office_asset_id', $inputs['seat_assets_id'])],
            'booking_mode' => 'required',
            'seat_type' => 'required',
            'is_show_user_details' => 'required',
        ];

        if (isset($inputs['telephone']) && $inputs['telephone'] == 1) {
            $rules['telephone_number'] = 'required';

        }

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $OfficeSeat = new OfficeSeat();
        $OfficeSeat->building_id = $officeAsset->building_id;
        $OfficeSeat->office_asset_id = $inputs['seat_assets_id'];
        $OfficeSeat->office_id = $inputs['office_id'];
        $OfficeSeat->seat_no = $inputs['seat_no'];
        $OfficeSeat->description = $inputs['description'];
        $OfficeSeat->booking_mode = $inputs['booking_mode'];
        $OfficeSeat->seat_type = $inputs['seat_type'];
        $OfficeSeat->is_show_user_details = $inputs['is_show_user_details'];
        $OfficeSeat->dots_id = $inputs['seat_no'];

        if ($officeAsset->asset_type == 1) {
            $OfficeSeat->monitor = isset($inputs['monitor']) ? 1 : 0;
            $OfficeSeat->dokingstation = isset($inputs['dokingstation']) ? 1 : 0;
            $OfficeSeat->adjustableheight = isset($inputs['adjustableheight']) ? 1 : 0;
            $OfficeSeat->privatespace = isset($inputs['privatespace']) ? 1 : 0;
            $OfficeSeat->wheelchair = isset($inputs['wheelchair']) ? 1 : 0;
            $OfficeSeat->usbcharger = isset($inputs['usbcharger']) ? 1 : 0;
            $OfficeSeat->privacy = $inputs['privacy'];

        } elseif ($officeAsset->asset_type == 2) {
            $OfficeSeat->underground = isset($inputs['underground']) ? 1 : 0;
            $OfficeSeat->pole_information = isset($inputs['pole_information']) ? 1 : 0;
            $OfficeSeat->wheelchair_accessable = isset($inputs['wheelchair_accessable']) ? 1 : 0;
            $OfficeSeat->parking_difficulty = isset($inputs['parking_difficulty']) ? $inputs['parking_difficulty'] : 1;
        } elseif ($officeAsset->asset_type == 3) {
            $OfficeSeat->kanban_board = isset($inputs['kanban_board']) ? 1 : 0;
            $OfficeSeat->whiteboard = isset($inputs['whiteboard']) ? 1 : 0;
            $OfficeSeat->interactive_whiteboard = isset($inputs['interactive_whiteboard']) ? 1 : 0;
            $OfficeSeat->standing_only = isset($inputs['standing_only']) ? 1 : 0;
            $OfficeSeat->telecomference_screen = isset($inputs['telecomference_screen']) ? 1 : 0;
        } elseif ($officeAsset->asset_type == 4) {
            $OfficeSeat->whiteboard_avaialble = isset($inputs['whiteboard_avaialble']) ? 1 : 0;
            $OfficeSeat->teleconference_screen = isset($inputs['teleconference_screen']) ? 1 : 0;
            $OfficeSeat->is_white_board_interactive = isset($inputs['is_white_board_interactive']) ? 1 : 0;
            $OfficeSeat->telephone = isset($inputs['telephone']) ? 1 : 0;
            if ($OfficeSeat->telephone == 1) {
                $OfficeSeat->telephone_number = isset($inputs['telephone_number']) ? $inputs['telephone_number'] : '';
                $OfficeSeat->number_of_spare_power_sockets = isset($inputs['number_of_spare_power_sockets']) ? $inputs['number_of_spare_power_sockets'] : '';

            }
            $OfficeSeat->meeting_indicator_mounted_on_wall = isset($inputs['meeting_indicator_mounted_on_wall']) ? 1 : 0;

        }

        if ($OfficeSeat->save()) {

            $response = [
                'success' => true,
                'message' => 'Office seat Added success',
                'id' => $OfficeSeat->seat_id,
                'assetId' => $inputs['seat_assets_id'],
                'dotsId' => $inputs['seat_no'],
                'seat_no' => $inputs['seat_no'],
                'status' => 0,
            ];
        } else {
            return back()->with('error', 'Office seat failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [getofficeassetsinfo description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function getofficeassetsinfo(Request $request, $assetId)
    {
        $officeAsset = OfficeAsset::find($assetId);

        $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);

        $seatid = '';
        $assets_count = OfficeSeat::where('office_asset_id', $assetId)->orderBy('seat_id', 'desc')->first();

        if (isset($assets_count->dots_id) && $assets_count->dots_id != '') {
            $seatid = ($assets_count->dots_id + 1);
        } else {
            $seatid = 0;
        }

        $seat_info = OfficeSeat::where('office_asset_id', $assetId)->get();

        $response = [
            'success' => true,
            'data' => $officeAsset,
            'assets_image' => $assets_image,
            'last_id' => $seatid,
            'seat_info' => $seat_info,
        ];

        return response()->json($response, 200);
    }

    /**
     * [updateassets_image description]
     * @param Request $request [description]
     */
    public function updateassets_image(Request $request, $asset_id)
    {
        $inputs = $request->all();

        $OfficeAsset = OfficeAsset::find($asset_id);
        $OfficeAsset->asset_canvas = $inputs['canvas'];

        if ($OfficeAsset->save()) {
            $response = [
                'success' => true,
                'message' => 'Office assets image updated success',
                'id' => $asset_id,
            ];
        } else {
            return back()->with('error', 'Office assets failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [editOfficeAsset description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function edit_seats(Request $request, $seatid)
    {
        $inputs = $request->all();
        $officeseat = OfficeSeat::where('seat_id', $seatid)->first();

        $officeAsset = '';
        if (isset($inputs['asset_id'])) {
            $officeAsset = OfficeAsset::find($inputs['asset_id']);
        }

        $response = [
            'success' => true,
            'asset_type' => $officeAsset->asset_type,
            'html' => view($this->viewPath . 'editofficeseats', compact('officeseat'))->render(),
        ];

        return response()->json($response, 200);
    }

    /**
     * [addseat description]
     * @param Request $request [description]
     */
    public function updateSeat(Request $request, $seatid)
    {
        $inputs = $request->all();
        $officeAsset = OfficeAsset::find($inputs['office_asset_id']);
        $rules = [
            // 'seat_no' => 'required',
            'booking_mode' => 'required',
            'seat_type' => 'required',
            'is_show_user_details' => 'required',

        ];
        $messages = [];

        if (isset($inputs['telephone']) && $inputs['telephone'] == 1) {
            $rules['telephone_number'] = 'required';
            $messages['telephone_number.required'] = 'Please Add Telephone number';

        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $OfficeSeat = OfficeSeat::where('seat_id', $seatid)->first();
        $OfficeSeat->building_id = $inputs['building_id'];
        $OfficeSeat->office_asset_id = $inputs['office_asset_id'];
        $OfficeSeat->office_id = $inputs['office_id'];
        // $OfficeSeat->seat_no = $inputs['seat_no'];
        $OfficeSeat->description = $inputs['description'];
        $OfficeSeat->booking_mode = $inputs['booking_mode'];
        $OfficeSeat->seat_type = $inputs['seat_type'];
        $OfficeSeat->is_show_user_details = $inputs['is_show_user_details'];

        if ($officeAsset->asset_type == 1) {
            $OfficeSeat->monitor = isset($inputs['monitor']) ? 1 : 0;
            $OfficeSeat->dokingstation = isset($inputs['dokingstation']) ? 1 : 0;
            $OfficeSeat->adjustableheight = isset($inputs['adjustableheight']) ? 1 : 0;
            $OfficeSeat->privatespace = isset($inputs['privatespace']) ? 1 : 0;
            $OfficeSeat->wheelchair = isset($inputs['wheelchair']) ? 1 : 0;
            $OfficeSeat->usbcharger = isset($inputs['usbcharger']) ? 1 : 0;
            $OfficeSeat->privacy = $inputs['privacy'];

        } elseif ($officeAsset->asset_type == 2) {
            $OfficeSeat->underground = isset($inputs['underground']) ? 1 : 0;
            $OfficeSeat->pole_information = isset($inputs['pole_information']) ? 1 : 0;
            $OfficeSeat->wheelchair_accessable = isset($inputs['wheelchair_accessable']) ? 1 : 0;
            $OfficeSeat->parking_difficulty = isset($inputs['parking_difficulty']) ? $inputs['parking_difficulty'] : 1;
        } elseif ($officeAsset->asset_type == 3) {
            $OfficeSeat->kanban_board = isset($inputs['kanban_board']) ? 1 : 0;
            $OfficeSeat->whiteboard = isset($inputs['whiteboard']) ? 1 : 0;
            $OfficeSeat->interactive_whiteboard = isset($inputs['interactive_whiteboard']) ? 1 : 0;
            $OfficeSeat->standing_only = isset($inputs['standing_only']) ? 1 : 0;
            $OfficeSeat->telecomference_screen = isset($inputs['telecomference_screen']) ? 1 : 0;
        } elseif ($officeAsset->asset_type == 4) {
            $OfficeSeat->whiteboard_avaialble = isset($inputs['whiteboard_avaialble']) ? 1 : 0;
            $OfficeSeat->teleconference_screen = isset($inputs['teleconference_screen']) ? 1 : 0;
            $OfficeSeat->is_white_board_interactive = isset($inputs['is_white_board_interactive']) ? 1 : 0;
            $OfficeSeat->telephone = isset($inputs['telephone']) ? 1 : 0;
            if ($OfficeSeat->telephone == 1) {
                $OfficeSeat->telephone_number = isset($inputs['telephone_number']) ? $inputs['telephone_number'] : '';
                $OfficeSeat->number_of_spare_power_sockets = isset($inputs['number_of_spare_power_sockets']) ? $inputs['number_of_spare_power_sockets'] : '';

            }
            $OfficeSeat->meeting_indicator_mounted_on_wall = isset($inputs['meeting_indicator_mounted_on_wall']) ? 1 : 0;

        }

        if ($OfficeSeat->save()) {

            if ($inputs['seat_type'] == '2') {
                $this->send_email_block_seat($seatid);
            }

            $response = [
                'success' => true,
                'message' => 'Office seat Updated success',
                'status' => 0,
            ];
        } else {
            return back()->with('error', 'Office seat updated failed,please try again');
        }

        return response()->json($response, 200);
    }

    public function send_email_block_seat($seat_id)
    {
        $logo = env('Logo');
        if ($logo) {
            $Admin = User::where('role', '1')->first();
            $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

        } else {
            $logo_url = asset('front_end/images/logo.png');
        }
        $todaydate = date('Y-m-d');
        $ReserveSeatData = DB::table('reserve_seats as rs')
            ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name', 'u.email')
            ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
            ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
            ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
            ->whereNull('rs.deleted_at')
            ->whereNotIn('rs.status', ['2', '3'])
            ->where('rs.reserve_date', '>=', $todaydate)
            ->where('rs.seat_id', $seat_id)
            ->get();

        foreach ($ReserveSeatData as $key => $value) {

            $mailData = array(
                'first_name' => $value->user_name,
                'email' => $value->email,
                'user_name' => $value->user_name,
                'form_name' => 'paul@datagov.ai',
                'schedule_name' => 'weBOOK',
                'template' => 'admin_reservation_cancel',
                'subject' => 'weBOOK Reservation Cancelled Due To Blocked Seat',
                'data' => $value,
                'logo_url' => $logo_url,
            );
            if (!empty($mailData) && !empty($value->email && !is_null($value->email))) {
                Mail::to($value->email)->send(new NotifyMail($mailData));
            }

            $seat_info = ReserveSeat::where('reserve_seat_id', $value->reserve_seat_id)->first();
            $seat_info->status = '2';
            $seat_info->save();
        }
    }

    /**
     * [deleteAsset description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function deleteSeat(Request $request, $assets_id, $dots_id)
    {
        $OfficeSeat = OfficeSeat::where('office_asset_id', $assets_id)->where('dots_id', $dots_id);
        $OfficeSeat->delete();

        $response = [
            'success' => true,
            'message' => 'Office seat Removed success',
        ];

        return response()->json($response, 200);
    }

    public function getAssetsSeats($assets_id, $dots_id)
    {

        $OfficeSeat = OfficeSeat::where('office_asset_id', $assets_id)->where('dots_id', $dots_id)->whereNull('deleted_at')->first();
        $officeAsset = OfficeAsset::find($assets_id);
        $seat_id = '';
        $counts = '';
        $status = "";
        $seat_type = "";
        $asset_type = "";

        if (isset($OfficeSeat)) {
            $seat_count = $OfficeSeat->count();
            if ($seat_count > 0) {
                $counts = true;
                $seat_id = $OfficeSeat->seat_id;
                $status = $OfficeSeat->status;
                $seat_type = $OfficeSeat->seat_type;
                $asset_type = $officeAsset->asset_type;
            } else {
                $counts = false;
            }
        }

        $response = [
            'success' => true,
            'seat_count' => $counts,
            'seat_id' => $seat_id,
            'status' => $status,
            'seat_type' => $seat_type,
            'asset_type' => $asset_type,
        ];

        return response()->json($response, 200);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function question_logic(Request $request, $office_assets_id)
    {
        $office_assets = OfficeAsset::find($office_assets_id);

        $question = '';
        if (isset($office_assets->quesionaire_id) && $office_assets->quesionaire_id != '') {
            $question = Question::whereIn('quesionaire_id', json_decode($office_assets->quesionaire_id))->get();

        } else {
            $question = Question::get();
        }

        $quesionaire = Quesionaire::get();

        if (isset($office_assets->quesionaire_id) && $office_assets->quesionaire_id != '') {
            $quesionaire_id = array_values(json_decode($office_assets->quesionaire_id, true));
            $response = [
                'success' => true,
                'quesionaire' => $quesionaire_id,
                'html' => view($this->viewPath . 'question_logic_edit', compact('question', 'quesionaire', 'office_assets_id', 'office_assets', 'quesionaire_id'))->render(),
            ];

        } else {
            $response = [
                'success' => true,
                'html' => view($this->viewPath . 'question_logic', compact('question', 'quesionaire', 'office_assets_id'))->render(),
            ];
        }

        return response()->json($response, 200);

    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function get_question_list(Request $request)
    {
        $inputs = $request->all();
        if ($inputs['questionarie']) {
            $columns = ['quesionaire.title', 'questions.id', 'questions.question', 'questions.quesionaire_id'];
            $question = Question::select($columns)->leftJoin("quesionaire", "questions.quesionaire_id", "quesionaire.id")->whereIn('quesionaire_id', $inputs['questionarie'])->get();

            $response = [
                'success' => true,
                // 'html' => view($this->viewPath . 'question_list', compact('question'))->render(),
            ];

        } else {
            $response = [
                'success' => false,
                'messages' => 'Please select questions',
            ];
        }
        return response()->json($response, 200);

    }

    /**
     * [save_question_logic description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function save_question_logic(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            // 'logic' => 'required',
            'quesionaire_id' => 'required',

        ];

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $question_logic = OfficeAsset::find($inputs['office_assets_id']);
        // $question_logic->logic = json_encode($values);
        $question_logic->quesionaire_id = json_encode($inputs['quesionaire_id']);
        if ($question_logic->save()) {
            $response = [
                'success' => true,
                'message' => 'Questionarie updated successfull',
            ];
        } else {
            return back()->with('error', 'Questionarie updated failed,please try again');
        }

        return response()->json($response, 200);

    }

    public function block_notification($assets_id, $seat_id)
    {
        $modal_type = "seat_block_notification";
        $response = [
            'success' => true,
            'html' => view('modal_content', compact('modal_type', 'assets_id', 'seat_id'))->render(),
        ];

        return response()->json($response, 200);
    }

}
