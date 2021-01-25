<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\DeployLabel;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeSeat;
use App\Models\SeatLabel;
use Auth;
use Illuminate\Http\Request;
use Validator;

class SeatLabelController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND offices.office_name like '%{$search}%'";
                $whereStr .= " OR buildings.building_name like '%{$search}%'";
                $whereStr .= " OR offices.office_number like '%{$search}%'";
            }

            $columns = ['seat_label.id', 'offices.office_id', 'offices.updated_at', 'offices.created_at', 'offices.office_name as office_name', 'offices.office_number as office_number', 'buildings.building_name as building_name', 'office_asset.title', 'seat_label.scan', 'seat_label.nfc', 'seat_label.label_order_date'];

            $SeatLabel = SeatLabel::select($columns)->leftJoin("seats", "seats.seat_id", "seat_label.seat_id")->leftJoin("office_asset", "office_asset.id", "seat_label.office_asset_id")->leftJoin("offices", "offices.office_id", "seat_label.office_id")->leftJoin("buildings", "buildings.building_id", "seat_label.building_id")->whereRaw($whereStr, $whereParams);

            $SeatLabel = $SeatLabel->orderBy('seat_label.id', 'desc');

            if ($SeatLabel) {
                $total = $SeatLabel->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $SeatLabel = $SeatLabel->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $SeatLabel = $SeatLabel->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $SeatLabel = $SeatLabel->get();

            $final = [];
            $number_key = 1;
            foreach ($SeatLabel as $key => $value) {
                $DeployLabelall = '';
                $config = '';
                $status = '';
                $DeployLabelall = DeployLabel::where('seat_label_id', $value->id)->get();
                $Deploystatus = DeployLabel::where('seat_label_id', $value->id)->where('status', '1')->get();
                $Deploysdeploy = DeployLabel::where('seat_label_id', $value->id)->where('status', '2')->get();

                if ($Deploystatus->count() == $DeployLabelall->count()) {
                    $status = '<a data-id="' . $value->id . '" class="btn btn-link view_deploy_label"> Ordered </a>';
                } elseif (count($DeployLabelall) == count($Deploysdeploy)) {

                    if (isset($DeployLabelall) && $DeployLabelall != '') {
                        foreach ($DeployLabelall as $office_key => $office_value) {
                            $seats_labels = SeatLabel::where('id', $office_value->seat_label_id)->first();
                            $officeassets_info = OfficeAsset::where('id', $office_value->office_asset_id)->first();

                            if (isset($officeassets_info) && $seats_labels->nfc == 1) {
                                $officeassets_info->nfc = 1;
                                $officeassets_info->save();
                            }

                            if (isset($officeassets_info) && $seats_labels->scan == 1) {
                                $officeassets_info->qr = 1;
                                $officeassets_info->save();
                            }

                        }
                    }

                    $status = '<a data-id="' . $value->id . '" class="btn btn-link view_deploy_label"> Deployed </a>';
                } elseif (count($Deploystatus) != count($DeployLabelall)) {
                    $status = '<a data-id="' . $value->id . '" class="btn btn-link view_deploy_label"> Deploy </a>';
                }

                if ($value->scan == 1) {
                    $config .= '<img alt="QR Code" src="' . asset('admin_assets') . '/images/scan.png" class="menu_icons bl-icon"> ';
                }
                if ($value->nfc == 1) {
                    $config .= '<img  alt="NFC Code" src="' . asset('admin_assets') . '/images/nfc.png" class="menu_icons bl-icon"> ';
                }

                $final[$key]['number_key'] = $value->id;
                $final[$key]['searial_no'] = $value->serialno;
                $final[$key]['order_date'] = date('d/m/Y', strtotime($value->label_order_date));
                $final[$key]['no_labels'] = count($DeployLabelall);
                $final[$key]['config'] = $config;
                $final[$key]['status'] = $status;
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();

        $buildings = Building::whereNull('deleted_at')->get();

        return view('admin.seat_label.index', compact('data', 'buildings'));

    }

    public function get_deploy_info($id)
    {
        $SeatLabel = SeatLabel::where('id', $id)->first();
        $deploy_total = DeployLabel::where('seat_label_id', $id)->count();
        $response = [
            'success' => true,
            'html' => view('admin.seat_label.show', compact('SeatLabel', 'deploy_total'))->render(),
        ];

        return response()->json($response, 200);

    }

    public function deploy_label(Request $request)
    {
        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->sSearch) && $request->sSearch != "") {
                $search = trim(addslashes($request->sSearch));
                $whereStr .= " AND offices.office_name like '%{$search}%'";
                $whereStr .= " OR buildings.building_name like '%{$search}%'";
                $whereStr .= " OR offices.office_number like '%{$search}%'";
                $whereStr .= " OR office_asset.title like '%{$search}%'";
                $whereStr .= " OR seats.seat_no like '%{$search}%'";
            }

            $columns = ['deploy_label.id', 'deploy_label.seat_id', 'seat_label.id as seat_label_id', 'offices.office_id', 'offices.updated_at', 'offices.created_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'deploy_label.status', 'seats.seat_no'];

            $DeployLabel = DeployLabel::select($columns)->leftJoin("seat_label", "seat_label.id", "deploy_label.seat_label_id")->leftJoin("seats", "seats.seat_id", "deploy_label.seat_id")->leftJoin("office_asset", "office_asset.id", "deploy_label.office_asset_id")->leftJoin("offices", "offices.office_id", "deploy_label.office_id")->leftJoin("buildings", "buildings.building_id", "deploy_label.building_id")->whereRaw($whereStr, $whereParams);

            if (isset($request->label_id) && $request->label_id != "") {
                $DeployLabel = $DeployLabel->where("deploy_label.seat_label_id", $request->label_id);
            }

            $DeployLabel = $DeployLabel->orderBy('deploy_label.id', 'desc');

            if ($DeployLabel) {
                $total = $DeployLabel->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $DeployLabel = $DeployLabel->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $DeployLabel = $DeployLabel->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $DeployLabel = $DeployLabel->get();

            $final = [];
            $number_key = 1;
            $deploy_status = array('1' => 'Not Deployed', '2' => 'Deployed', '3' => 'Activated');
            foreach ($DeployLabel as $key => $value) {
                $DeployLabelall = '';
                $config = '';
                $final[$key]['number_key'] = $value->id;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['title'] = $value->title;
                $final[$key]['seat'] = $value->seat_no;
                $final[$key]['status'] = @$deploy_status[$value->status];
                $final[$key]['deploy_status'] = $value->status;
                $final[$key]['seat_label_id'] = $value->seat_label_id;
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin.seat_label.create');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {

        $inputs = $request->all();

        $SeatLabel = new SeatLabel();
        $SeatLabel->building_id = isset($inputs['building_id']) ? $inputs['building_id'] : '';
        $SeatLabel->office_id = isset($inputs['office_id']) ? $inputs['office_id'] : '';
        $SeatLabel->office_asset_id = isset($inputs['office_asset_id']) ? $inputs['office_asset_id'] : '';
        $SeatLabel->seat_id = isset($inputs['seat_id']) ? $inputs['seat_id'] : '';
        $SeatLabel->label_order_date = date('Y-m-d');
        $SeatLabel->scan = isset($inputs['scan']) ? 1 : 0;
        $SeatLabel->nfc = isset($inputs['nfc']) ? 1 : 0;
        if ($SeatLabel->save()) {

            if ($SeatLabel->building_id == '' && $inputs['office_id'] == '' && $inputs['office_asset_id'] == '' && $inputs['seat_id'] == '') {
                $seats = OfficeSeat::all();

                if ($seats) {
                    foreach ($seats as $key => $value) {

                        $officeassets = OfficeAsset::where('id', $value->office_asset_id)->first();

                        $deploylabel = new DeployLabel();
                        $deploylabel->seat_label_id = $SeatLabel->id;
                        $deploylabel->building_id = $officeassets['building_id'];
                        $deploylabel->office_id = $officeassets['office_id'];
                        $deploylabel->office_asset_id = $value->office_asset_id;
                        $deploylabel->seat_id = $value->seat_id;
                        $deploylabel->status = 1;
                        $deploylabel->save();
                    }

                }
            } elseif ($inputs['building_id'] != '' && $inputs['office_id'] == '' && $inputs['office_asset_id'] == '' && $inputs['seat_id'] == '') {
                $officeassets = OfficeAsset::where('building_id', $inputs['building_id'])->first();
                $seats = OfficeSeat::where('office_asset_id', $officeassets->id)->get();
                if ($seats) {
                    foreach ($seats as $key => $value) {

                        $deploylabel = new DeployLabel();
                        $deploylabel->seat_label_id = $SeatLabel->id;
                        $deploylabel->building_id = $officeassets['building_id'];
                        $deploylabel->office_id = $officeassets['office_id'];
                        $deploylabel->office_asset_id = $value->office_asset_id;
                        $deploylabel->seat_id = $value->seat_id;
                        $deploylabel->status = 1;
                        $deploylabel->save();
                    }

                }
            } elseif ($inputs['building_id'] != '' && $inputs['office_id'] != '' && $inputs['office_asset_id'] == '' && $inputs['seat_id'] == '') {
                $officeassets = OfficeAsset::where('building_id', $inputs['building_id'])->where('office_id', $inputs['office_id'])->first();

                $seats = OfficeSeat::where('office_asset_id', $officeassets->id)->get();
                if ($seats) {
                    foreach ($seats as $key => $value) {

                        $deploylabel = new DeployLabel();
                        $deploylabel->seat_label_id = $SeatLabel->id;
                        $deploylabel->building_id = $officeassets['building_id'];
                        $deploylabel->office_id = $officeassets['office_id'];
                        $deploylabel->office_asset_id = $value->office_asset_id;
                        $deploylabel->seat_id = $value->seat_id;
                        $deploylabel->status = 1;
                        $deploylabel->save();
                    }

                } else {
                    return back()->with('error', 'Office not added');
                }
            } elseif ($inputs['building_id'] != '' && $inputs['office_id'] != '' && $inputs['office_asset_id'] != '' && $inputs['seat_id'] == '') {
                $officeassets = OfficeAsset::where('building_id', $inputs['building_id'])->where('office_id', $inputs['office_id'])->where('id', $inputs['office_asset_id'])->first();
                $seats = OfficeSeat::where('office_asset_id', $officeassets->id)->get();
                if ($seats) {
                    foreach ($seats as $key => $value) {

                        $deploylabel = new DeployLabel();
                        $deploylabel->seat_label_id = $SeatLabel->id;
                        $deploylabel->building_id = $officeassets['building_id'];
                        $deploylabel->office_id = $officeassets['office_id'];
                        $deploylabel->office_asset_id = $value->office_asset_id;
                        $deploylabel->seat_id = $value->seat_id;
                        $deploylabel->status = 1;
                        $deploylabel->save();
                    }

                } else {
                    return back()->with('error', 'office assets not added');
                }
            } elseif ($inputs['building_id'] != '' && $inputs['office_id'] != '' && $inputs['office_asset_id'] != '' && $inputs['seat_id'] != '') {
                $officeassets = OfficeAsset::where('id', $inputs['office_asset_id'])->first();
                $seats = OfficeSeat::where('seat_id', $inputs['seat_id'])->get();
                if ($seats) {
                    $deploylabel = new DeployLabel();
                    $deploylabel->seat_label_id = $SeatLabel->id;
                    $deploylabel->building_id = $officeassets['building_id'];
                    $deploylabel->office_id = $officeassets['office_id'];
                    $deploylabel->office_asset_id = $inputs['office_asset_id'];
                    $deploylabel->seat_id = $inputs['seat_id'];
                    $deploylabel->status = 1;
                    $deploylabel->save();
                } else {
                    return back()->with('error', 'office assets not added');
                }
            }

            $response = [
                'success' => true,
                'message' => 'SeatLabel Added success',
            ];
        } else {
            return back()->with('error', 'SeatLabel added failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id)
    {
        return view('admin.seat_label.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $seat_label = Building::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.seat_label.edit', compact('seat_label'))->render(),
        ];

        return response()->json($response, 200);
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $rules = [
            'seat_label_name' => 'required',
            'seat_label_address' => 'required',
            'description' => 'required',
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

        $Building = Building::find($id);
        $Building->user_id = Auth::id();
        $Building->seat_label_name = $inputs['seat_label_name'];
        $Building->seat_label_address = $inputs['seat_label_address'];
        $Building->description = $inputs['description'];
        if ($Building->save()) {
            $response = [
                'success' => true,
                'message' => 'Building Updated success',
            ];
        } else {
            return back()->with('error', 'Building update failed,please try again');
        }

        return response()->json($response, 200);
    }

    public function filterofficeList(Request $request, $building_id)
    {
        $Office = Office::where('building_id', $building_id)->get();
        if ($Office->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $Office]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function filterofficeassetsList(Request $request, $office_id)
    {
        $OfficeAsset = OfficeAsset::where('office_id', $office_id)->get();
        if ($OfficeAsset->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $OfficeAsset]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function filterseatslist(Request $request, $office_assets_id)
    {
        $OfficeSeat = OfficeSeat::where('office_asset_id', $office_assets_id)->get();
        if ($OfficeSeat->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $OfficeSeat]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function change_deploy_seat(Request $request, $deploy_id)
    {
        $deploy_label = DeployLabel::where('id', $deploy_id)->first();
        $deploy_label->status = '2';

        $seat_info = OfficeSeat::where('seat_id', $deploy_label->seat_id)->first();

        if ($deploy_label->save()) {

            return response(['status' => true, 'message' => 'Deployed Successfull']);
        } else {
            return response(['status' => false, 'message' => 'Deploy not Successfull']);
        }
    }

}
