<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Building;
use App\Models\ReserveSeat;
use App\Models\Seat;

use App\Helpers\ImageHelper;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use Hash;
use Redirect,Response,DB,Config;
use Datatables;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;

class ReservationController extends Controller
{
    public function reservationRequest(Request $request){
        if($request->ajax()){
            $reservation_request = DB::table('reserve_seats as rs')
                    ->select('rs.*' , 'b.building_name' , 'o.office_name' , 'u.user_name', 'u.email', 'u.profile_image')
                    ->leftJoin('offices as o','o.office_id', '=','rs.office_id')
                    ->leftJoin('buildings as b','b.building_id', '=','o.building_id')
                    ->leftJoin('users as u','u.id', '=','rs.user_id')
                    ->whereNull('rs.deleted_at')
                    ->where('rs.status','0')
                    ->orderBy('rs.reserve_seat_id','desc')
                    ->get();
            $number_key=1;
            foreach ($reservation_request as $key => $value) {
                $value->profile_image=ImageHelper::getProfileImage($value->profile_image);
                $value->number_key=$number_key;
                $number_key++;
            }
            return datatables()->of($reservation_request)->make(true);
        }
        $data['seat_count']                     = ReserveSeat::where('status','0')->count();
        $data['js'] = ['reservation/index.js'];
        return view('admin.reservation.reservation_request',compact('data'));
    }

    public function reservationHistory(Request $request){
        if($request->ajax()){
            $reservation_request = DB::table('reserve_seats as rs')
                    ->select('rs.*' , 'b.building_name' , 'o.office_name' , 'u.user_name' , 'u.user_name', 'u.email', 'u.profile_image','s.booking_mode')
                    ->leftJoin('offices as o','o.office_id', '=','rs.office_id')
                    ->leftJoin('buildings as b','b.building_id', '=','o.building_id')
                    ->leftJoin('seats as s','s.seat_id', '=','rs.seat_id')

                    ->leftJoin('users as u','u.id', '=','rs.user_id')
                    ->whereNull('rs.deleted_at')
                    ->where('rs.status','!=','0')
                    ->orderBy('rs.reserve_seat_id','desc')
                    ->get();
            $number_key=1;
            foreach ($reservation_request as $key => $value) {
                $value->profile_image=ImageHelper::getProfileImage($value->profile_image);
                $value->number_key=$number_key;
                $value->current_date=date('Y-m-d');
                $number_key++;
            }
            return datatables()->of($reservation_request)->make(true);
        }
        $data['seat_count']                     = ReserveSeat::where('status','!=','0')->count();
        $data['js'] = ['reservation/reservation_history.js'];
        return view('admin.reservation.reservation_history',compact('data'));
    }
    public function Accpted(Request $request){
        $inputs      = $request->all();
        $ReserveSeat                     = ReserveSeat::find($inputs['reserve_seat_id']);
        if($ReserveSeat){
            $ReserveSeat->status             = '1';
        }
        if($ReserveSeat->save()){
            $Seat = Seat::find($ReserveSeat->seat_id);
            $Seat->status='1';
            $Seat->save();
            $reserveSeats=DB::table('reserve_seats as rs')
                            ->select('rs.*' , 'b.building_name' , 'o.office_name' , 'u.user_name','u.email', 'u.job_profile')
                            ->leftJoin('offices as o','o.office_id', '=','rs.office_id')
                            ->leftJoin('buildings as b','b.building_id', '=','o.building_id')
                            ->leftJoin('users as u','u.id', '=','rs.user_id')
                            ->where('rs.seat_id',$ReserveSeat->seat_id)
                            ->first();
            if($reserveSeats){
                $logo=env('Logo');
                if($logo){
                    $Admin = User::where('role','1')->first();
                    $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

                }else{
                    $logo_url = asset('front_end/images/logo.png');
                }
                $mailData = array(
                    'first_name'    => $reserveSeats->user_name,
                    'email'         => $reserveSeats->email,
                    'user_name'     => $reserveSeats->user_name,
                    'form_name'     => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template'      => 'reservation_accepted',
                    'subject'       => 'weBOOK',
                    'data'          => $reserveSeats,
                    'logo_url'      => $logo_url,
                );

                if(!empty($mailData) && !empty($reserveSeats->email && !is_null($reserveSeats->email))){
                    Mail::to($reserveSeats->email)->send(new NotifyMail($mailData));
                }
            }
            return response(['status' => true , 'message' => 'Seat accpted successfully','data'=>$ReserveSeat]);
        }else{
            return response(['status' => false , 'message' => 'Seat cancelled failded,please try again','data'=>$ReserveSeat]);
        }
    }

    public function Rejected(Request $request){
        $inputs      = $request->all();
        $ReserveSeat                     = ReserveSeat::find($inputs['reserve_seat_id']);
        if($ReserveSeat){
            $ReserveSeat->status             = '2';
        }
        if($ReserveSeat->save()){
            // $Seat = Seat::find($ReserveSeat->seat_id);
            // $Seat->status='1';
            // $Seat->save();
            $reserveSeats=DB::table('reserve_seats as rs')
                            ->select('rs.*' , 'b.building_name' , 'o.office_name' , 'u.user_name','u.email', 'u.job_profile')
                            ->leftJoin('offices as o','o.office_id', '=','rs.office_id')
                            ->leftJoin('buildings as b','b.building_id', '=','o.building_id')
                            ->leftJoin('users as u','u.id', '=','rs.user_id')
                            ->where('rs.seat_id',$ReserveSeat->seat_id)
                            ->first();
            if($reserveSeats){
                $logo=env('Logo');
                if($logo){
                    $Admin = User::where('role','1')->first();
                    $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

                }else{
                    $logo_url = asset('front_end/images/logo.png');
                }
                $mailData = array(
                    'first_name'    => $reserveSeats->user_name,
                    'email'         => $reserveSeats->email,
                    'user_name'     => $reserveSeats->user_name,
                    'form_name'     => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template'      => 'reservation_cancel',
                    'subject'       => 'weBOOK',
                    'data'          => $reserveSeats,
                    'logo_url'      => $logo_url,
                );

                if(!empty($mailData) && !empty($reserveSeats->email && !is_null($reserveSeats->email))){
                    Mail::to($reserveSeats->email)->send(new NotifyMail($mailData));
                }
            }
            return response(['status' => true , 'message' => 'Seat rejected successfully','data'=>$ReserveSeat]);
        }else{
            return response(['status' => false , 'message' => 'Seat rejected failded,please try again','data'=>$ReserveSeat]);
        }
    }

    public function destroy($id){
        if(ReserveSeat::find($id)->delete())
            return ['status'=>'success','message'=>'Successfully deleted Reserve seat'];
        else
            return ['status'=>'failed','message'=>'Failed delete Reserve seat'];
    }
}
