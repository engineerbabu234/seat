<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConnections;
use Illuminate\Http\Request;
use Validator;

class ApiConnectionsController extends Controller
{

    /**
     * [index description]
     * @param  Request $request    [description]
     * @param  [type]  $buildingId [description]
     * @return [type]              [description]
     */
    public function index(Request $request)
    {

        $api_teleconference = array('1' => 'Zoom', '2' => 'Teams', '3' => 'Google Meet', '4' => 'Blue Jeans', '5' => 'Goto Meeting', '6' => 'Webex');
        $api_contract = array('1' => 'Docusign', '2' => 'PandaDoc', '3' => 'EverSign', '4' => 'SignRequest', '5' => 'AdobeSign');
        $api_type = array('1' => 'Teleconference', '2' => 'Contract');
        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND api_connections.api_name like '%{$search}%'";

            }

            $columns = ['api_connections.id', 'api_connections.updated_at', 'api_connections.created_at', 'api_connections.api_title', 'api_connections.api_type', 'api_connections.api_key', 'api_connections.api_secret', 'api_connections.api_provider', 'api_connections.username', 'api_connections.password', 'api_connections.integrator_key', 'api_connections.host'];

            $ApiConnections = ApiConnections::select($columns)->whereRaw($whereStr, $whereParams);
            $ApiConnections = $ApiConnections->orderBy('id', 'desc');

            if ($ApiConnections) {
                $total = $ApiConnections->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $ApiConnections = $ApiConnections->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $ApiConnections = $ApiConnections->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $ApiConnections = $ApiConnections->get();

            $final = [];
            $number_key = 1;

            foreach ($ApiConnections as $key => $value) {
                $provider = '';

                if ($value->api_type == 1) {
                    $provider = @$api_teleconference[$value->api_provider];
                } else {
                    $provider = @$api_contract[$value->api_provider];
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['api_type'] = @$api_type[$value->api_type];
                $final[$key]['api_provider'] = $provider;
                $final[$key]['api_title'] = $value->api_title;
                $final[$key]['api_key'] = $value->api_key;
                $final[$key]['api_secret'] = $value->api_secret;
                $final[$key]['username'] = $value->username;
                $final[$key]['password'] = $value->password;
                $final[$key]['integrator_key'] = $value->integrator_key;
                $final[$key]['host'] = $value->host;
                $final[$key]['updated_at'] = date('d/m/Y', strtotime($value->updated_at));
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();

        return view('admin.apiconnections.index', compact('data', 'api_teleconference', 'api_contract', 'api_type'));
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
            'api_type' => 'required',
            'api_provider' => 'required',
            'api_title' => 'required',

        ];

        if (($inputs['api_type'] == '2' or $inputs['api_provider'] == '1')) {
            $rules['username'] = 'required';
            $rules['password'] = 'required';
            $rules['integrator_key'] = 'required';
            $rules['host'] = 'required';
        }

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $ApiConnections = new ApiConnections();
        $ApiConnections->api_type = $inputs['api_type'];
        $ApiConnections->api_provider = $inputs['api_provider'];
        $ApiConnections->api_title = $inputs['api_title'];
        $ApiConnections->api_description = $inputs['api_description'];
        $ApiConnections->api_key = $inputs['api_key'];
        $ApiConnections->api_secret = $inputs['api_secret'];
        $ApiConnections->username = $inputs['username'];
        $ApiConnections->password = $inputs['password'];
        $ApiConnections->integrator_key = $inputs['integrator_key'];
        $ApiConnections->host = $inputs['host'];
        if ($ApiConnections->save()) {
            $response = [
                'success' => true,
                'message' => 'ApiConnections Added successfull',
            ];
        } else {
            return back()->with('error', 'ApiConnections added failed,please try again');
        }

        return response()->json($response, 200);

    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $apiconnections = ApiConnections::find($id);

        $api_teleconference = array('1' => 'Zoom', '2' => 'Teams', '3' => 'Google Meet', '4' => 'Blue Jeans', '5' => 'Goto Meeting', '6' => 'Webex');
        $api_contract = array('1' => 'Docusign', '2' => 'PandaDoc', '3' => 'EverSign', '4' => 'SignRequest', '5' => 'AdobeSign');
        $api_type = array('1' => 'Teleconference', '2' => 'Contract');
        $response = [
            'success' => true,
            'api_type' => $apiconnections->api_type,
            'api_provider' => $apiconnections->api_provider,
            'html' => view('admin.apiconnections.edit', compact('apiconnections', 'api_teleconference', 'api_contract', 'api_type'))->render(),
        ];

        return response()->json($response, 200);

    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {

        $inputs = $request->all();
        $rules = [
            'api_type' => 'required',
            'api_provider' => 'required',
            'api_title' => 'required',

        ];

        if (($inputs['api_type'] == '2' or $inputs['api_provider'] == '1')) {
            $rules['username'] = 'required';
            $rules['password'] = 'required';
            $rules['integrator_key'] = 'required';
            $rules['host'] = 'required';
        }

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $ApiConnections = ApiConnections::find($id);
        $ApiConnections->api_type = $inputs['api_type'];
        $ApiConnections->api_provider = $inputs['api_provider'];
        $ApiConnections->api_title = $inputs['api_title'];
        $ApiConnections->api_description = $inputs['api_description'];
        $ApiConnections->api_key = $inputs['api_key'];
        $ApiConnections->api_secret = $inputs['api_secret'];
        $ApiConnections->username = $inputs['username'];
        $ApiConnections->password = $inputs['password'];
        $ApiConnections->integrator_key = $inputs['integrator_key'];
        $ApiConnections->host = $inputs['host'];
        if ($ApiConnections->save()) {
            $response = [
                'success' => true,
                'message' => 'ApiConnections Updated successfull',
            ];
        } else {
            return back()->with('error', 'ApiConnections added failed,please try again');
        }

        return response()->json($response, 200);

    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request, $id)
    {
        if (ApiConnections::find($id)->delete()) {
            return ['status' => 'success', 'message' => 'Successfully deleted ApiConnections  '];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete ApiConnections and ApiConnections assets'];
        }
    }

    /**
     * [get_api_provider_list description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function get_api_provider_list(Request $request, $type)
    {
        if ($type == 1) {
            $provider = array('1' => 'Zoom', '2' => 'Teams', '3' => 'Google Meet', '4' => 'Blue Jeans', '5' => 'Goto Meeting', '6' => 'Webex');
        } else {
            $provider = array('1' => 'Docusign', '2' => 'PandaDoc', '3' => 'EverSign', '4' => 'SignRequest', '5' => 'AdobeSign');

        }
        $response = [
            'success' => true,
            'data' => $provider,
            'message' => 'ApiConnections Updated successfull',
        ];

        return response()->json($response, 200);
    }

    public function check_api(Request $request)
    {

        $inputs = $request->all();

        if (($inputs['api_type'] == '2' and $inputs['api_provider'] == '1')) {
            $rules['username'] = 'required';
            $rules['password'] = 'required';
            $rules['integrator_key'] = 'required';
            $rules['host'] = 'required';
        }

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $docusign = new \DocuSign\Rest\Client([
            'username' => $inputs['username'],
            'password' => $inputs['password'],
            'integrator_key' => $inputs['integrator_key'],
            'host' => $inputs['host'],
        ]);

        if (is_numeric($docusign->getAccountId())) {
            $response = [
                'success' => true,
                'message' => 'Api Connection successfull',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Api Connection failed',
            ];

            return response()->json($response, 400);
        }

    }

}
