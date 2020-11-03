<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * [create description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function show(Request $request)
    {
        try {
            $user_id = Auth::id();
            $data['user'] = User::find($user_id);
            if ($data['user']) {
                $data['user']->profile_image = ImageHelper::getProfileImage($data['user']->profile_image);
                $data['user']->logo_image = ImageHelper::getProfileImage($data['user']->logo_image);
            }

            //return $data['user'];
            $data['js'] = ['profile.js'];
            return view('admin.auth.profile', compact('data'));
        } catch (\Exception $e) {
            return redirect()->route('500');
        }
    }

    /**
     * [edit description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        $user_id = Auth::id();
        $input = $request->all();

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|unique:users,phone_number,' . $user_id . ',id,deleted_at,NULL',
        ];
        $this->validate($request, $rules);

        $user = User::find($user_id);
        $user->user_name = $input['first_name'] . ' ' . $input['last_name'];
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        //$user->email        = $input['email'];
        $user->phone_number = $input['phone_number'];

        if ($user->update()) {
            return back()->with('success', 'Your profile updated successfully');
        } else {
            return back()->with('success', 'Your profile updated unsuccessfully');
        }
    }

    /**
     * [updatePassword description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updatePassword(Request $request)
    {

        $user_id = Auth::id();
        $input = $request->all();

        $rules = [
            'old_password' => 'required',
            'new_password' => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:8',
        ];
        $this->validate($request, $rules);

        // Validate
        /* $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
        return array('status' => 'error' , 'message' => 'failed to update new_password'  , 'errors' => $validator->errors());
        }*/

        if (!(Hash::check($request->old_password, Auth::user()->password))) {
            return back()->with('error', 'Your old password does not matches with the current password  , Please try again');

            //return array('status' => 'error' , 'message' => 'failed to update new_password'  , 'errors' => ['old_password' => 'Your old password does not matches with the current password  , Please try again']);
        } elseif (strcmp($request->old_password, $request->new_password) == 0) {
            return back()->with('error', 'New password cannot be same as your current password,Please choose a different new password');
            //return array('status' => 'error' , 'message' => 'failed to update new_password'  , 'errors' => ['new_password' => 'New password cannot be same as your current password,Please choose a different new password']);
        }
        $User = User::find($user_id);
        $User->password = Hash::make($request->new_password);
        if ($User->update()) {
            return back()->with('success', 'Your password updated successfully');
        } else {
            return back()->with('error', 'Your password updated unsuccessfully');
        }
    }

    /**
     * [updateProfileImage description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateProfileImage(Request $request)
    {
        $user_id = Auth::id();
        $inputs = $request->all();

        // $rules = [
        //     'profile_image'      => 'required',
        // ];

        // $validator = Validator::make($request->all(), $rules);
        // if($validator->fails()){
        //     return array('status' => 'error' , 'message' => 'failed to update new_password'  , 'errors' => $validator->errors());
        // }

        $profile_image = null;
        if ($request->hasFile('profile_image')) {
            $profile_image = str_random('10') . '_' . time() . '.' . request()->profile_image->getClientOriginalExtension();
            request()->profile_image->move(public_path('uploads/profiles/'), $profile_image);
        }

        $User = User::find($user_id);
        $User->profile_image = $profile_image;
        if ($User->save()) {
            return back()->with('success', 'Your profile image updated successfully');
            //return response(['status' => true , 'message' => 'success' , 'data' => $data]);
        } else {
            return back()->with('success', 'Your profile image updated failed,please try again');
            //return response(['status' => false , 'message' => 'failed' ]);
        }
    }

    /**
     * [updateLogoImage description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateLogoImage(Request $request)
    {
        $user_id = Auth::id();
        $inputs = $request->all();
        $logo_image = null;
        if ($request->hasFile('logo_image')) {
            $logo_image = str_random('10') . '_' . time() . '.' . request()->logo_image->getClientOriginalExtension();
            request()->logo_image->move(public_path('uploads/profiles/'), $logo_image);
        }

        $User = User::find($user_id);
        $User->logo_image = $logo_image;
        if ($User->save()) {
            return back()->with('success', 'Your logo updated successfully');
        } else {
            return back()->with('success', 'Your logo updated failed,please try again');
        }
    }

    /**
     * [passwordForm description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function passwordForm(Request $request)
    {
        return view('admin.profile.update_password');
    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request, $id)
    {
        //
    }
}
