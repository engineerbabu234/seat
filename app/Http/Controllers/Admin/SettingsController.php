<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Validator;

class SettingsController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = array('0' => 'Not Access api', '1' => 'Access Api');
            $settings = DB::table('settings as b')
                ->select('b.*')
                ->whereNull('b.deleted_at')
                ->get();
            $number_key = 1;
            foreach ($settings as $key => $value) {
                $value->number_key = $number_key;
                $value->api_access = @$status[$value->api_access];
                $number_key++;
            }
            return datatables()->of($settings)->make(true);
        }

        return view('admin.settings.index');
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id)
    {
        return view('admin.settings.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $settings = Settings::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.settings.edit', compact('settings'))->render(),
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
            'api_access' => 'required',
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

        $Settings = Settings::find($id);

        $Settings->api_access = $inputs['api_access'];
        if ($Settings->save()) {
            $response = [
                'success' => true,
                'message' => 'Settings Updated success',
            ];
        } else {
            return back()->with('error', 'Settings update failed,please try again');
        }

        return response()->json($response, 200);
    }

}
