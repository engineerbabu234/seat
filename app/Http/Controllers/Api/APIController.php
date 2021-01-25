<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class APIController extends Controller
{
    public $apiToken;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        $this->apiToken = strtoupper(uniqid(ApiHelper::getUniqueFileName(30)));
    }

    /**
     * [loginCheck description]
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author Jatin Bhatt
     */
    public function loginCheck(Request $request)
    {

        $rules = [
            'email' => "required|email",
            'password' => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'errors' => implode(",", $validator->errors()->all()),
            ];
            return response()->json($response, 200);
        }

        $auth = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'api_access' => 1,
        ]);

        if ($auth) {

            $user = User::where("email", $request->email)->first();
            $user['api_token'] = $this->apiToken;

            User::where("email", $request->email)->update([
                'api_token' => $this->apiToken,
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            $response = [
                'success' => true,
                'data' => $user,
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Auth failed (Contact to administrator)',
        ];

        return response()->json($response, 200);
    }

    /**
     * [logout description]
     * @return [type] [description]
     * @author Jatin Bhatt
     */
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token', $token)->first();

        if ($user) {

            $logout = User::where('id', $user->id)->update([
                'api_token' => null,
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            if ($logout) {
                $response = [
                    'success' => true,
                    'message' => 'You Are Successfully Logout.',
                ];

                return response()->json($response, 200);
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Auth failed (Api Token Not Found)',
            ];
            return response()->json($response, 200);
        }
    }
}
