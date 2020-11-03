<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Zone;
use Datatables;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriverContoller extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $delivery_boy = DB::table('users as u')
                ->select('u.*')
            //->leftJoin('zones as z', 'z.zone_id', '=', 'u.zone_id')
                ->where('u.role', '=', "3")
                ->get();
            $number_key = 1;
            foreach ($delivery_boy as $key => $value) {
                $value->profile_image = ImageHelper::getProfileImage($value->profile_image);
                $value->number_key = $number_key;
                $number_key++;
            }
            return datatables()->of($delivery_boy)->make(true);
        }

        $data['js'] = ['delivery_boy/index.js'];
        return view('admin.delivery_boy.index', compact('data'));
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $data['zones'] = Zone::get();
        $data['js'] = ['delivery_boy/create.js'];
        return view('admin.delivery_boy.create', compact('data'));
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
            'zone_name' => 'required',
            'user_name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->where('role', '3')],
            'phone_number' => ['required', Rule::unique('users', 'phone_number')->where('role', '3')],
            'password' => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:8',
        ];

        $this->validate($request, $rules);

        $profile_image = null;
        if ($request->hasFile('profile_image')) {
            $profile_image = str_random('10') . '_' . time() . '.' . request()->profile_image->getClientOriginalExtension();
            request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
        }

        $User = new User;
        $User->role = '3';
        $User->zone_id = $inputs['zone_name'];
        $User->user_name = $inputs['user_name'];
        $User->email = $inputs['email'];
        $User->phone_number = $inputs['phone_number'];
        $User->password = Hash::make($inputs['password']);
        if ($profile_image) {
            $User->profile_image = $profile_image;
        }
        if ($User->save()) {
            return back()->with('success', 'Delivery boy added successfully');
        } else {
            return back()->with('error', 'Delivery boy failed,please try again');
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
        $data['user'] = User::find($id);
        if ($data['user']) {
            $data['user']->profile_image = ImageHelper::getProfileImage($data['user']->profile_image);
            $data['user']->selfie_image = ImageHelper::getSelfieImage($data['user']->selfie_image);
            if ($data['user']->driverDocument) {
                foreach ($data['user']->driverDocument as $key => $value) {
                    $value->document = ImageHelper::getDriverDocumentImage($value->document);
                }
            }
        }
        //return $data['user']->driverDocument;
        $data['js'] = ['delivery_boy/show.js'];
        return view('admin.delivery_boy.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $data['user'] = User::find($id);
        if ($data['user']) {
            $data['user']->profile_image = ImageHelper::getProfileImage($data['user']->profile_image);
        }
        $data['zones'] = Zone::get();
        $data['js'] = ['delivery_boy/create.js'];
        return view('admin.delivery_boy.edit', compact('data'));
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
            'zone_name' => 'required',
            'user_name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->where('role', '3')->ignore($id, 'id')],
            'phone_number' => ['required', Rule::unique('users', 'phone_number')->where('role', '3')->ignore($id, 'id')],
            //'password'          => 'min:8|required_with:confirm_password|same:confirm_password',
            //'confirm_password'  => 'required|min:8',
        ];

        $this->validate($request, $rules);

        $profile_image = null;
        if ($request->hasFile('profile_image')) {
            $profile_image = str_random('10') . '_' . time() . '.' . request()->profile_image->getClientOriginalExtension();
            request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
        }

        $User = User::find($id);
        //$User->role               = '3';
        $User->zone_id = $inputs['zone_name'];
        $User->user_name = $inputs['user_name'];
        $User->email = $inputs['email'];
        $User->phone_number = $inputs['phone_number'];
        //$User->password           = Hash::make($inputs['password']);
        if ($profile_image) {
            $User->profile_image = $profile_image;
        }
        if ($User->save()) {
            return back()->with('success', 'Delivery boy updated successfully');
        } else {
            return back()->with('error', 'Delivery updated failed,please try again');
        }
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
        if ($status == '0') {
            $change_status = '1';
        } else {
            $change_status = '0';
        }

        $User = User::find($inputs['id']);
        $User->approve_status = $change_status;
        if ($User->update()) {
            if ($status == 0) {
                return ['status' => 'success', 'message' => 'User approved successfully', 'data' => $User];
            } else {
                return ['status' => 'success', 'message' => 'User unapproved successfully', 'data' => $User];
            }
        } else {
            return ['status' => 'failed', 'message' => 'Status updated failed'];
        }
    }

    /**
     * [tripHistory description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function tripHistory(Request $request)
    {
        $inputs = $request->all();
        if ($request->ajax()) {
            $orders = DB::table('trips as t')
                ->select('t.*', 'u.user_name')
                ->leftJoin('users as u', 't.user_id', '=', 'u.id')
                ->orderBy('t.trip_id', 'desc')
                ->where('t.driver_id', $inputs['user_id'])
            //->where('o.status',$inputs['status'])
                ->where(function ($query) use ($inputs) {
                    if ($inputs) {
                        if ($inputs['status'] == '0') {
                            $query->whereIn('t.status', ['0']);
                        } elseif ($inputs['status'] == '1') {
                            $query->whereIn('t.status', ['1']);
                        } elseif ($inputs['status'] == '2') {
                            $query->whereIn('t.status', ['2', '3']);
                        }

                    }
                })
                ->whereNull('t.deleted_at')
                ->get();
            $number_key = 1;
            foreach ($orders as $key => $value) {
                $value->number_key = $number_key;
                if ($value->status == '0') {
                    $value->status = 'Pending';
                } elseif ($value->status == '1') {
                    $value->status = 'Completed';
                } elseif ($value->status == '2') {
                    $value->status = 'Cancellled';
                } elseif ($value->status == '3') {
                    $value->status = 'Cancellled';
                } else {
                    $value->status = 'Other';
                }
            }
            return datatables()->of($orders)->make(true);
        }
    }
}
