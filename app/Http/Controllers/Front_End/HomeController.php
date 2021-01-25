<?php

namespace App\Http\Controllers\Front_End;

use App\Helpers\ApiHelper;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\Building;
use App\Models\Office;
use App\Models\OfficeAsset;
use App\Models\OfficeImage;
use App\Models\OfficeSeat;
use App\Models\Quesionaire;
use App\Models\Question;
use App\Models\ReserveSeat;
use Illuminate\Validation\Rule;
use App\Models\Seat;
use App\Models\User;
use App\Models\UserExamHistory;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Response;
use Validator;

class HomeController extends Controller
{
    public function index()
    {
        $Building = Building::get();
        $user_role = Auth::User()->role;
        if ($user_role == 2) {
            return view('index', compact('Building'));
        } else if ($user_role == 3) {
            return view('cleaner.index');
        }
    }
    public function getBuilding(Request $request)
    {
        $inputs = $request->all();
        $tenantId = $this->getTenantIdBYHost();
        $Building = Building::whereNull('deleted_at')
            ->where(function ($query) use ($inputs) {
                if (!empty($inputs['search_name'])) {
                    $query->whereRaw('LOWER(building_name) like ?', '%' . strtolower($inputs['search_name']) . '%')
                        ->orWhereRaw('LOWER(building_address) like ?', '%' . strtolower($inputs['search_name']) . '%');
                }
            })
            ->where('user_id',$tenantId)->get();
        if ($Building->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $Building]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }
    public function officeList(Request $request)
    {
        $data['building'] = Building::where('building_id', $request->building_id)->whereNull('deleted_at')->first();
        //$data['building']->
        //return $data['building'];
        //die;
        return view('office_list', compact('data'));
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

    public function filterofficeassetsinfo(Request $request, $office_assets_id)
    {
        $OfficeAsset = OfficeAsset::where('id', $office_assets_id)->first();
        if ($OfficeAsset->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $OfficeAsset]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function filterofficeassetstypeinfo(Request $request, $office_assets_id)
    {
        $OfficeAsset = OfficeAsset::where('id', $office_assets_id)->first();
        $assets_type = array('1' => 'Desk', '2' => 'Carpark Spaces', '3' => 'Collaboration Spaces', '4' => 'Meeting Room Spaces');
        if ($OfficeAsset->toArray()) {
            $assets_value = array($OfficeAsset->asset_type => @$assets_type[$OfficeAsset->asset_type]);
            return response(['status' => true, 'message' => 'Record found', 'data' => $assets_value]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function getOfficeList(Request $request)
    {
        $inputs = $request->all();
        $tenantId = $this->getTenantIdBYHost();
        $building_id = $inputs['building_id'];
        $Office = Office::whereNull('deleted_at')
            ->where(function ($query) use ($inputs) {
                if (!empty($inputs['search_name'])) {
                    $query->whereRaw('LOWER(office_name) like ?', '%' . strtolower($inputs['search_name']) . '%');
                    //->orWhereRaw('LOWER(building_address) like ?' , '%'.strtolower($inputs['search_name']).'%');
                }
            })
            ->where('building_id', $building_id)
            ->where('user_id',$tenantId)
            ->get();
        if ($Office->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $Office]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function AssetsList(Request $request)
    {
        $data['assets'] = OfficeAsset::where('office_id', $request->office_id)->whereNull('deleted_at')->first();

        return view('assets_list', compact('data'));
    }

    public function getAssetsList(Request $request)
    {
        $inputs = $request->all();
        $office_id = $inputs['office_id'];
        $OfficeAsset = OfficeAsset::whereNull('deleted_at')
            ->where(function ($query) use ($inputs) {
                if (!empty($inputs['search_name'])) {
                    $query->whereRaw('LOWER(title) like ?', '%' . strtolower($inputs['search_name']) . '%');
                }
            })
            ->where('office_id', $office_id)
            ->get();
        if ($OfficeAsset->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $OfficeAsset]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    public function reserveSeat(Request $request)
    {
        $inputs = $request->all();
        $data['offices'] = DB::table('offices as o')
            ->select('o.*', 'oa.title', 'oa.description', 'oa.preview_image', 'oa.asset_canvas', 'b.building_name', 'b.building_address')
            ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
            ->leftJoin('office_asset as oa', 'oa.office_id', '=', 'o.office_id')
            ->where('oa.id', $inputs['assets_id'])
            ->whereNull('o.deleted_at')
            ->first();

        if ($data['offices']) {
            $data['offices']->office_id;
            $total_seats = Seat::where('office_id', $data['offices']->office_id)->count();
            $blocked_seat = Seat::where('office_id', $data['offices']->office_id)->where('seat_type', '2')->count();
            $reserved_seat = ReserveSeat::where('office_id', $data['offices']->office_id)->whereIn('status', ['1', '4'])->count();

            $OfficeImage = OfficeImage::where('office_id', $data['offices']->office_id)->whereNull('deleted_at')->get();
            $office_image = $OfficeImage;

            $available_seat = $total_seats - $reserved_seat - $blocked_seat;
            $data['offices']->total_seats = $total_seats;
            $data['offices']->blocked_seat = $blocked_seat;
            $data['offices']->reserved_seat = $reserved_seat;
            $data['offices']->available_seat = $available_seat;
            $data['offices']->office_image = $office_image;

            $Seats = Seat::where('office_id', $data['offices']->office_id)->orderBy('seat_type', 'asc')->orderBy('status', 'asc')->get();
            $officeAsset = OfficeAsset::find($inputs['assets_id']);
            $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);
            $data['offices']->seats = $Seats;
            // print_r($data['offices']);
            //die;
        }

        return view('reserve_seat', compact('data', 'officeAsset', 'assets_image'));
    }

    public function seatReservation(Request $request)
    {
        $inputs = $request->all();
        $rules = [
            //'seat_number'     => 'required',
            //'checkbox'  => 'required',
            'reserve_date' => 'required',
        ];

        $this->validate($request, $rules);
        if (empty($inputs['seat_number'])) {
            return back()->with('error', 'The seat filed is required');
        }
        $reserve_date = str_replace('/', '-', $inputs['reserve_date']);
        $reserve_date = date('Y-m-d', strtotime($reserve_date));

        $Seat = Seat::where('seat_id', $inputs['seat_number'])->first();
        if ($Seat) {
            $current_date = date('Y-m-d');
            $last_date = date('Y-m-d', strtotime('+13 day'));
            $current_date = strtotime($current_date);
            $last_date = strtotime($last_date);
            //echo $current_date;
            if ($current_date >= $last_date) {
                return back()->with('error', 'Sorry you can booked seat under 14 days');
            }
            // $ReserveSeat=ReserveSeat::where('user_id',Auth::User()->id)->where('seat_id',$Seat->seat_id)->first();
            // if($ReserveSeat){
            //     return back()->with('error','This seat is already booked seat,please try again diffrent seat');
            // }

            // $ReserveSeatDate=ReserveSeat::where('user_id',Auth::User()->id)->whereDate('reserve_date',$reserve_date)->where('office_id',$Seat->office_id)->whereIn('status',['0','1','4'])->latest()->first();
            // if($ReserveSeatDate){
            //     return back()->with('error','Sorry you can only one seat book as per day in this office');
            // }

            $ReserveSeatDate = ReserveSeat::where('user_id', Auth::User()->id)->whereDate('reserve_date', $reserve_date)->whereIn('status', ['0', '1', '4'])->latest()->first();
            if ($ReserveSeatDate) {
                return back()->with('error', 'Sorry you can only one seat book as per day');
            }
            // $ReserveSeatCheck=ReserveSeat::where('status','!=','2')->where('seat_id',$Seat->seat_id)->first();
            // if($ReserveSeatCheck){
            //     return back()->with('error','This seat is already booked by other user,please try again diffrent seat');
            // }

            if ($Seat->seat_type == '2') {
                return back()->with('error', 'This seat is blocked seat,please try again diffrent seat');
            }
            // if($Seat->status=='1'){
            //     return back()->with('error','This seat is booked seat,please try again diffrent seat');
            // }

            if ($Seat->booking_mode == '1') {
                $status = '0';
            } elseif ($Seat->booking_mode == '2') {
                $status = '4';
            }

            $unique_id = 'RA' . time() . ApiHelper::otpGenrator(4);
            $ReserveSeat = new ReserveSeat();
            $ReserveSeat->reservation_id = $unique_id;
            $ReserveSeat->user_id = Auth::User()->id;
            $ReserveSeat->seat_id = $Seat->seat_id;
            $ReserveSeat->office_id = $inputs['office_id'];
            $ReserveSeat->seat_no = $Seat->seat_no;
            if (!empty($inputs['checkbox'])) {
                $ReserveSeat->advance_days = 30;
            }
            $ReserveSeat->reserve_date = $reserve_date;
            $ReserveSeat->status = $status;

            if ($ReserveSeat->save()) {
                $logo = env('Logo');
                if ($logo) {
                    $Admin = User::where('role', '1')->first();
                    $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

                } else {
                    $logo_url = asset('front_end/images/logo.png');
                }
                $ReserveSeatData = DB::table('reserve_seats as rs')
                    ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name', 'u.email')
                    ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
                    ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
                    ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
                    ->whereNull('rs.deleted_at')
                    ->where('rs.reservation_id', $ReserveSeat->reservation_id)
                    ->first();

                $mailData = array(
                    'first_name' => $ReserveSeatData->user_name,
                    'email' => $ReserveSeatData->email,
                    'user_name' => $ReserveSeatData->user_name,
                    'form_name' => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template' => 'reservation',
                    'subject' => 'weBOOK',
                    'data' => $ReserveSeatData,
                    'logo_url' => $logo_url,
                );
                if (!empty($mailData) && !empty($ReserveSeatData->email && !is_null($ReserveSeatData->email))) {
                    Mail::to($ReserveSeatData->email)->send(new NotifyMail($mailData));
                }
                if ($status == '4') {
                    $Seat->status = '1';
                    $Seat->save();
                    $mailData = array(
                        'first_name' => $ReserveSeatData->user_name,
                        'email' => $ReserveSeatData->email,
                        'user_name' => $ReserveSeatData->user_name,
                        'form_name' => 'Support@gmail.com',
                        'schedule_name' => 'weBOOK',
                        'template' => 'reservation_accepted',
                        'subject' => 'weBOOK',
                        'data' => $ReserveSeatData,
                        'logo_url' => $logo_url,
                    );
                    if (!empty($mailData) && !empty($ReserveSeatData->email && !is_null($ReserveSeatData->email))) {
                        Mail::to($ReserveSeatData->email)->send(new NotifyMail($mailData));
                    }
                }
                return back()->with('success', 'Seat reservation successfully');
            } else {
                return back()->with('error', 'Seat reservation failed,please try again');
            }
        }

    }
    public function reservation(Request $request)
    {

        $today_reservation = ReserveSeat::where('user_id', Auth::User()->id)->whereIN('status', ['1', '4', '0'])->where('reserve_date', date('Y-m-d'))->first();

        $today_office = '';
        $today_building = '';
        $today_assets = '';
        $today_seat_status = '';

        if ($today_reservation && isset($today_reservation->office_id)) {
            $today_office = Office::where('office_id', $today_reservation->office_id)->first();
            $today_building = Building::where('building_id', $today_office->building_id)->first();
            $today_assets = OfficeAsset::where('id', $today_reservation->office_asset_id)->first();
        }

        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $tomorrow_reservation = ReserveSeat::where('user_id', Auth::User()->id)->whereIN('status', ['1', '4', '0'])->where('reserve_date', '>=', $tomorrow)->first();

        $tomorrow_office = '';
        $tomorrow_building = '';
        $tomorrow_assets = '';
        $tomorrow_seat_status = '';

        if ($tomorrow_reservation) {
            $tomorrow_office = Office::where('office_id', $tomorrow_reservation->office_id)->first();
            $tomorrow_building = Building::where('building_id', $tomorrow_office['building_id'])->first();
            $tomorrow_assets = OfficeAsset::where('id', $tomorrow_reservation->office_asset_id)->first();

        }

        $data['SeatCount'] = ReserveSeat::where('user_id', Auth::User()->id)->count();
        return view('reservation', compact('data', 'today_office', 'today_building', 'today_assets', 'today_reservation', 'tomorrow_reservation', 'tomorrow_office', 'tomorrow_building', 'tomorrow_assets'));
    }

    public function getHistory(Request $request)
    {

        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND offices.office_name like '%{$search}%'";
                $whereStr .= " OR buildings.building_name like '%{$search}%'";
                $whereStr .= " OR office_asset.title like '%{$search}%'";
                $whereStr .= " OR reserve_seats.seat_no like '%{$search}%'";
                $whereStr .= " OR reserve_seats.reservation_id like '%{$search}%'";
            }

            $columns = ['reserve_seats.seat_no', 'reserve_seats.reservation_id', 'reserve_seats.reserve_date', 'reserve_seats.status', 'reserve_seats.reserve_seat_id', 'offices.office_name', 'buildings.building_name', 'office_asset.title as assets_name', 'users.user_name'];

            $ReserveSeat = ReserveSeat::select($columns)->leftJoin("office_asset", "office_asset.id", "reserve_seats.office_asset_id")->leftJoin("offices", "offices.office_id", "reserve_seats.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->leftJoin("users", "users.id", "reserve_seats.user_id")->whereRaw($whereStr, $whereParams);
            $ReserveSeat = $ReserveSeat->where("reserve_seats.user_id", Auth::User()->id);

            $ReserveSeat = $ReserveSeat->orderBy('reserve_seats.reserve_seat_id', 'desc');

            if ($ReserveSeat) {
                $total = $ReserveSeat->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $ReserveSeat = $ReserveSeat->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $ReserveSeat = $ReserveSeat->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $ReserveSeat = $ReserveSeat->get();
            $seat_status = array('0' => 'Pending', '1' => 'Accepted (By Admin)', '2' => 'Rejected (By Admin)', '3' => 'Rejected (By you)', '4' => 'Accepted (Auto Approved)');
            $final = [];
            $number_key = 1;
            $status = '';
            $cancel_button = '';
            foreach ($ReserveSeat as $key => $value) {
                $current_date = date('Y-m-d');
                if ($value->status == 0) {
                    $status = '<span class="pending" >Pending</span>';
                    if ($current_date != $value->reserve_date) {

                        $cancel_button = '<button id="cancel" class="cancel cancel-status" reserve_seat_id="' . $value->reserve_seat_id . '">Cancel</button>';
                    } else {

                        if ($current_date == $value->reserve_date) {
                            $cancel_button = '<button disabled >Cancel</button>';
                        } else {
                            $cancel_button = '<button id="cancel" class="cancel">Pending</button>';

                        }

                    }
                } else if ($value->status == 1) {
                    $status = '<span class="accepeted">Accepted</span><p>(By Admin)</p>';
                    if ($current_date != $value->reserve_date) {
                        if ($current_date <= $value->reserve_date) {
                            $cancel_button = '<button id="cancel" class="cancel cancel-status" reserve_seat_id="' . $value->reserve_seat_id . '">Cancel</button>';

                        } else {
                            $cancel_button = '<button id="cancel" disabled>Cancel</button>';
                        }
                    } else {
                        $cancel_button = '<button id="cancel" disabled>Cancel</button>';
                    }
                } else if ($value->status == 2) {
                    $status = '<span class="rejected">Rejected</span><p>(By Admin)</p>';

                    $cancel_button = '<button disabled >Cancel</button>';

                } else if ($value->status == 3) {
                    $status = '<span class="canceled">Cancelled</span><p>(By You)</p>';

                    $cancel_button = '<button disabled >Cancel</button>';

                } else if ($value->status == 4) {
                    $status = '<span class="accepeted">Accepted</span><p>(Auto Approved)</p>';

                    if ($current_date != $value->reserve_date) {
                        $cancel_button = '<button id="cancel" class="cancel cancel-status" reserve_seat_id="' . $value->reserve_seat_id . '">Cancel</button>';
                    } else {

                        if ($current_date == $value->reserve_date) {
                            $cancel_button = '<button disabled >Cancel</button>';
                        } else {
                            $cancel_button = '<button id="cancel" class="cancel">Pending</button>';

                        }
                    }

                }

                $final[$key]['number_key'] = $value->reservation_id;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['assets_name'] = $value->assets_name;
                $final[$key]['seat_no'] = $value->seat_no;
                $final[$key]['user_name'] = $value->user_name;
                $final[$key]['status'] = $status;
                $final[$key]['cancel_button'] = $cancel_button;
                $final[$key]['reserve_date'] = date('d-m-Y', strtotime($value->reserve_date));
                $final[$key]['created_at'] = date('d-m-Y H:i:s', strtotime($value->created_at));
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }

    }
    public function reservationStatusChange(Request $request)
    {
        $inputs = $request->all();
        $ReserveSeat = ReserveSeat::find($inputs['reserve_seat_id']);
        if ($ReserveSeat) {
            $ReserveSeat->status = '3';
        }
        if ($ReserveSeat->save()) {
            $Seat = Seat::where('office_asset_id', $ReserveSeat->office_asset_id)->where('seat_no', $ReserveSeat->seat_no)->first();

            $Seat->status = 0;
            $Seat->save();
            $logo = env('Logo');
            if ($logo) {
                $Admin = User::where('role', '1')->first();
                $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

            } else {
                $logo_url = asset('front_end/images/logo.png');
            }
            $ReserveSeatData = DB::table('reserve_seats as rs')
                ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name', 'u.email')
                ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
                ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
                ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
                ->whereNull('rs.deleted_at')
                ->where('rs.reservation_id', $ReserveSeat->reservation_id)
                ->first();
            $mailData = array(
                'first_name' => $ReserveSeatData->user_name,
                'email' => $ReserveSeatData->email,
                'user_name' => $ReserveSeatData->user_name,
                'form_name' => 'paul@datagov.ai',
                'schedule_name' => 'weBOOK',
                'template' => 'reservation_cancel_by_user',
                'subject' => 'weBOOK',
                'data' => $ReserveSeatData,
                'logo_url' => $logo_url,
            );
            if (!empty($mailData) && !empty($ReserveSeatData->email && !is_null($ReserveSeatData->email))) {
                Mail::to($ReserveSeatData->email)->send(new NotifyMail($mailData));
            }

            return response(['status' => true, 'message' => 'Seat reservation cancelled successfully', 'data' => $ReserveSeat]);
        } else {
            return response(['status' => false, 'message' => 'Seat reservation cancelled failded,please try again', 'data' => $ReserveSeat]);
        }
    }

    public function getSeatList(Request $request)
    {
        $inputs = $request->all();
        $reserve_date = $inputs['reserve_date'];
        $asset_id = $inputs['asset_id'];
        $office_id = $inputs['office_id'];
        /*$seats = DB::table('seats as s')
        ->select('s.*' , 'rs.reserve_date' , 'rs.status as book_status')
        ->leftJoin('reserve_seats as rs','rs.seat_id', '=','s.seat_id')
        ->where(function($query) use ($inputs){
        if(!empty($inputs['reserve_date'])){
        $reserve_date = str_replace('/', '-', $inputs['reserve_date']);
        $reserve_date = date('Y-m-d', strtotime($reserve_date));
        // $query->whereDate('rs.reserve_date',$reserve_date);
        // $query->whereDate('rs.user_id',\Auth::user()->user_id);
        }
        })
        ->where('s.office_id',$inputs['office_id'])
        ->whereNull('s.deleted_at')
        ->whereNull('rs.deleted_at')
        ->groupBy('s.seat_id')
        //->orderBy('s.status','asc')
        //->orderBy('s.seat_type','asc')
        ->get();
        $reserve_date = str_replace('/', '-', $inputs['reserve_date']);
        $reserve_date = date('Y-m-d', strtotime($reserve_date));*/
        $reserve_date = str_replace('/', '-', $inputs['reserve_date']);
        $reserve_date = date('Y-m-d', strtotime($reserve_date));
        $sql = "SELECT t1.*,t2.reserve_date,t2.user_id,t2.status as book_status FROM `seats` t1 left join reserve_seats t2 on t1.`seat_no` = t2.seat_no and  t1.`office_asset_id` = t2.office_asset_id and t2.reserve_date = '" . $reserve_date . "' and t2.status not in ('2','3') where t1.office_id = " . $inputs['office_id'] . " and t1.office_asset_id = " . $inputs['asset_id'] . " and  t1.deleted_at IS NULL ";

        $seats = DB::select($sql);
        // print_r($seats);
        // die;

        if (!empty($seats)) {
            $count_data = [];
            $total_seats = 0;
            $available_seats = 0;
            $booked_seats = 0;
            $blocked_seats = 0;
            $pending_seats = 0;
            $seatsArr = [];
            foreach ($seats as $key0 => $value0) {
                array_push($seatsArr, $value0);
                // if($value0->book_status=='3'){
                //     //array_push($seatsArr,$value0);
                //     //break;
                // }elseif($value0->book_status=='2'){
                //    // array_push($seatsArr,$value0);
                //    // break;
                // }else{
                //    array_push($seatsArr,$value0);
                // }
            }
            //return $seatsArr;
            foreach ($seatsArr as $key => $value) {
                $reserve_date = !empty($value->reserve_date) ? date('Y-m-d', strtotime($value->reserve_date)) : '';
                $value->book_reserved_date = $reserve_date;

                $curent_reserve_date = str_replace('/', '-', $inputs['reserve_date']);
                $curent_reserve_date = date('Y-m-d', strtotime($curent_reserve_date));
                $value->curent_reserve_date = $curent_reserve_date;
                $profile_image = '';
                $user_name = '';
                if ($value->is_show_user_details == '1') {
                    if (!empty($value->user_id)) {
                        $User = User::find($value->user_id);
                        if ($User) {
                            $user_name = $User->user_name;
                            $profile_image = ImageHelper::getProfileImage($User->profile_image);
                        } else {
                            $profile_image = '';
                            $user_name = '';
                        }
                    }
                } else {
                    if (!empty($value->user_id)) {
                        $User = User::find($value->user_id);
                        if ($User) {
                            $user_name = $User->user_name;
                        } else {
                            $user_name = '';
                        }
                    }
                    $profile_image = 'uploads/others/quetion.png';
                }
                $value->profile_image = $profile_image;
                $value->user_name = $user_name;
                if (!empty($value->user_id)) {
                    if ($value->user_id == Auth::user()->id) {
                        $value->tool = '1';
                    } else {
                        $value->tool = '0';
                    }
                } else {
                    $value->tool = '0';
                }

                if ($curent_reserve_date == $reserve_date) {
                    if ($value->book_status == '2' || $value->book_status == '3') {
                        $value->seat_reserve_status = '0';
                        $available_seats++;
                    } elseif ($value->book_status == '0') {
                        $value->seat_reserve_status = '1';
                        $pending_seats++;
                    } else {
                        $value->seat_reserve_status = '1';
                        $booked_seats++;
                    }
                } else {
                    $value->seat_reserve_status = '0';
                    $available_seats++;
                }
                if ($value->seat_type == '2') {
                    $blocked_seats++;
                }
                $total_seats++;
            }
            $count_data['total_seats'] = $total_seats;
            $count_data['available_seats'] = $total_seats - ($blocked_seats + $pending_seats + $booked_seats);
            $count_data['booked_seats'] = $booked_seats;
            $count_data['blocked_seats'] = $blocked_seats;
            $count_data['pending_seats'] = $pending_seats;

            //print_r($seats);die;
            return response(['status' => true, 'message' => 'Record found', 'data' => $seatsArr, 'count_data' => $count_data]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
        }
    }

    /**
     * [getofficeassetsinfo description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function getofficeassetsinfo(Request $request, $assetId)
    {
        $inputs = $request->all();
        $officeAsset = OfficeAsset::find($assetId);

        $reserve_seats = '';
        $seats_status = '';
        if ($inputs['date']) {
            $oscolumns = ['reserve_seats.office_asset_id', 'reserve_seats.status', 'reserve_seats.seat_no', 'reserve_seats.reserve_date'];
            $reserve_seats = ReserveSeat::select($oscolumns)->where('office_asset_id', $assetId)->where('reserve_date', date('Y-m-d', strtotime($inputs['date'])))->get();

            $sescolumns = ['seats.office_asset_id', 'seats.seat_type', 'seats.seat_no'];
            $seats_status = Seat::select($sescolumns)->where('office_asset_id', $assetId)->where('seat_type', 2)->get();

        }

        $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);

        $response = [
            'success' => true,
            'data' => $officeAsset,
            'assets_image' => $assets_image,
            'reserve_seats' => $reserve_seats,
            'seats_status' => $seats_status,
        ];

        return response()->json($response, 200);
    }

    /**
     * [getassetsdetails description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function getassetsdetails(Request $request, $assetId)
    {
        $whereStr = '1 = ?';
        $whereParams = [1];
        $columns = ['offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description'];

        $officeAssets = OfficeAsset::select($columns)->leftJoin("offices", "offices.office_id", "office_asset.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->whereRaw($whereStr, $whereParams)->orderBy('id', 'desc');
        if (isset($assetId) && $assetId != "") {
            $officeAssets = $officeAssets->where("office_asset.id", $assetId);
        }
        $officeAsset = $officeAssets->first();
        $response = [
            'success' => true,
            'data' => $officeAsset,

        ];

        return response()->json($response, 200);
    }

    public function isBoolean($value)
    {
        if ($value) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * [bookofficeSeats description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bookOfficeSeats(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'office_asset_id' => 'required',
            'booking_date' => 'required',

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

        $office_assets_info = OfficeAsset::find($inputs['office_asset_id']);
        $status = 0;
        $OfficeSeatinfo = OfficeSeat::where('office_asset_id', $inputs['office_asset_id'])->where('dots_id', $inputs['seat_no'])->first();

        if ($OfficeSeatinfo->booking_mode == 1) {
            $status = '0';
        } else {
            $status = '4';
        }

        $today = date('Y-m-d');
        $days = '';
        if ($office_assets_info->book_within != '') {
            $new_date = ApiHelper::convert_new_date('Day', $office_assets_info->book_within);
            $days = $office_assets_info->book_within;
        } else {
            $new_date = date('Y-m-d', strtotime($today . ' + 14 days'));
            $days = 14;
        }

        if ($new_date >= date('Y-m-d', strtotime($inputs['booking_date']))) {

            $booking_date = date('Y-m-d', strtotime($inputs['booking_date']));

            $ReserveSeat = ReserveSeat::where('office_asset_id', $inputs['office_asset_id'])->where('user_id', Auth::User()->id)->where('reserve_date', $inputs['booking_date'])->where('status', '!=', '3')->where('status', '!=', '2')->first();

            if (!isset($ReserveSeat) and $ReserveSeat == '') {

                $restrictions_ids = array();
                $has_exam = array();

                if (isset($office_assets_info) and $office_assets_info->quesionaire_id != '') {

                    $qcolumns = ['quesionaire.id', 'quesionaire.restriction', 'quesionaire.expired_option', 'quesionaire.expired_value', 'quesionaire.expired_date'];

                    $Quesionaire = Quesionaire::select($qcolumns)->whereIn('id', json_decode($office_assets_info->quesionaire_id))->get();

                    $exam_failed_status = array();
                    foreach ($Quesionaire as $qkey => $qvalue) {

                        if ($qvalue->restriction == 1) {

                            $userexam_info = UserExamHistory::where('user_id', Auth::User()->id)->get();

                            $expired_date = ApiHelper::convert_new_date($qvalue->expired_option, $qvalue->expired_value);

                            $userexam = UserExamHistory::where('user_id', Auth::User()->id)->where('quesionaire_id', $qvalue->id)->where('date', '<=', $expired_date)->where('exam_status', 1)->first();

                            if ($userexam_info->count() > 0) {

                                if ($userexam == '') {
                                    $restrictions_ids[$qkey] = $qvalue->id;
                                }

                            } else {

                                $restrictions_ids[$qkey] = $qvalue->id;
                                $has_exam[$qvalue->id] = 0;
                            }
                        }

                    }
                }

                if ($restrictions_ids) {
                    $qscolumns = ['quesionaire.id', 'quesionaire.title'];
                    $Quesionaireinfo = Quesionaire::select($qscolumns)->whereIn('id', $restrictions_ids)->get();

                    $questionarie_name = array();
                    foreach ($Quesionaireinfo as $wkey => $wvalue) {
                        $questionarie_name[$wvalue->id] = $wvalue->title;
                    }

                    $modal_type = "exam_failed";
                    $response = [
                        'success' => false,
                        'html' => view('modal_content', compact('modal_type'))->render(),
                    ];

                    return response()->json($response, 400);

                } else {
                    $unique_id = 'RA' . time() . ApiHelper::otpGenrator(4);
                    $ReserveSeats = new ReserveSeat();
                    $ReserveSeats->user_id = Auth::User()->id;
                    $ReserveSeats->reservation_id = $unique_id;
                    $ReserveSeats->seat_id = $OfficeSeatinfo->seat_id;
                    $ReserveSeats->seat_no = $inputs['seat_no'];
                    $ReserveSeats->reserve_date = date('Y-m-d', strtotime($inputs['booking_date']));
                    $ReserveSeats->office_asset_id = $inputs['office_asset_id'];
                    $ReserveSeats->office_id = $office_assets_info->office_id;
                    $ReserveSeats->status = $status;

                    if ($ReserveSeats->save()) {

                        $this->booking_send_email($ReserveSeats, $status, $OfficeSeatinfo);

                        if ($status == 4) {
                            $OfficeSeat = OfficeSeat::where('office_asset_id', $inputs['office_asset_id'])->where('dots_id', $inputs['seat_no'])->first();
                            $OfficeSeat->status = 1;
                            $OfficeSeat->save();

                            $modal_type = "booking_success";
                            $reservation_id = $unique_id;

                            $response = [
                                'success' => true,
                                'html' => view('modal_content', compact('reservation_id', 'modal_type'))->render(),
                            ];

                            return response()->json($response, 200);

                        } else {

                            $modal_type = "booking_order_success";
                            $reservation_id = $unique_id;

                            $response = [
                                'success' => true,
                                'order' => '1',
                                'html' => view('modal_content', compact('reservation_id', 'modal_type'))->render(),
                            ];

                            return response()->json($response, 200);

                        }

                    } else {

                        $modal_type = "booking_not_saved";
                        $response = [
                            'success' => false,
                            'html' => view('modal_content', compact('modal_type'))->render(),
                        ];

                        return response()->json($response, 400);

                    }

                }

            } else {
                $reservation_id = $ReserveSeat->reservation_id;
                $reservation_date = date('Y-m-d', strtotime($inputs['booking_date']));
                $modal_type = "booking_failed";
                $response = [
                    'success' => false,
                    'html' => view('modal_content', compact('reservation_id', 'modal_type', 'reservation_date'))->render(),
                ];

                return response()->json($response, 400);
            }

        } else {

            $modal_type = "morethen14_days";
            $response = [
                'success' => false,
                'html' => view('modal_content', compact('modal_type', 'days'))->render(),
            ];

            return response()->json($response, 400);
        }

    }

    public function getAssetsSeats($assets_id, $dots_id)
    {

        $OfficeSeat = OfficeSeat::where('office_asset_id', $assets_id)->where('dots_id', $dots_id)->first();

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

    public function questionaries(Request $request)
    {
        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND quesionaire.title like '%{$search}%'";
                $whereStr .= " OR user_exam_history.date like '%{$search}%'";
            }

            $columns = ['user_exam_history.user_id', 'user_exam_history.date', 'user_exam_history.exam_status', 'user_exam_history.active_status', 'quesionaire.title as quesionaire_name', 'quesionaire.expired_date', 'quesionaire.expired_option', 'quesionaire.expired_value', 'quesionaire.restriction'];

            $UserExamHistory = UserExamHistory::select($columns)->leftJoin("quesionaire", "quesionaire.id", "user_exam_history.quesionaire_id")->whereRaw($whereStr, $whereParams);
            $UserExamHistory = $UserExamHistory->where("user_exam_history.user_id", Auth::User()->id);

            $UserExamHistory = $UserExamHistory->orderBy('user_exam_history.id', 'desc');

            if ($UserExamHistory) {
                $total = $UserExamHistory->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $UserExamHistory = $UserExamHistory->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $UserExamHistory = $UserExamHistory->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $UserExamHistory = $UserExamHistory->get();
            $restriction = array('0' => 'No', '1' => 'Yes');
            $exam_status = array('0' => '<span class="text-danger"><i class="fa fa-times pr-1"></i>Fail</span>', '1' => '<span class="text-success"><i class="fa fa-check pr-1"></i> Pass</span>');

            $final = [];
            $number_key = 1;
            $active_status = 'N/A';
            foreach ($UserExamHistory as $key => $value) {
                $expired_date = ApiHelper::convert_new_date($value->expired_option, $value->expired_value);
                if ((date('Y-m-d', strtotime($value->date)) <= $expired_date) and ($value->restriction == 1) and ($value->exam_status == 1)) {
                    $active_status = '<span class="text-success">Active</span>';
                } else if ($value->restriction == 0 and ($value->exam_status == 1) or ($value->exam_status == 0)) {
                    $active_status = '<span class="text-danger">N/A</span>';
                } else {
                    $active_status = '<span class="text-danger">Expired</span>';

                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['date'] = date('d/m/Y', strtotime($value->date));
                $final[$key]['name'] = $value->quesionaire_name;
                $final[$key]['restriction'] = @$restriction[$value->restriction];
                $final[$key]['expired_date'] = date('d/m/Y', strtotime($expired_date));
                $final[$key]['exam_status'] = @$exam_status[$value->exam_status];
                $final[$key]['active_status'] = $active_status;
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();

        $today = date('Y-m-d');
        $quesitons = Question::pluck('quesionaire_id');
        $Quesionaire = Quesionaire::where('expired_date', '>=', $today)->whereIn('id', $quesitons)->orderBy('restriction', 'asc')->get();

        return view('questionaries', compact('data', 'Quesionaire'));

    }

    /**
     * [getLogicQuestions description]
     * @param  Request $request    [description]
     * @param  [type]  $assetId [description]
     * @return [type]              [description]
     */
    public function getuserLogicQuestions(Request $request, $quesionaire_id)
    {

        $Question = Question::where('quesionaire_id', $quesionaire_id)->get();
        $response = [
            'success' => true,
            'html' => view('question_list', compact('Question', 'quesionaire_id'))->render(),

        ];
        return response()->json($response, 200);

        //$quesionaire = Question::get();

    }

    /**
     * [bookofficeSeats description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checklogic(Request $request)
    {
        $inputs = $request->all();

        $messages = [];
        $rules = [];

        $rules["quesionaire_id"] = "required";
        $messages["required"] = 'Please Select Quesionaire';
        $Quesionaire = Quesionaire::where('id', $inputs['quesionaire_id'])->first();

        $Question = Question::where('quesionaire_id', $inputs['quesionaire_id'])->get();
        foreach ($Question as $key => $value) {
            if ($inputs['quesionaire_id'] == $value->quesionaire_id && $Quesionaire->restriction == 1) {
                $rules["logic_question." . $inputs['quesionaire_id'] . "." . $value->id] = "required";
                $messages["required"] = 'This questions is requied';
                $question_required = '1';

            } else {
                $question_required = '0';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $newError = [];
            foreach ($validator->errors()->toArray() as $key => $value) {
                $newError[str_replace(".", "_", $key)] = $value;
            }

            $response = [
                'success' => false,
                'errors' => $newError,
            ];

            return response()->json($response, 400);
        }

        $columns = ['questions.id', 'questions.correct_answer'];

        $user_ans = array();
        if (isset($inputs['logic_question']) and $inputs['logic_question'] != '') {
            foreach ($inputs['logic_question'] as $uqakey => $uqavalue) {

                $getquestionans = Question::select($columns)->leftJoin("quesionaire", "quesionaire.id", "questions.quesionaire_id")->where("quesionaire.id", $uqakey)->get();
                $total_questions = $getquestionans->count();
                $total_user_question = @count($inputs['logic_question'][$inputs['quesionaire_id']]);

                $adminans = array();
                foreach ($getquestionans as $qakey => $qavalue) {

                    if (is_numeric($qavalue->id)) {
                        //array_push($questionans, $qavalue->id);
                        $adminans[$qavalue->id] = $qavalue->correct_answer;
                    }
                }

                $user_ans = $uqavalue;

                $user_logic = array();
                foreach ($user_ans as $ukey => $uvalue) {
                    if ($adminans[$ukey] == $uvalue) {
                        $user_logic[$ukey] = 1;
                    } else {
                        $user_logic[$ukey] = 0;
                    }
                }

                if ($total_questions == $total_user_question) {
                    $result = '';
                    if (!in_array(0, $user_logic)) {

                        $result = 1;
                    } else {
                        $result = 0;
                    }
                } else {
                    $result = 0;
                }

                $UserExamHistoryinfo = UserExamHistory::where('date', date('Y-m-d'))->where('user_id', Auth::User()->id)->where('quesionaire_id', $uqakey)->get();

                if ($UserExamHistoryinfo->isEmpty()) {
                    $user_exam_history = new UserExamHistory();
                    $user_exam_history->user_id = Auth::User()->id;
                    $user_exam_history->quesionaire_id = $uqakey;
                    $user_exam_history->date = date('Y-m-d');
                    $user_exam_history->exam_status = $result;
                    $user_exam_history->save();
                } else {

                    $UserExamHistoryinfo = UserExamHistory::where('date', date('Y-m-d'))->where('user_id', Auth::User()->id)->where('quesionaire_id', $uqakey)->first();
                    $UserExamHistoryinfo->user_id = Auth::User()->id;
                    $UserExamHistoryinfo->quesionaire_id = $uqakey;
                    $UserExamHistoryinfo->date = date('Y-m-d');
                    $UserExamHistoryinfo->exam_status = $result;
                    $UserExamHistoryinfo->save();

                }
            }

            $response = [
                'success' => true,
                'message' => 'Your Result has beed submitted',
            ];
            return response()->json($response, 200);

        } else {
            $response = [
                'success' => true,
                'message' => 'You have Skip Exam',
            ];
            return response()->json($response, 200);
        }

    }

    public function check_user_seat_booking(Request $request, $assets_id, $dots_id)
    {
        $inputs = $request->all();
        $today = date('Y-m-d');
        $booking_date = date('Y-m-d', strtotime($inputs['booking_date']));
        $assetsinfo = OfficeAsset::find($assets_id);
        $new_date = '';
        $days = '';
        if ($assetsinfo->book_within != '') {
            $new_date = ApiHelper::convert_new_date('Day', $assetsinfo->book_within);
            $days = $assetsinfo->book_within;
        } else {
            $new_date = date('Y-m-d', strtotime($today . ' + 14 days'));
            $days = 14;
        }

        $OfficeSeat = OfficeSeat::where('office_asset_id', $assets_id)->where('dots_id', $dots_id)->first();
        $booking_mode = $OfficeSeat->booking_mode;
        $description = $OfficeSeat->description;

        $monitor = $OfficeSeat->monitor;
        $dokingstation = $OfficeSeat->dokingstation;
        $adjustableheight = $OfficeSeat->adjustableheight;
        $privatespace = $OfficeSeat->privatespace;
        $wheelchair = $OfficeSeat->wheelchair;
        $usbcharger = $OfficeSeat->usbcharger;
        $privacy = $OfficeSeat->privacy;

        $oscolumns = ['offices.office_name', 'offices.office_id'];
        $Office = Office::select($oscolumns)->where('office_id', $OfficeSeat->office_id)->first();
        $bscolumns = ['buildings.building_name', 'buildings.building_id'];
        $Building = Building::select($bscolumns)->where('building_id', $OfficeSeat->building_id)->first();
        $assets_name = $assetsinfo->title;
        if ($OfficeSeat->seat_type == 2) {
            $response = [
                'success' => true,
                'bloked' => 1,
                'html' => view('seat_bloked', compact('booking_date', 'dots_id', 'Office', 'Building', 'assets_id', 'booking_mode', 'description', 'assets_name'))->render(),
            ];

            return response()->json($response, 200);
        }

        if ($new_date >= $booking_date) {

            $ReserveSeat = ReserveSeat::where('office_asset_id', $assets_id)->where('seat_no', $dots_id)->where('reserve_date', $booking_date)->where('status', '!=', '3')->where('status', '!=', '2')->first();

            $office_asset = '';
            if ($ReserveSeat == '') {

                $acolumns = ['office_asset.id', 'office_asset.quesionaire_id', 'asset_type'];
                $office_asset = OfficeAsset::select($acolumns)->where('id', $assets_id)->first();

                $restrictions_ids = array();
                $has_exam = array();

                $response = [
                    'success' => true,
                    'available' => 1,
                    'html' => view('seat_avalilable_booking', compact('booking_date', 'office_asset', 'OfficeSeat', 'assets_name', 'dots_id', 'Office', 'Building', 'assets_id', 'booking_mode', 'description', 'monitor', 'dokingstation', 'adjustableheight', 'privatespace', 'wheelchair', 'usbcharger', 'privacy'))->render(),
                ];

                return response()->json($response, 200);

            } else {

                if ($ReserveSeat->seat_no == $dots_id) {

                    $is_show_user_details = '';
                    if ($OfficeSeat->is_show_user_details == 1) {
                        $is_show_user_details = 1;
                    }

                    $user_id = $ReserveSeat->user_id;
                    $user_info = User::find($user_id);

                    if ($user_info) {
                        $user_info->profile_image = ImageHelper::getProfileImage($user_info->profile_image);
                    }
                    $username = $user_info->user_name;
                    $user_image = $user_info->profile_image;
                    $user_id = $user_info->id;
                    $seat_status = $ReserveSeat->status;

                    $response = [
                        'success' => true,
                        'booked_seat' => 1,
                        'html' => view('booked_seat', compact('username', 'assets_name', 'seat_status', 'is_show_user_details', 'user_image', 'booking_date', 'dots_id', 'user_id', 'Office', 'Building', 'booking_mode', 'description'))->render(),
                    ];

                    return response()->json($response, 200);
                } else {
                    $response = [
                        'error' => true,
                        'msg' => 'You can not book second seat same day',
                    ];

                    return response()->json($response, 400);
                }
            }

        } else {

            $response = [
                'success' => false,
                'message' => "You Can not Book After $days  day from now",
            ];

            return response()->json($response, 400);
        }

    }

    public function booking_send_email($ReserveSeat, $status, $Seat)
    {
        $logo = env('Logo');
        if ($logo) {
            $Admin = User::where('role', '1')->first();
            $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

        } else {
            $logo_url = asset('front_end/images/logo.png');
        }
        $ReserveSeatData = DB::table('reserve_seats as rs')
            ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name', 'u.email')
            ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
            ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
            ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
            ->whereNull('rs.deleted_at')
            ->where('rs.reservation_id', $ReserveSeat->reservation_id)
            ->first();

        if ($status == '4') {
            $Seat->status = '1';
            $Seat->save();
            $mailData = array(
                'first_name' => $ReserveSeatData->user_name,
                'email' => $ReserveSeatData->email,
                'user_name' => $ReserveSeatData->user_name,
                'form_name' => 'paul@datagov.ai',
                'schedule_name' => 'weBOOK',
                'template' => 'reservation_accepted',
                'subject' => 'weBOOK Reservation Accepted',
                'data' => $ReserveSeatData,
                'logo_url' => $logo_url,
            );
            if (!empty($mailData) && !empty($ReserveSeatData->email && !is_null($ReserveSeatData->email))) {
                Mail::to($ReserveSeatData->email)->send(new NotifyMail($mailData));
            }
        } else {

            $mailData = array(
                'first_name' => $ReserveSeatData->user_name,
                'email' => $ReserveSeatData->email,
                'user_name' => $ReserveSeatData->user_name,
                'form_name' => 'paul@datagov.ai',
                'schedule_name' => 'weBOOK',
                'template' => 'reservation',
                'subject' => 'weBOOK Booking Order',
                'data' => $ReserveSeatData,
                'logo_url' => $logo_url,
            );
            if (!empty($mailData) && !empty($ReserveSeatData->email && !is_null($ReserveSeatData->email))) {
                Mail::to($ReserveSeatData->email)->send(new NotifyMail($mailData));
                $admins = User::where('role', 1)->get();

                if ($admins) {
                    $adminmailData = array(
                        'first_name' => $ReserveSeatData->user_name,
                        'email' => $ReserveSeatData->email,
                        'user_name' => $ReserveSeatData->user_name,
                        'form_name' => 'paul@datagov.ai',
                        'schedule_name' => 'weBOOK',
                        'template' => 'reservation',
                        'subject' => 'weBOOK Approve the pending reservation',
                        'data' => $ReserveSeatData,
                        'logo_url' => $logo_url,
                    );
                    foreach ($admins as $akey => $avalue) {
                        Mail::to($avalue->email)->send(new NotifyMail($adminmailData));
                    }
                }

            }
        }
    }

    public function search_seat(Request $request)
    {
        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if ($request->sSearch) {
                $search = trim($request->sSearch);
                $whereStr .= " AND  office_asset.title like '%{$search}%'";
                $whereStr .= " OR offices.office_name like '%{$search}%'";
                $whereStr .= " OR buildings.building_name like '%{$search}%' ";
            }

            $columns = ['office_asset.id', 'office_asset.is_covid_test', 'office_asset.asset_type', 'office_asset.office_id', 'office_asset.updated_at', 'offices.office_name as office_name', 'buildings.building_name as building_name', 'office_asset.title', 'office_asset.description', 'office_asset.quesionaire_id', 'seats.booking_mode', 'seats.seat_id', 'seats.seat_no', 'seats.office_asset_id', 'seats.monitor', 'seats.dokingstation', 'seats.adjustableheight', 'seats.privatespace', 'seats.wheelchair', 'seats.usbcharger', 'seats.seat_type', 'seats.privacy', 'seats.underground', 'seats.pole_information', 'seats.wheelchair_accessable', 'seats.parking_difficulty', 'seats.whiteboard_avaialble', 'seats.teleconference_screen', 'seats.is_white_board_interactive', 'seats.telephone', 'seats.telephone_number', 'seats.number_of_spare_power_sockets', 'seats.kanban_board', 'seats.whiteboard', 'seats.interactive_whiteboard', 'seats.standing_only', 'seats.telecomference_screen', 'seats.meeting_indicator_mounted_on_wall'];

            $officeAssets = OfficeSeat::select($columns)->leftJoin("office_asset", "office_asset.id", "seats.office_asset_id")->leftJoin("offices", "offices.office_id", "office_asset.office_id")->leftJoin("buildings", "buildings.building_id", "office_asset.building_id")->whereNull('buildings.deleted_at')->whereNull('offices.deleted_at')->whereNull('office_asset.deleted_at')->whereRaw($whereStr, $whereParams);

            // $officeAssets = OfficeSeat::Join("office_asset", "office_asset.id", "seats.office_asset_id")->Join("offices", "offices.office_id", "seats.office_id")->Join("buildings", "buildings.building_id", "seats.building_id")->whereRaw($whereStr, $whereParams)->whereNull('seats.deleted_at')->get();
            $booking_status = '';
            if ($request->search_booking_date) {

                $booking_date = date('Y-m-d', strtotime($request->search_booking_date));
            } else {
                $booking_date = date('Y-m-d');
            }

            if (isset($request->building_id) && $request->building_id != "") {
                $officeAssets = $officeAssets->where("office_asset.building_id", $request->building_id);
            }

            if (isset($request->office_id) && $request->office_id != "" && $request->office_id != "All") {
                $officeAssets = $officeAssets->where("office_asset.office_id", $request->office_id);
            }

            if (isset($request->office_asset_id) && $request->office_asset_id != "") {
                $officeAssets = $officeAssets->where("office_asset.id", $request->office_asset_id);
            }

            if (isset($request->office_asset_type_id) && $request->office_asset_type_id != "") {
                $officeAssets = $officeAssets->where("office_asset.asset_type", $request->office_asset_type_id);
            }

            if (isset($request->seat_status) && $request->seat_status == 1) {

                $ReserveSeat = ReserveSeat::where("reserve_date", $booking_date)->pluck('seat_id')->all();
                $officeAssets = $officeAssets->where('seats.seat_type', 1)->whereNotIn("seats.seat_id", $ReserveSeat);
                $booking_status = '<button title="Available" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#83AB4F;">A</button>';
            }

            if (isset($request->seat_status) && $request->seat_status == 2) {

                $officeAssets = $officeAssets->where("seats.seat_type", '2');
                $booking_status = '<button title="Blocked" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#DB2710;">B</button>';
            }

            if (isset($request->seat_status) && $request->seat_status == 3) {

                $ReserveSeat = ReserveSeat::where("reserve_date", $booking_date)->whereIn('status', ['1', '4'])->pluck('seat_id')->all();

                $officeAssets = $officeAssets->where('seats.seat_type', 1)->whereIn("seats.seat_id", $ReserveSeat);

                $booking_status = '<button title="Booked" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#7E7E7D;">R</button>';

            }

            if (isset($request->seat_status) && $request->seat_status == 4) {

                $ReserveSeat = ReserveSeat::where("reserve_date", $booking_date)->where('status', '0')->pluck('seat_id')->all();

                $officeAssets = $officeAssets->where('seats.seat_type', '1')->whereIn("seats.seat_id", $ReserveSeat);

                $booking_status = '<button title="Pending" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#5471BF;">P</button>';

            }

            if (isset($request->seat_status) && $request->seat_status == 5) {
                $ReserveSeat = ReserveSeat::where("reserve_date", $booking_date)->where('require_clean', 1)->pluck('seat_id')->all();
                $officeAssets = $officeAssets->whereIn("seats.seat_id", $ReserveSeat);
                $booking_status = '<button title="Cleaning Required" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#62339B;">C</button>';
            }

            if (isset($request->privacy) && $request->privacy != "") {
                $officeAssets = $officeAssets->where("seats.privacy", $request->privacy);
            }

            if (isset($request->monitor) && $request->monitor == 1) {
                $officeAssets = $officeAssets->where("seats.monitor", 1);
            }

            if (isset($request->dokingstation) && $request->dokingstation == 1) {
                $officeAssets = $officeAssets->where("seats.dokingstation", 1);
            }

            if (isset($request->adjustableheight) && $request->adjustableheight == 1) {
                $officeAssets = $officeAssets->where("seats.adjustableheight", 1);
            }

            if (isset($request->privatespace) && $request->privatespace == 1) {
                $officeAssets = $officeAssets->where("seats.privatespace", 1);
            }

            if (isset($request->wheelchair) && $request->wheelchair == 1) {
                $officeAssets = $officeAssets->where("seats.wheelchair", 1);
            }

            if (isset($request->usbcharger) && $request->usbcharger == 1) {
                $officeAssets = $officeAssets->where("seats.usbcharger", 1);
            }

            if (isset($request->underground) && $request->underground == 1) {
                $officeAssets = $officeAssets->where("seats.underground", 1);
            }

            if (isset($request->pole_information) && $request->pole_information == 1) {
                $officeAssets = $officeAssets->where("seats.pole_information", 1);
            }

            if (isset($request->wheelchair_accessable) && $request->wheelchair_accessable == 1) {
                $officeAssets = $officeAssets->where("seats.wheelchair_accessable", 1);
            }

            if (isset($request->parking_difficulty) && $request->parking_difficulty != '') {
                $officeAssets = $officeAssets->where("seats.parking_difficulty", $request->parking_difficulty);
            }

            if (isset($request->whiteboard_avaialble) && $request->whiteboard_avaialble == 1) {
                $officeAssets = $officeAssets->where("seats.whiteboard_avaialble", 1);
            }

            if (isset($request->teleconference_screen) && $request->teleconference_screen == 1) {
                $officeAssets = $officeAssets->where("seats.teleconference_screen", 1);
            }

            if (isset($request->is_white_board_interactive) && $request->is_white_board_interactive == 1) {
                $officeAssets = $officeAssets->where("seats.is_white_board_interactive", 1);
            }

            if (isset($request->kanban_board) && $request->kanban_board == 1) {
                $officeAssets = $officeAssets->where("seats.kanban_board", 1);
            }

            if (isset($request->whiteboard) && $request->whiteboard == 1) {
                $officeAssets = $officeAssets->where("seats.whiteboard", 1);
            }

            if (isset($request->interactive_whiteboard) && $request->interactive_whiteboard == 1) {
                $officeAssets = $officeAssets->where("seats.interactive_whiteboard", 1);
            }

            if (isset($request->standing_only) && $request->standing_only == 1) {
                $officeAssets = $officeAssets->where("seats.standing_only", 1);
            }

            if (isset($request->telecomference_screen) && $request->telecomference_screen == 1) {
                $officeAssets = $officeAssets->where("seats.telecomference_screen", 1);
            }

            if (isset($request->meeting_indicator_mounted_on_wall) && $request->meeting_indicator_mounted_on_wall == 1) {
                $officeAssets = $officeAssets->where("seats.meeting_indicator_mounted_on_wall", 1);
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
            $booking_mode = '';

            foreach ($officeAssets as $key => $value) {

                if (isset($value->quesionaire_id) && $value->quesionaire_id != '') {
                    $quesionaire_info = explode(",", $value->quesionaire_id);
                    $quesionaire_total = count($quesionaire_info);

                } else {
                    $quesionaire_total = 0;
                }

                if ($value->booking_mode == 2) {
                    $booking_mode = '<button title="Auto" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#83AB4F;">A</button>';
                } else {
                    $booking_mode = '<button title="Manual" class="btn btn-success booking_mode_css" style="border-radius: 50px;background:#d37c39;">M</button>';
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['building_name'] = $value->building_name;
                $final[$key]['office_name'] = $value->office_name;
                $final[$key]['title'] = $value->title;
                $final[$key]['monitor'] = $value->monitor;
                $final[$key]['dokingstation'] = $value->dokingstation;
                $final[$key]['adjustableheight'] = $value->adjustableheight;
                $final[$key]['privatespace'] = $value->privatespace;
                $final[$key]['wheelchair'] = $value->wheelchair;
                $final[$key]['seat_number'] = $value->seat_no;
                $final[$key]['usbcharger'] = $value->usbcharger;
                $final[$key]['status'] = $booking_status;
                $final[$key]['booking_mode'] = $booking_mode;
                $final[$key]['total_quesionaire'] = $quesionaire_total;
                $final[$key]['office_asset_id'] = $value->office_asset_id;
                $final[$key]['asset_type'] = $value->asset_type;
                $final[$key]['underground'] = $value->underground;
                $final[$key]['pole_information'] = $value->pole_information;
                $final[$key]['wheelchair_accessable'] = $value->wheelchair_accessable;
                $final[$key]['parking_difficulty'] = $value->parking_difficulty;
                $final[$key]['whiteboard_avaialble'] = $value->whiteboard_avaialble;
                $final[$key]['teleconference_screen'] = $value->teleconference_screen;
                $final[$key]['is_white_board_interactive'] = $value->is_white_board_interactive;
                $final[$key]['telephone'] = $value->telephone;
                $final[$key]['telephone_number'] = $value->telephone_number;
                $final[$key]['number_of_spare_power_sockets'] = $value->number_of_spare_power_sockets;
                $final[$key]['kanban_board'] = $value->kanban_board;
                $final[$key]['whiteboard'] = $value->whiteboard;
                $final[$key]['interactive_whiteboard'] = $value->interactive_whiteboard;
                $final[$key]['standing_only'] = $value->standing_only;
                $final[$key]['telecomference_screen'] = $value->telecomference_screen;
                $final[$key]['meeting_indicator_mounted_on_wall'] = $value->meeting_indicator_mounted_on_wall;
                $final[$key]['viewseat'] = '<a target="_blank" href="' . url('reserve_seat') . '?assets_id=' . $value->office_asset_id . '&view_seat=' . $value->seat_no . '&date=' . $request->search_booking_date . '" class="btn btn-link">See in map</a>';
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;

        }
    }

    public function get_user_questionarie_result(Request $request)
    {

        $inputs = $request->all();

        $office_assets_info = OfficeAsset::find($inputs['asset_id']);
        $qcolumns = ['quesionaire.id', 'quesionaire.restriction', 'quesionaire.expired_option', 'quesionaire.expired_value', 'quesionaire.expired_date'];

        $Quesionaire = Quesionaire::whereIn('id', json_decode($office_assets_info->quesionaire_id))->pluck('id')->all();
        $columns = ['user_exam_history.user_id', 'user_exam_history.date', 'user_exam_history.exam_status', 'user_exam_history.active_status', 'quesionaire.title as quesionaire_name', 'quesionaire.expired_date', 'quesionaire.expired_option', 'quesionaire.expired_value', 'quesionaire.restriction'];

        if ($Quesionaire) {
            $UserExamHistory = UserExamHistory::select($columns)->leftJoin("quesionaire", "quesionaire.id", "user_exam_history.quesionaire_id")->whereIn('quesionaire_id', $Quesionaire);
            $UserExamHistory = $UserExamHistory->where("user_exam_history.user_id", Auth::User()->id)->get();

            $qcolumns = ['quesionaire.id', 'quesionaire.title as quesionaire_name'];
            $Quesionaires = Quesionaire::select($qcolumns)->whereIn('id', json_decode($office_assets_info->quesionaire_id))->get();
            $exam_status = array('0' => '<span class="btn question_css btn-danger"> Fail </span>', '1' => '<span class="btn question_css btn-success">  Pass </span>');
            if ($UserExamHistory) {
                $question = "<p>There are " . count($Quesionaire) . "  questionaries attached to this seat, below are your customized results</p>";
                $active_status = '';
                $search_date = $inputs['date'];
                foreach ($UserExamHistory as $key => $value) {
                    $expired_date = ApiHelper::convert_new_date($value->expired_option, $value->expired_value);
                    if (($expired_date <= date('Y-m-d', strtotime($search_date)) and ($value->restriction == 1) and ($value->exam_status == 1))) {
                        $active_status = '<span class="btn question_css btn-success"> In Date</span>';
                    } else if ($value->restriction == 0 and ($value->exam_status == 1) or ($value->exam_status == 0)) {
                        $active_status = '<span class="btn question_css btn-danger">N/A</span>';
                    } else {
                        $active_status = '<span class="btn question_css btn-danger"> Out Date</span>';

                    }

                    $question .= '<div class="questionarie_result"><a class="question_css"   target="_blank" href="' . url('questionaries') . '">' . $value->quesionaire_name . '</a>';
                    $question .= '<div class="result-btn"><span>' . @$exam_status[$value->exam_status] . '</span>';
                    $question .= '<span>' . $active_status . '</span></div></div>';
                }

            }

            return $question;
        }

    }

    public function seat_check_in(Request $request)
    {
        $inputs = $request->all();
        $ReserveSeat = ReserveSeat::find($inputs['reserve_seat_id']);
        if ($ReserveSeat) {
            $ReserveSeat->checkin_time = date('H:i:s');
            $ReserveSeat->checkin_method = '1';
        }
        if ($ReserveSeat->save()) {
            return response(['status' => true, 'time' => date('h:i A'), 'message' => 'Seat Check in successfully', 'data' => $ReserveSeat]);
        } else {
            return response(['status' => false, 'message' => 'Seat checkin failded,please try again', 'data' => $ReserveSeat]);
        }
    }

    public function seat_view(Request $request)
    {
        $inputs = $request->all();
    }

    public function get_reservation_info(Request $request)
    {
        $confirmed = ReserveSeat::where('user_id', Auth::User()->id)->whereIN('status', ['1', '4'])->where('reserve_date', ">", DB::raw('NOW() - INTERVAL 1 WEEK'))->count();

        $pending = ReserveSeat::where('user_id', Auth::User()->id)->where('status', '0')->where('reserve_date', ">", DB::raw('NOW() - INTERVAL 1 WEEK'))->count();

        $completed = ReserveSeat::where('user_id', Auth::User()->id)->whereIN('status', ['1', '4'])->where('reserve_date', ">", DB::raw('NOW() - INTERVAL 1 WEEK'))->whereNotNull('checkin_time')->where('noshow', '0')->count();

        $no_show = ReserveSeat::where('user_id', Auth::User()->id)->where('noshow', '1')->where('reserve_date', ">", DB::raw('NOW() - INTERVAL 1 WEEK'))->count();
        return response([
            'success' => true,
            'confirmed' => $confirmed,
            'pending' => $pending,
            'completed' => $completed,
            'no_show' => $no_show,
        ]);

    }

    public function get_assets_info(Request $request, $assets_id)
    {
        if ($assets_id) {
            $assets = OfficeAsset::where('id', $assets_id)->fist();
            return response(['status' => true, 'book_within' => $assets->book_within]);
        } else {
            return response(['status' => false]);
        }
    }

    /**
     * [get_new_time description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function get_new_time(Request $request)
    {
        ;
        $response = [
            'success' => true,
            'time' => date('d/m/Y h:i A'),
        ];
        return response()->json($response, 200);
    }

     public function inviteUserRegistrationForm($id){
        $id = decrypt($id);
        $inviteUser = DB::table('user_invitations')->where('id',$id)->whereNull('deleted_at')->first();
        if($inviteUser){
             $data['id']        = encrypt($inviteUser->id);
             $data['user_name'] = $inviteUser->name;
             $data['email']     = $inviteUser->email;
        return view('invite_user_registration',compact('data'));
        }
     }

     public function inviteUserRegistrationStore(Request $request){
        $inputs   = $request->all();
        
        $tenantId = $this->getTenantIdBYHost();

        $rules = [
            '_id'               => 'required',
            'user_name'         => 'required',
            'job_profile'       => 'required',
            'email'             => ['required', Rule::unique('users', 'email')->where('tenant_id',$tenantId)->where('role', '2')],
            'password'          => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'  => 'required|min:8',

        ];

        $request->validate($rules);

        $profile_image=null;
        if($request->hasFile('profile_image')) {
            $profile_image = str_random('10').'_'.time().'.'.request()->profile_image->getClientOriginalExtension();
            request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
        }

        $inviteId = decrypt($inputs['_id']);

        $inviteUser = DB::table('user_invitations')->where('id',$inviteId)->whereNull('deleted_at')->first();
        if($inviteUser){
             $data['id']        = $inviteUser->id;
             $data['user_name'] = $inviteUser->name;
             $data['email']     = $inviteUser->email;
             $data['invited_user_id']     = $inviteUser->invited_user_id;
             $data['invited_time']        = $inviteUser->created_at;
        }else{
              return back()->with('status',false)->with('message','Your registration failed,please try again');
        }
         
        $inviteDate = date('Y-m-d H:i:s', strtotime($data['invited_time']. ' + 48 days'));

        if(strtotime($inviteDate) < strtotime(date('Y-m-d H:i:s'))){
              return back()->with('status',false)->with('message','This page is expire');
        }
        
        $User                     = new User;
        $User->role               = '2';
        $User->user_name          = $inputs['user_name'];
        $User->email              = $data['email'];
        $User->job_profile        = $inputs['job_profile'];
        $User->email_verify_status = '1';
        $User->approve_status      = '1';
        $User->is_invited          = '1';
        $User->invite_user_id      = $data['invited_user_id'];
        $User->password            = \Hash::make($inputs['password']);
        $User->tenant_id           = $tenantId;

        $host = request()->getHost();
        $hostArr = explode('.',$host);
        $subDomain = $hostArr[0];
        $tenantData = DB::table('tenant_details')->where('sub_domain',$subDomain)->first();

        if($tenantData){
            $tenantId = $tenantData->tenant_id;
            $User->tenant_id        = $tenantId;
        }

        if($profile_image){
            $User->profile_image  = $profile_image;
        }

        $User->save();
        if($User){
            DB::table('user_invitations')->where('id',$inviteId)->update(['is_registered'=>'1']);
            return Redirect('/login')->with('status',true)->with('message','Your registration successfully');
        }else{
            return bach()->with('status',false)->with('message','Your registration failed,please try again');
        }
     }

     public function getTenantIdBYHost(){
        $host = request()->getHost();
        $hostArr = explode('.',$host);
        $subDomain = $hostArr[0];
        $tenantData = \DB::table('tenant_details')->where('sub_domain',$subDomain)->first();
        $tenantId = null;
        if($tenantData){
            $tenantId = $tenantData->tenant_id;
        }
        return $tenantId;
     }

}
