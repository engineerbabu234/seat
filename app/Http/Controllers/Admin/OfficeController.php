<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\Building;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeSeat;
use App\Models\ReserveSeat;
use App\Models\Seat;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class OfficeController extends Controller
{

    /**
     * [index description]
     * @param  Request $request    [description]
     * @param  [type]  $buildingId [description]
     * @return [type]              [description]
     */
    public function index(Request $request, $buildingId = null)
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

            $columns = ['offices.office_id', 'offices.updated_at', 'offices.created_at', 'offices.office_name as office_name', 'offices.office_number as office_number', 'buildings.building_name as building_name', 'offices.description'];

            if (isset($buildingId) && $buildingId != "") {
                $Office = Office::select($columns)->leftJoin("buildings", "buildings.building_id", "offices.building_id")->whereRaw($whereStr, $whereParams);
                $Office = $Office->where("offices.building_id", $buildingId);

            } else {
                $Office = Office::select($columns)->leftJoin("buildings", "buildings.building_id", "offices.building_id")->whereRaw($whereStr, $whereParams);

            }

            $Office = $Office->orderBy('office_id', 'desc');

            if ($Office) {
                $total = $Office->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $Office = $Office->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Office = $Office->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $Office = $Office->get();

            $final = [];
            $number_key = 1;

            foreach ($Office as $key => $value) {

                $total_assets = OfficeAsset::where('office_id', $value->office_id)->get();

                $final[$key]['number_key'] = $number_key;
                $final[$key]['office_id'] = $value->office_id;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['office_number'] = $value->office_number;
                $final[$key]['seats'] = count($total_assets);
                $final[$key]['updated_at'] = date('d/m/Y', strtotime($value->updated_at));
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

        return view('admin.office.index', compact('data', 'buildings'));
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $buildings = Building::whereNull('deleted_at')->get();
        $data = array('buildings' => $buildings);
        return view('admin.office.create', compact('data'));
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $rules = [
            'building_id' => 'required',
            'office_name' => 'required',
            'office_number' => 'required',
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

        $OfficeCount = DB::table('offices')->whereNull('deleted_at')->count();
        $TOTAL_MAX_OFFICES = env('TOTAL_MAX_OFFICES');
        if (isset($TOTAL_MAX_OFFICES) && $OfficeCount >= $TOTAL_MAX_OFFICES) {
            return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_OFFICES . ' office'];
        }

        $Office = new Office();
        $Office->user_id = Auth::id();
        $Office->building_id = $inputs['building_id'];
        $Office->office_name = $inputs['office_name'];
        $Office->office_number = $inputs['office_number'];
        $Office->description = $inputs['description'];
        if ($Office->save()) {
            $response = [
                'success' => true,
                'message' => 'Office Added successfull',
            ];
        } else {
            return back()->with('error', 'Office added failed,please try again');
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
        if ($request->ajax()) {
            $seats = DB::table('seats as s')
                ->select('s.*')
            /*->leftJoin('users as u','u.id', '=','t.user_id')
            ->leftJoin('users as d','d.id', '=','t.driver_id')*/
                ->where('s.office_id', $id)
                ->whereNull('s.deleted_at')
                ->orderBy('s.seat_id', 'asc')
                ->get();
            $number_key = 1;
            foreach ($seats as $key => $value) {
                $value->number_key = $number_key;
                $number_key++;
                $created_date = date('d/m/Y', strtotime($value->created_at));
                $created_time = date('H:i
 A', strtotime($value->created_at));
                $value->created_at = $created_date . ',' . $created_time;
            }
            return datatables()->of($seats)->make(true);
        }

        $office = Office::find($id);
        if ($office) {
            $total_seats = Seat::where('office_id', $office->office_id)->count();
            $blocked_seat = Seat::where('office_id', $office->office_id)->where('seat_type', '2')->count();
            $reserved_seat = ReserveSeat::where('office_id', $office->office_id)->whereIn('status', ['1', '4'])->count();
            $available_seat = $total_seats - $reserved_seat - $blocked_seat;
            $office->total_seats = $total_seats;
            $office->blocked_seat = $blocked_seat;
            $office->reserved_seat = $reserved_seat;
            $office->available_seat = $available_seat;
        }
        $data['js'] = ['office/show.js'];
        $data['office'] = $office;
        //$data = array('office'=>$office);
        return view('admin.office.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit1($id)
    {
        $office = Office::find($id);
        $buildings = Building::get();
        if ($buildings->toArray()) {
            foreach ($buildings as $key => $building) {
                if ($office->building_id == $building->building_id) {
                    $buildings[$key]->selected = true;
                } else {
                    $buildings[$key]->selected = false;
                }

            }
        }
        $data = array('buildings' => $buildings, 'office' => $office);
        return view('admin.office.edit', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $office = Office::find($id);
        $buildings = Building::whereNull('deleted_at')->get();
        $response = [
            'success' => true,
            'html' => view('admin.office.edit', compact('office', 'buildings'))->render(),
        ];

        return response()->json($response, 200);

    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {

        $inputs = $request->all();
        $rules = [
            'building_id' => 'required',
            'office_name' => 'required',
            'office_number' => 'required',
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

        $OfficeCount = DB::table('offices')->whereNull('deleted_at')->count();
        $TOTAL_MAX_OFFICES = env('TOTAL_MAX_OFFICES');
        if (isset($TOTAL_MAX_OFFICES) && $OfficeCount >= $TOTAL_MAX_OFFICES) {
            return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_OFFICES . ' office'];
        }

        $Office = Office::find($id);
        $Office->user_id = Auth::id();
        $Office->building_id = $inputs['building_id'];
        $Office->office_name = $inputs['office_name'];
        $Office->office_number = $inputs['office_number'];
        $Office->description = $inputs['description'];
        if ($Office->save()) {
            $response = [
                'success' => true,
                'message' => 'Office Updated successfull',
            ];
        } else {
            return back()->with('error', 'Office added failed,please try again');
        }

        return response()->json($response, 200);

    }

    /**
     * [orderStatusChange description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function orderStatusChange(Request $request)
    {
        $inputs = $request->all();
        $status = $inputs['status'];

        $Order = Order::find($inputs['order_id']);
        $Order->status = $inputs['status'];
        if ($Order->update()) {
            if ($status == 0) {
                return ['status' => 'success', 'message' => 'Order pending successfully', 'data' => $Order];
            } elseif ($status == 1) {
                return ['status' => 'success', 'message' => 'Order completed successfully', 'data' => $Order];
            } elseif ($status == 2) {
                return ['status' => 'success', 'message' => 'Order Rejecet successfully', 'data' => $Order];
            } else {
                return ['status' => 'success', 'message' => 'Order other successfully', 'data' => $Order];
            }
        } else {
            return ['status' => 'failed', 'message' => 'Status updated failed'];
        }
    }

    /**
     * [orderZoneChange description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function orderZoneChange(Request $request)
    {
        $inputs = $request->all();
        $zone_id = $inputs['zone_id'];

        $Order = Order::find($inputs['order_id']);
        $Order->zone_id = $inputs['zone_id'];
        if ($Order->update()) {
            return ['status' => 'success', 'message' => 'Zone changed successfully', 'data' => $Order];
        } else {
            return ['status' => 'failed', 'message' => 'Zone changed failed,please try again'];
        }
    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request, $id)
    {
        if (Office::find($id)->delete()) {
            $office_asstes = OfficeAsset::where('office_id', $id)->first();
            if (isset($office_asstes->id)) {
                OfficeAsset::where('office_id', $id)->delete();
                OfficeSeat::where('office_asset_id', $office_asstes->id)->delete();
                $this->send_cancle_email_delete($office_id);
            }
            return ['status' => 'success', 'message' => 'Successfully deleted office and related office asstes,seats, reserve seat Cancel'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete office and office assets'];
        }
    }

    public function send_cancle_email_delete($office_id)
    {
        $office_asstes = OfficeAsset::where('office_id', $office_id)->get();

        foreach ($office_asstes as $akey => $avalue) {
            $reserve_seat = ReserveSeat::where('office_asset_id', $avalue->id)->get();

            if ($reserve_seat) {
                foreach ($reserve_seat as $key => $value) {

                    $this->send_email_block_seat($value->reserve_seat_id);
                }
            }

        }

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
                'subject' => 'weBOOK Reservation Cancelled Due To Admin Remove Office',
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

}
