<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeAsset;
use Illuminate\Http\Request;
use Validator;

class OfficeAssetController extends Controller
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->viewPath = "office_asset.";
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

            if (isset($request->name) && $request->name != "") {
                $search = trim(addslashes($request->name));
                $whereStr .= " AND office_asset.question like '%{$search}%'";
            }

            if (isset($request->role) && $request->role != "") {
                $whereStr .= " AND office_asset.role_id IN (" . $request->role . ")";
            }

            $columns = ['office_asset.id', 'roles.name as role_name', 'office_asset.question'];

            $officeAssets = OfficeAsset::select($columns)->leftJoin("roles", "roles.id", "office_asset.role_id")->whereRaw($whereStr, $whereParams)->orderBy('id', 'desc');

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
                $final[$key]['id'] = $value->id;
                $final[$key]['role_name'] = $value->role_name;
                $final[$key]['question'] = $value->question;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
    }

    /**
     * [addAsset description]
     * @param Request $request [description]
     */
    public function addAsset(Request $request)
    {
        $response = [
            'success' => true,
            'html' => view($this->viewPath . '.add')->render(),
        ];

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

        $response = [
            'success' => true,
            'html' => view($this->viewPath . '.edit', compact('officeAsset'))->render(),
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

        $officeAsset = OfficeAsset::find($assetId);
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
}
