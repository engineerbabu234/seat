<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiHelper;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Artisan;
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
                $data['user']->logo_image = ImageHelper::getLogoImage($data['user']->logo_image);
            }

            $data['timezone_list'] = ApiHelper::timezone_list();

            //return $data['user'];
            $data['js'] = ['profile.js'];
            $logo_access = $data['user']->logo_status;

            return view('admin.auth.profile', compact('data', 'logo_access'));
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
        $user->timezone = $input['timezone'];

        if ($user->update()) {
            $this->update_env('APP_TIMEZONE', $user->timezone);
            $response = [
                'success' => true,
                'time' => date('d/m/Y h:i A'),
                'message' => 'Your profile updated successfully',

            ];

            return response()->json($response, 200);
            //return back()->with('success', 'Your profile updated successfully');
        } else {
            $response = [
                'success' => false,
                'message' => 'Your profile updated successfully',
            ];

            return response()->json($response, 400);
            //return back()->with('success', 'Your profile updated unsuccessfully');
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
            $response = [
                'success' => true,
                'image' => $image_64,
                'message' => 'Your profile image updated successfully',

            ];

            return response()->json($response, 200);

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
        $input = $request->all();

        $rules = [
            'profile_base64' => 'required',
        ];
        $this->validate($request, $rules);

        $image_64 = $input['profile_base64'];

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = str_random('10') . '_' . time() . '.' . $extension;
        $destinationPath = ImageHelper::$getProfileImagePath;

        $uploadPath = $destinationPath . '/' . $imageName;

        if (file_put_contents($uploadPath, base64_decode($image))) {

            $this->remove_user_image($user_id);

            $User = User::find($user_id);
            $User->profile_image = $imageName;
            $User->update();

            $response = [
                'success' => true,
                'image' => $image_64,
                'message' => 'Your profile image updated successfully',

            ];

            return response()->json($response, 200);

        } else {
            $response = [
                'success' => false,

                'message' => 'Your profile image updated failed,please try again',

            ];
            return response()->json($response, 400);

        }

        // endbase64

        $file = $_FILES['profile_image'];
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $imagename = str_random('10') . '_' . time() . '.' . $file_ext;
        $destinationPath = ImageHelper::$getProfileImagePath;
        #echo $destinationPath;die;
        if (move_uploaded_file($file_tmp, $destinationPath . '/' . $imagename)) {
            $User = User::find($user_id);
            $User->profile_image = $imagename;
            $User->update();
            return back()->with('success', 'Your profile image updated successfully');
            //return response(['status' => true , 'message' => 'success' , 'data' => $data]);
        } else {
            return back()->with('success', 'Your profile image updated failed,please try again');
            //return response(['status' => false , 'message' => 'failed' ]);
        }
    }

    /**
     * [updateProfileImageupdateLogoImage description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateLogoImage(Request $request)
    {
        $user_id = Auth::id();
        $input = $request->all();

        if (isset($input['color']) and $input['color'] != '') {
            $User = User::find($user_id);
            $User->color = $input['color'];
            $User->api_access = isset($input['api_access']) ? 1 : 0;
            $User->update();
        }

        $rules = [
            'logo_base64' => 'required',
        ];
        $this->validate($request, $rules);

        $image_64 = $input['logo_base64'];

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = str_random('10') . '_' . time() . '.' . $extension;
        $destinationPath = ImageHelper::$getLogoImagePath;

        $uploadPath = $destinationPath . '/' . $imageName;

        if (file_put_contents($uploadPath, base64_decode($image))) {

            $this->remove_logo_image($user_id);

            $User = User::find($user_id);
            $User->logo_image = $imageName;
            $User->update();

            return back()->with('success', 'Your logo image updated successfully');
        } else {
            return back()->with('success', 'Your logo image updated failed,please try again');
        }

        // endbase64

        $file = $_FILES['logo_image'];
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_tmp = $_FILES['logo_image']['tmp_name'];
        $imagename = str_random('10') . '_' . time() . '.' . $file_ext;
        $destinationPath = ImageHelper::$getLogoImagePath;
        #echo $destinationPath;die;

        if (move_uploaded_file($file_tmp, $destinationPath . '/' . $imagename)) {
            $User = User::find($user_id);
            $User->logo_image = $imagename;
            $User->update();
            return back()->with('success', 'Your logo image updated successfully');
            //return response(['status' => true , 'message' => 'success' , 'data' => $data]);
        } else {
            return back()->with('success', 'Your logo image updated failed,please try again');
            //return response(['status' => false , 'message' => 'failed' ]);
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

    public function remove_user_image($user_id)
    {
        $user = User::find($user_id);
        $destinationPath = ImageHelper::$getProfileImagePath;
        $removepath = $destinationPath . '/' . $user->profile_image;
        if (isset($user->profile_image) && file_exists(public_path($removepath))) {
            unlink(public_path($removepath));
        } else {
            return false;
        }
    }

    public function remove_logo_image($user_id)
    {
        $user = User::find($user_id);
        $destinationPath = ImageHelper::$getLogoImagePath;
        $removepath = $destinationPath . '/' . $user->logo_image;
        if (isset($user->logo_image) && file_exists(public_path($removepath))) {
            unlink(public_path($removepath));
        } else {
            return false;
        }
    }

    public function update_env($variable, $value)
    {

        if ($variable == "APP_TIMEZONE") {
            $value = "\"$value\"";
        }

        $values = array(
            $variable => $value,
        );
        $this->setEnvironmentValue($values);

        Artisan::call('config:cache');
        return true;
    }

    public function setEnvironmentValue(array $values)
    {

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {

                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }

            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;

    }
}
