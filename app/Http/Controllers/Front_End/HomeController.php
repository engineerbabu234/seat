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
use App\Models\ReserveSeat;
use Illuminate\Validation\Rule;
use App\Models\Seat;
use App\Models\User;
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
        return view('index');
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
    public function history(Request $request)
    {
        $data['SeatCount'] = ReserveSeat::where('user_id', Auth::User()->id)->count();
        return view('history', compact('data'));
    }
    public function getHistory(Request $request)
    {
        $inputs = $request->all();
        $history = DB::table('reserve_seats as rs')
            ->select('rs.*', 'b.building_name', 'o.office_name', 'u.user_name')
            ->leftJoin('offices as o', 'o.office_id', '=', 'rs.office_id')
            ->leftJoin('buildings as b', 'b.building_id', '=', 'o.building_id')
            ->leftJoin('users as u', 'u.id', '=', 'rs.user_id')
            ->where(function ($query) use ($inputs) {
                if (!empty($inputs['search_name'])) {
                    $query->whereRaw('LOWER(rs.reserve_seat_id) like ?', '%' . strtolower($inputs['search_name']) . '%')
                        ->orWhereRaw('LOWER(o.office_name) like ?', '%' . strtolower($inputs['search_name']) . '%')
                        ->orWhereRaw('LOWER(rs.seat_no) like ?', '%' . strtolower($inputs['search_name']) . '%')
                        ->orWhereRaw('LOWER(b.building_name) like ?', '%' . strtolower($inputs['search_name']) . '%')
                        ->orWhereRaw('LOWER(u.user_name) like ?', '%' . strtolower($inputs['search_name']) . '%')
                        ->orWhereRaw('LOWER(rs.reservation_id) like ?', '%' . strtolower($inputs['search_name']) . '%');
                }
            })
            ->whereNull('rs.deleted_at')
            ->where('rs.user_id', Auth::User()->id)
            ->orderBy('rs.reserve_seat_id', 'asc')
            ->get();
        foreach ($history as $key => $value) {
            $reserve_date = date('Y-m-d', strtotime($value->reserve_date));
            $value->reserve_date = $reserve_date;
            $value->current_date = date('Y-m-d');
        }
        if ($history->toArray()) {
            return response(['status' => true, 'message' => 'Record found', 'data' => $history]);
        } else {
            return response(['status' => false, 'message' => 'Record not found']);
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
            $Seat = Seat::find($ReserveSeat->seat_id);
            $Seat->status = '0';
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
                'form_name' => 'Support@gmail.com',
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
        $sql = "SELECT t1.*,t2.reserve_date,t2.user_id,t2.status as book_status FROM `seats` t1 left join reserve_seats t2 on t1.`seat_id` = t2.seat_id and t2.reserve_date = '" . $reserve_date . "' and t2.status not in ('2','3') where t1.office_id = " . $inputs['office_id'];

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
        $officeAsset = OfficeAsset::find($assetId);

        $assets_image = ImageHelper::getOfficeAssetsImage($officeAsset->preview_image);

        $response = [
            'success' => true,
            'data' => $officeAsset,
            'assets_image' => $assets_image,
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

    /**
     * [bookofficeSeats description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bookOfficeSeats(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            // 'seat_id' => 'required',
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

        // $OfficeAsset = new OfficeAsset();
        // $OfficeAsset->building_id = $inputs['building_id'];
        // $OfficeAsset->office_id = $inputs['office_id'];
        // $OfficeAsset->title = $inputs['title'];
        // $OfficeAsset->description = $inputs['description'];
        // $OfficeAsset->preview_image = $preview_image;

        $response = [
            'success' => true,
            'message' => 'Office Asset Added success',
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
