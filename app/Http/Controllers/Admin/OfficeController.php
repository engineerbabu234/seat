<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\Building;
use App\Models\Office;
use App\Models\ReserveSeat;
use App\Models\Seat;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OfficeController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $data = array();
        $offices = Office::orderBy('office_id', 'desc')->get();
        $data['offices'] = $offices;
        if ($request->ajax()) {
            return view('admin.office.office_list', compact('data'));
        }
        return view('admin.office.index', compact('data'));
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

        $officeData = array();
        $seatData = array();
        $imageData = array();

        $officeData['building_id'] = $inputs['office_building'];
        $officeData['office_name'] = $inputs['office_name'];
        $officeData['office_number'] = $inputs['office_number'];
        $officeData['description'] = $inputs['office_description'];

        $OfficeCount = DB::table('offices')->whereNull('deleted_at')->count();
        $TOTAL_MAX_OFFICES = env('TOTAL_MAX_OFFICES');
        if ($OfficeCount >= $TOTAL_MAX_OFFICES) {
            return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_OFFICES . ' office'];
        }

        $SeatCount = DB::table('seats')->whereNull('deleted_at')->count();
        $addSeatCount = count($inputs['seat_no']);
        $TOTAL_MAX_SEATS = env('TOTAL_MAX_SEATS');
        $totalSeatCount = $SeatCount + $addSeatCount;
        if ($totalSeatCount >= $TOTAL_MAX_SEATS + 1) {
            return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_SEATS . ' seats'];
        }

        DB::beginTransaction();
        try {
            $office_id = DB::table('offices')->insertGetId($officeData);
            if ($inputs['seat_no']) {
                foreach ($inputs['seat_no'] as $key => $value) {
                    $temp = array();
                    $temp['office_id'] = $office_id;
                    $temp['seat_no'] = $inputs['seat_no'][$key];
                    $temp['description'] = $inputs['description'][$key];
                    $temp['booking_mode'] = $inputs['booking_mode'][$key];
                    $temp['seat_type'] = $inputs['seat_type'][$key] ?? '2';
                    $temp['is_show_user_details'] = $inputs['is_show_user_details'][$key] ?? '0';
                    array_push($seatData, $temp);
                }
            }
            $documentFiles = array();
            if ($request->images) {
                foreach ($request->images as $key => $document) {
                    $fileName = str_random('10') . '.' . time() . '.' . $document->getClientOriginalExtension();
                    $document->move(public_path('uploads/office_image/'), $fileName);
                    array_push($documentFiles, ['office_id' => $office_id, 'image' => $fileName, 'description' => $inputs['image_description'][$key]]);
                }
            }
            if ($seatData) {
                DB::table('seats')->insert($seatData);
            }

            if ($documentFiles) {
                DB::table('office_images')->insert($documentFiles);
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Successfully added office'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => 'failed', 'message' => $e->getMessage()];
        }
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
    public function edit($id)
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
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $officeData = array();
        $seatData = array();
        $imageData = array();

        $officeData['building_id'] = $inputs['office_building'];
        $officeData['office_name'] = $inputs['office_name'];
        $officeData['office_number'] = $inputs['office_number'];
        $officeData['description'] = $inputs['office_description'];

        $SeatCount = DB::table('seats')->whereNull('deleted_at')->count();
        $AddedSeatCount = DB::table('seats')->where('office_id', $inputs['office_id'])->whereNull('deleted_at')->count();
        $addSeatCount = count($inputs['seat_no']);
        $TOTAL_MAX_SEATS = env('TOTAL_MAX_SEATS');
        $totalSeatCount = ($SeatCount - $AddedSeatCount) + $addSeatCount;
        if ($totalSeatCount >= $TOTAL_MAX_SEATS + 1) {
            return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_SEATS . ' seats'];
        }
        DB::beginTransaction();
        try {

            $office_id = $inputs['office_id'];
            DB::table('offices')->where('office_id', $office_id)->update($officeData);
            DB::table('seats')->where('office_id', $office_id)->whereNotIn('seat_id', array_filter($inputs['seat_id'], function ($value) {return !is_null($value);}))->delete();
            DB::table('office_images')->where('office_id', $office_id)->whereNotIn('office_image_id', array_filter($inputs['image_id'], function ($value) {return !is_null($value);}))->delete();

            if ($inputs['seat_no']) {
                foreach ($inputs['seat_no'] as $key => $value) {
                    $temp = array();
                    $temp['office_id'] = $office_id;
                    $temp['seat_no'] = $inputs['seat_no'][$key];
                    $temp['description'] = $inputs['description'][$key];
                    $temp['booking_mode'] = $inputs['booking_mode'][$key];
                    $temp['seat_type'] = $inputs['seat_type'][$key] ?? '2';
                    $temp['is_show_user_details'] = $inputs['is_show_user_details'][$key] ?? '0';
                    if ($inputs['seat_id'][$key]) {
                        if ($temp['seat_type'] == '2') {
                            $reserveSeats = DB::table('reserve_seats as rs')
                                ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name', 'u.email', 'u.job_profile')
                                ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
                                ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
                                ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
                                ->whereIn('rs.status', ['0', '1', '4'])
                                ->whereDate('rs.reserve_date', '>=', date('Y-m-d'))
                                ->where('rs.seat_id', $inputs['seat_id'][$key])
                                ->get();

                            if ($reserveSeats->toArray()) {
                                foreach ($reserveSeats as $key1 => $value1) {
                                    $mailData = array(
                                        'first_name' => $value1->user_name,
                                        'email' => $value1->email,
                                        'user_name' => $value1->user_name,
                                        'form_name' => 'Support@gmail.com',
                                        'schedule_name' => 'weBOOK',
                                        'template' => 'reservation_cancel',
                                        'subject' => 'weBOOK',
                                        'data' => $value1,
                                    );
                                    DB::table('reserve_seats')->where('reserve_seat_id', $value1->reserve_seat_id)->update(['status' => '2']);
                                    if (!empty($mailData) && !empty($value1->email && !is_null($value1->email))) {
                                        Mail::to($value1->email)->send(new NotifyMail($mailData));
                                    }
                                }
                            }
                        }
                        DB::table('seats')->where('seat_id', $inputs['seat_id'][$key])->update($temp);
                    } else {
                        DB::table('seats')->insert($temp);
                    }
                }
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $value) {
                if ($inputs['image_id'][$key]) {
                    $updateImageData = ['description' => $inputs['image_description'][$key]];
                    if ($value) {
                        $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                        $fileName = str_random('10') . '.' . time() . '.' . $ext;
                        move_uploaded_file($value, 'uploads/office_image/' . $fileName);
                        $updateImageData['image'] = $fileName;
                    }
                    DB::table('office_images')->where(['office_id' => $office_id, 'office_image_id' => $inputs['image_id'][$key]])->update($updateImageData);
                } else {
                    $insertImageData = ['office_id' => $office_id, 'description' => $inputs['image_description'][$key]];
                    if ($value) {
                        $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                        $fileName = str_random('10') . '.' . time() . '.' . $ext;
                        move_uploaded_file($value, 'uploads/office_image/' . $fileName);
                        $insertImageData['image'] = $fileName;
                    }
                    DB::table('office_images')->insert($insertImageData);
                }
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Successfully updated office'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => 'failed', 'message' => $e->getMessage()];
        }
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
            DB::table('seats')->where('office_id', $id)->delete();
            return ['status' => 'success', 'message' => 'Successfully deleted office'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete office'];
        }
    }
}
