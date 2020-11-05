<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeImage;
use App\Models\OfficeSeat;
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
    public function index(Request $request)
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

            $columns = ['office_asset.id', 'office_asset.office_id', 'office_asset.created_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description'];

            $officeAssets = OfficeAsset::select($columns)->leftJoin("offices", "offices.office_id", "office_asset.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->whereRaw($whereStr, $whereParams)->orderBy('id', 'desc');

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

            foreach ($officeAssets as $key => $value) {

                $total_seats = OfficeSeat::where('office_id', $value->office_id)->whereNull('deleted_at')->get();

                $final[$key]['id'] = $value->id;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['title'] = $value->title;
                $final[$key]['total_seats'] = count($total_seats);
                $final[$key]['created_at'] = date('d-m-Y H:i:s', strtotime($value->created_at));
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
     * [addAsset description]
     * @param Request $request [description]
     */
    public function addAsset(Request $request)
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

        $image_64 = null;
        $image_64 = $inputs['preview_image'];

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

        $OfficeAsset = new OfficeAsset();
        $OfficeAsset->building_id = $inputs['building_id'];
        $OfficeAsset->office_id = $inputs['office_id'];
        $OfficeAsset->title = $inputs['title'];
        $OfficeAsset->description = $inputs['description'];
        $OfficeAsset->preview_image = $preview_image;
        if ($OfficeAsset->save()) {
            $response = [
                'success' => true,
                'message' => 'Office Asset Added success',
            ];
        } else {
            return back()->with('error', 'Building added failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [saveOfficeAsset description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function saveOfficeAsset(Request $request)
    {
        $rules = [
            'office_id' => ['required'],
            'building_id' => ['required'],
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

        $officeAsset = new OfficeAsset();
        $officeAsset->building_id = $request->building_id;
        $officeAsset->office_id = $request->office_id;
        $officeAsset->title = $request->title;
        $officeAsset->description = $request->description;
        $officeAsset->preview_image = $request->preview_image;
        $officeAsset->save();

        $response = [
            'success' => true,
        ];

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

        if ($inputs['preview_image']) {

            $oldimage = OfficeAsset::find($assetId);
            $new_image = explode('office_asset/', $inputs['preview_image']);

            if (isset($new_image[1]) && ($oldimage->preview_image == $new_image[1])) {
            } else {
                $image_64 = null;
                $image_64 = $inputs['preview_image'];

                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = str_random('10') . '_' . time() . '.' . $extension;
                $destinationPath = ImageHelper::$getOfficeAssetsImagePath;

                $uploadPath = $destinationPath . '/' . $imageName;

                //remove old image
                $this->remove_office_assets_image($assetId);

                if (file_put_contents($uploadPath, base64_decode($image))) {
                    $preview_image = $imageName;
                }
            }
        }

        $OfficeAsset = OfficeAsset::find($assetId);
        $OfficeAsset->building_id = $inputs['building_id'];
        $OfficeAsset->office_id = $inputs['office_id'];
        $OfficeAsset->title = $inputs['title'];
        $OfficeAsset->description = $inputs['description'];
        if (isset($preview_image)) {
            $OfficeAsset->preview_image = $preview_image;
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
     * [get buildings description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function getoffices(Request $request, $building_id)
    {

        $office = Office::where('building_id', $building_id)->get();

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

        $columns = ['office_asset.id', 'buildings.building_id', 'office_asset.preview_image', 'office_asset.office_id', 'office_asset.created_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description'];
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

        $image_64 = null;
        $image_64 = $inputs['preview_image'];

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
            ];
        } else {
            return back()->with('error', 'Office seat failed,please try again');
        }

        return response()->json($response, 200);
    }

}
