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
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class BuildingController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $buildings = DB::table('buildings as b')
                ->select('b.*')

            /*->leftJoin('users as u','u.id', '=','t.user_id')
            ->leftJoin('users as d','d.id', '=','t.driver_id')*/
                ->orderBy('b.building_id', 'desc')
                ->whereNull('b.deleted_at')
                ->where('b.user_id',auth::id())
                ->get();
            $number_key = 1;
            foreach ($buildings as $key => $value) {
                $value->number_key = $number_key;
                $value->updated_at = date('d/m/Y', strtotime($value->updated_at));
                $office_count = DB::table('offices as o')->where('o.building_id', $value->building_id)->whereNull('o.deleted_at')->count();
                $value->office_count = $office_count;
                $number_key++;
            }
            return datatables()->of($buildings)->make(true);
        }
        $data['js'] = ['building/index.js'];
        $data['building_count'] = DB::table('buildings as b')->whereNull('deleted_at')->count();
        return view('admin.building.index', compact('data'));
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin.building.create');
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
            'building_name' => 'required',
            'building_address' => 'required',
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

        $BuildingCount = Building::whereNull('deleted_at')->count();
        $TOTAL_MAX_BUILDINGS = env('TOTAL_MAX_BUILDINGS');
        if (isset($TOTAL_MAX_BUILDINGS) && $BuildingCount >= $TOTAL_MAX_BUILDINGS) {
            return back()->with('error', 'You can add only ' . $TOTAL_MAX_BUILDINGS . ' buildings');
        }

        $Building = new Building();
        $Building->user_id = Auth::id();
        $Building->building_name = $inputs['building_name'];
        $Building->building_address = $inputs['building_address'];
        $Building->description = $inputs['description'];
        if ($Building->save()) {
            $response = [
                'success' => true,
                'message' => 'Building Added success',
            ];
        } else {
            return back()->with('error', 'Building added failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [officeList description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function officeList(Request $request, $id)
    {
        if ($request->ajax()) {
            $offices = DB::table('offices as o')
                ->select('o.*')
                ->where('o.building_id', $id)
                ->whereNull('o.deleted_at')
                ->orderBy('o.office_id', 'desc')
                ->where('o.user_id',auth::id())
                ->get();
            $number_key = 1;
            foreach ($offices as $key => $value) {
                $value->number_key = $number_key;
                $seats_count = DB::table('seats as s')->where('s.office_id', $value->office_id)->whereNull('s.deleted_at')->count();
                $value->seats_count = $seats_count;
                $number_key++;
            }
            //print_r($offices);
            return datatables()->of($offices)->make(true);
        }
        $data['office_count'] = DB::table('offices as o')->where('o.building_id', $id)->whereNull('o.deleted_at')->count();
        $data['building_id'] = $id;
        $data['js'] = ['building/office_list.js'];
        return view('admin.building.office_list', compact('data'));
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id)
    {
        return view('admin.building.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $building = Building::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.building.edit', compact('building'))->render(),
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
            'building_name' => 'required',
            'building_address' => 'required',
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
        $Building->building_name = $inputs['building_name'];
        $Building->building_address = $inputs['building_address'];
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

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        if (Building::find($id)->delete()) {
            OfficeAsset::where('building_id', $id)->delete();
            OfficeSeat::where('building_id', $id)->delete();
            Office::where('building_id', $id)->delete();
            $this->send_cancle_email_delete($id);
            return ['status' => 'success', 'message' => 'Successfully deleted building and all associated offices and office assets, seats, reserve seat cancel'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete building'];
        }
    }

    public function send_cancle_email_delete($building_id)
    {
        $office_asstes = OfficeAsset::where('building_id', $building_id)->get();

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
                'subject' => 'weBOOK Reservation Cancelled Due To Admin Remove Building',
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
