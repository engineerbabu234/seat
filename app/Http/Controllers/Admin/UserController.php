<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\ReserveSeat;
use App\Models\User;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = DB::table('users as u')
                ->select('u.*')
                ->where('u.role', '=', "2")
                ->whereIn('u.approve_status', ['0', '1'])
                ->orderBy('u.id', 'desc')
                ->get();
            $number_key = 1;
            foreach ($users as $key => $value) {
                $value->number_key = $number_key;
                $number_key++;
                $value->profile_image = ImageHelper::getProfileImage($value->profile_image);
            }
            return datatables()->of($users)->make(true);
        }
        $data['user_count'] = DB::table('users as u')->where('u.role', '=', "2")->count();
        $data['js'] = ['user/index.js'];
        return view('admin.user.index', compact('data'));
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id)
    {

        $data['js'] = ['user/show.js'];
        return view('admin.user.show', compact('data'));
    }

    /**
     * [activeStatusChange description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function activeStatusChange(Request $request)
    {
        $inputs = $request->all();
        $status = $inputs['status'];
        if ($status == '0') {
            $change_status = '1';
        } else {
            $change_status = '0';
        }

        $User = User::find($inputs['id']);
        $User->active_status = $change_status;
        if ($User->update()) {
            if ($status == 0) {
                return ['status' => 'success', 'message' => 'User activated successfully', 'data' => $User];
            } else {
                return ['status' => 'success', 'message' => 'User deactivated successfully', 'data' => $User];
            }
        } else {
            return ['status' => 'failed', 'message' => 'Status updated failed'];
        }
    }

    /**
     * [approveStatusChange description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function approveStatusChange(Request $request)
    {
        $inputs = $request->all();
        $status = $inputs['status'];
        if ($status == '1') {
            $change_status = '1';
        } else {
            $change_status = '2';
        }

        $User = User::find($inputs['id']);
        $User->approve_status = $change_status;
        if ($change_status == '2') {
            $User->approve_status = $change_status;
            //$User->deleted_at        = $change_status;
        } else {
            $User->approve_status = $change_status;
        }

        if ($User->update()) {
            $logo = env('Logo');
            if ($logo) {
                $Admin = User::where('role', '1')->first();
                $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

            } else {
                $logo_url = asset('front_end/images/logo.png');
            }
            if ($status == 1) {

                $mailData = array(
                    'first_name' => $User->user_name,
                    'email' => $User->email,
                    'user_name' => $User->user_name,
                    'form_name' => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template' => 'approved',
                    'subject' => 'weBOOK',
                    'data' => $User,
                    'base_url' => url('/login'),
                    'logo_url' => $logo_url,
                );
                if (!empty($mailData) && !empty($User->email && !is_null($User->email))) {
                    Mail::to($User->email)->send(new NotifyMail($mailData));
                }
                return ['status' => 'success', 'message' => 'User approved successfully', 'data' => $User];
            } else {
                $mailData = array(
                    'first_name' => $User->user_name,
                    'email' => $User->email,
                    'user_name' => $User->user_name,
                    'form_name' => 'Support@gmail.com',
                    'schedule_name' => 'weBOOK',
                    'template' => 'rejected',
                    'subject' => 'weBOOK',
                    'data' => $User,
                    'base_url' => url('/'),
                    'logo_url' => $logo_url,
                );
                if (!empty($mailData) && !empty($User->email && !is_null($User->email))) {
                    Mail::to($User->email)->send(new NotifyMail($mailData));
                }
                $newUser = $User->replicate();
                $newUser->setTable('delete_users');
                $newUser->save();
                //DB::table('delete_users')->insert($User);
                ReserveSeat::where('user_id', $User->id)->delete();
                $User->forceDelete();
                return ['status' => 'success', 'message' => 'User rejected successfully'];
            }
        } else {
            return ['status' => 'failed', 'message' => 'Status updated failed'];
        }
    }
}
