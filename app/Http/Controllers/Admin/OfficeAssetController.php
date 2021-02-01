<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeImage;
use App\Models\OfficeSeat;
use App\Models\Quesionaire;
use App\Models\Question;
use Auth;
use DB;
use Illuminate\Http\Request;
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

            $columns = ['office_asset.id', 'office_asset.is_covid_test', 'office_asset.office_id', 'office_asset.created_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description'];

            $officeAssets = OfficeAsset::select($columns)->leftJoin("offices", "offices.office_id", "office_asset.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->whereRaw($whereStr, $whereParams)->orderBy('id', 'desc');

            if (isset($office_id) && $office_id != "") {
                $officeAssets = $officeAssets->where("office_asset.office_id", $office_id);

            }

            if ($officeAssets) {
                $total = $officeAssets->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $officeAssets = $officeAssets->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
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
                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['title'] = $value->title;
                $final[$key]['total_seats'] = count($total_seats);
                $final[$key]['total_quesionaire'] = $total_quesionaire;
                $final[$key]['is_covid_test'] = $value->is_covid_test;
                $final[$key]['created_at'] = date('d-m-Y H:i:s', strtotime($value->created_at));
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }

        $buildings = Building::whereNull('deleted_at')->get();
        return view($this->viewPath . 'index', compact('buildings'));
    }

    /**
     * [saveOfficeAsset description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveOfficeAsset(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'building_id' => 'required',
            'office_id' => 'required',
            'title' => 'required',
            'preview_image' => 'required',

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

        $OfficeAsset = new OfficeAsset();
        $OfficeAsset->user_id = Auth::id();
        $OfficeAsset->building_id = $inputs['building_id'];
        $OfficeAsset->office_id = $inputs['office_id'];
        $OfficeAsset->title = $inputs['title'];
        $OfficeAsset->description = $inputs['description'];

        $OfficeAsset->preview_image = $fileName ?? NUll;
        if ($OfficeAsset->save()) {
            $response = [
                'success' => true,
                'message' => 'Office Asset Added success',
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

        $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);

        $response = [
            'success' => true,
            'html' => view($this->viewPath . 'edit', compact('officeAsset', 'buildings', 'Office', 'assets_image'))->render(),
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

        $rules = [
            'building_id' => 'required',
            'office_id' => 'required',
            'title' => 'required',
            'preview_image' => 'required',

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

        return $fileName;
        
        $preview_image = $fileName;
        $OfficeAsset = OfficeAsset::find($assetId);
        $OfficeAsset->user_id = Auth::id();
        $OfficeAsset->building_id = $inputs['building_id'];
        $OfficeAsset->office_id = $inputs['office_id'];
        $OfficeAsset->title = $inputs['title'];

        $OfficeAsset->description = $inputs['description'];

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
        $officeAsset = OfficeAsset::find($assetId);
        $officeAsset->delete();

        $response = [
            'success' => true,
        ];

        return response()->json($response, 200);
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

        $rules = [
            'building_id' => 'required',
            'seat_no' => 'required',
            'booking_mode' => 'required',
            'seat_type' => 'required',
            'is_show_user_details' => 'required',
            'is_show_user_details' => 'required',
            'status' => 'required',
            'preview_seat_image' => 'required',
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

        $image_64 = null;
        $image_64 = $inputs['preview_seat_image'];

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = str_random('10') . '_' . time() . '.' . $extension;
        $destinationPath = ImageHelper::$getOfficeAssetsImagePath;

        $uploadPath = $destinationPath . '/' . $imageName;

        if (file_put_contents($uploadPath, base64_decode($image))) {
            $preview_image = $imageName;
        }

        $OfficeSeat = new OfficeSeat();
        $OfficeSeat->building_id = $inputs['building_id'];
        $OfficeSeat->office_asset_id = $inputs['asset_id'];
        $OfficeSeat->office_id = $inputs['office_id'];
        $OfficeSeat->seat_no = $inputs['seat_no'];
        $OfficeSeat->description = $inputs['description'];
        $OfficeSeat->booking_mode = $inputs['booking_mode'];
        $OfficeSeat->seat_type = $inputs['seat_type'];
        $OfficeSeat->is_show_user_details = $inputs['is_show_user_details'];
        $OfficeSeat->status = $inputs['status'];
        $OfficeSeat->dots_id = $inputs['dots_id'];

        if ($OfficeSeat->save()) {
            $OfficeImage = new OfficeImage();
            $OfficeImage->office_id = $inputs['office_id'];
            $OfficeImage->seat_id = $OfficeSeat->seat_id;
            $OfficeImage->building_id = $inputs['building_id'];
            $OfficeImage->office_asset_id = $inputs['asset_id'];
            $OfficeImage->image = $preview_image;
            $OfficeImage->save();

            $response = [
                'success' => true,
                'message' => 'Office seat Added success',
                'id' => $OfficeSeat->seat_id,
                'assetId' => $inputs['asset_id'],
                'dotsId' => $inputs['dots_id'],
                'status' => $inputs['status'],
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
            $seatid = 1;
        }

        $response = [
            'success' => true,
            'data' => $officeAsset,
            'assets_image' => $assets_image,
            'last_id' => $seatid,
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

        $officeseat = OfficeSeat::where('seat_id', $seatid)->first();
        $officeseat_image = DB::table('office_images')->where('seat_id', $seatid)->where('office_id', $officeseat->office_id)->first();

        $seat_image = ImageHelper::getOfficeAssetsImage($officeseat_image->image);

        $response = [
            'success' => true,
            'html' => view($this->viewPath . 'editofficeseats', compact('officeseat', 'seat_image'))->render(),
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

        $rules = [
            'seat_no' => 'required',
            'booking_mode' => 'required',
            'seat_type' => 'required',
            'is_show_user_details' => 'required',
            'is_show_user_details' => 'required',
            'status' => 'required',
            'preview_seat_image' => 'required',
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

        $image_64 = null;
        $image_64 = $inputs['preview_seat_image'];

        $officeseat = OfficeSeat::where('seat_id', $seatid)->first();
        $officeseat_image = DB::table('office_images')->where('seat_id', $seatid)->where('office_id', $officeseat->office_id)->first();

        $oldimage = ImageHelper::getOfficeAssetsImage($officeseat_image->image);

        //$oldimage = DB::table('office_images')->where('seat_id', $seatid)->first();
        $new_image = explode('office_asset/', $inputs['preview_seat_image']);
        $oldimage_new = explode('office_asset/', $oldimage);

        if (isset($new_image[1]) && ($new_image[1] == $oldimage_new[1])) {
        } else {

            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

            $image = str_replace($replace, '', $image_64);

            $image = str_replace(' ', '+', $image);

            $imageName = str_random('10') . '_' . time() . '.' . $extension;
            $destinationPath = ImageHelper::$getOfficeAssetsImagePath;

            $uploadPath = $destinationPath . '/' . $imageName;
            if (file_put_contents($uploadPath, base64_decode($image))) {
                $preview_image = $imageName;

            }
        }

        $OfficeSeat = OfficeSeat::where('seat_id', $seatid)->first();
        $OfficeSeat->building_id = $inputs['building_id'];
        $OfficeSeat->office_asset_id = $inputs['office_asset_id'];
        $OfficeSeat->office_id = $inputs['office_id'];
        $OfficeSeat->seat_no = $inputs['seat_no'];
        $OfficeSeat->description = $inputs['description'];
        $OfficeSeat->booking_mode = $inputs['booking_mode'];
        $OfficeSeat->seat_type = $inputs['seat_type'];
        $OfficeSeat->is_show_user_details = $inputs['is_show_user_details'];
        $OfficeSeat->status = $inputs['status'];
        if ($OfficeSeat->save()) {

            if (isset($preview_image)) {
                $OfficeImage = OfficeImage::where('office_id', $inputs['office_id'])->where('seat_id', $seatid)->first();
                $OfficeImage->image = $preview_image;
                $OfficeImage->save();

            }

            $response = [
                'success' => true,
                'message' => 'Office seat Updated success',
                'status' => $inputs['status'],
            ];
        } else {
            return back()->with('error', 'Office seat updated failed,please try again');
        }

        return response()->json($response, 200);
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

        $seat_id = '';
        $counts = '';
        $status = "";
        $seat_type = "";

        if (isset($OfficeSeat)) {
            $seat_count = $OfficeSeat->count();
            if ($seat_count > 0) {
                $counts = true;
                $seat_id = $OfficeSeat->seat_id;
                $status = $OfficeSeat->status;
                $seat_type = $OfficeSeat->seat_type;
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
        ];

        return response()->json($response, 200);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function question_logic()
    {

        $question = Question::get();
        $quesionaire = Quesionaire::get();

        $response = [
            'success' => true,
            'html' => view($this->viewPath . 'question_logic', compact('question', 'quesionaire'))->render(),
        ];

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
        $question = Question::where('quesionaire_id', $inputs['questionarie'])->get();

        $response = [
            'success' => true,
            'html' => view($this->viewPath . 'question_list', compact('question'))->render(),
        ];

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
            'logic' => 'required',

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

        $values = array();
        foreach ($inputs['logic'] as $key => $value) {
            if ($value != '') {
                $values[] .= $value;
            }
        }

        $question_logic = OfficeAsset::find($office_asset_id);
        $question_logic->covid_logic = json_encode($values);
        if ($question_logic->save()) {
            $response = [
                'success' => true,
                'message' => 'Question Logic Added successfull',
            ];
        } else {
            return back()->with('error', 'Question Logic added failed,please try again');
        }

        return response()->json($response, 200);

    }

}
