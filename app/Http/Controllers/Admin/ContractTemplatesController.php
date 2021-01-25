<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractTemplates;
use Illuminate\Http\Request;
use Validator;

class ContractTemplatesController extends Controller
{

    /**
     * [index description]
     * @param  Request $request    [description]
     * @param  [type]  $buildingId [description]
     * @return [type]              [description]
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND contract_templates.api_name like '%{$search}%'";
            }

            $columns = ['contract_templates.id', 'contract_templates.updated_at', 'contract_templates.created_at', 'contract_templates.contract_title', 'contract_templates.contract_id', 'contract_templates.contract_restrict_seat', 'contract_templates.contract_description', '.contract_templates.contract_document_id'];

            $ContractTemplates = ContractTemplates::select($columns)->whereRaw($whereStr, $whereParams);
            $ContractTemplates = $ContractTemplates->orderBy('id', 'desc');

            if ($ContractTemplates) {
                $total = $ContractTemplates->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $ContractTemplates = $ContractTemplates->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $ContractTemplates = $ContractTemplates->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $ContractTemplates = $ContractTemplates->get();

            $final = [];
            $number_key = 1;

            foreach ($ContractTemplates as $key => $value) {
                $provider = '';

                if ($value->contract_id == 1) {
                    $provider = @$api_teleconference[$value->contract_document_id];
                } else {
                    $provider = @$api_contract[$value->contract_document_id];
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['contract_id'] = @$contract_id[$value->contract_id];
                $final[$key]['contract_document_id'] = $provider;
                $final[$key]['contract_title'] = $value->contract_title;
                $final[$key]['contract_restrict_seat'] = $value->contract_restrict_seat;
                $final[$key]['contract_description'] = $value->contract_description;
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

        return view('admin.apiconnections.index', compact('data', 'api_teleconference', 'api_contract', 'contract_id'));
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
            'contract_id' => 'required',
            'contract_document_id' => 'required',
            'contract_title' => 'required',
            'contract_restrict_seat' => 'required',
            'contract_description' => 'required',
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

        $ContractTemplates = new ContractTemplates();
        $ContractTemplates->contract_id = $inputs['contract_id'];
        $ContractTemplates->contract_document_id = $inputs['contract_document_id'];
        $ContractTemplates->contract_title = $inputs['contract_title'];
        $ContractTemplates->api_description = $inputs['api_description'];
        $ContractTemplates->contract_restrict_seat = $inputs['contract_restrict_seat'];
        $ContractTemplates->contract_description = $inputs['contract_description'];
        if ($ContractTemplates->save()) {
            $response = [
                'success' => true,
                'message' => 'ContractTemplates Added successfull',
            ];
        } else {
            return back()->with('error', 'ContractTemplates added failed,please try again');
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
        $apiconnections = ContractTemplates::find($id);

        $api_teleconference = array('1' => 'Zoom', '2' => 'Teams', '3' => 'Google Meet', '4' => 'Blue Jeans', '5' => 'Goto Meeting', '6' => 'Webex');
        $api_contract = array('1' => 'Docusign', '2' => 'PandaDoc', '3' => 'EverSign', '4' => 'SignRequest', '5' => 'AdobeSign');
        $contract_id = array('1' => 'Teleconference', '2' => 'Contract');
        $response = [
            'success' => true,
            'html' => view('admin.apiconnections.edit', compact('apiconnections', 'api_teleconference', 'api_contract', 'contract_id'))->render(),
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
            'contract_id' => 'required',
            'contract_document_id' => 'required',
            'contract_title' => 'required',
            'contract_restrict_seat' => 'required',
            'contract_description' => 'required',
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

        $ContractTemplates = ContractTemplates::find($id);
        $ContractTemplates->contract_id = $inputs['contract_id'];
        $ContractTemplates->contract_document_id = $inputs['contract_document_id'];
        $ContractTemplates->contract_title = $inputs['contract_title'];
        $ContractTemplates->api_description = $inputs['api_description'];
        $ContractTemplates->contract_restrict_seat = $inputs['contract_restrict_seat'];
        $ContractTemplates->contract_description = $inputs['contract_description'];
        if ($ContractTemplates->save()) {
            $response = [
                'success' => true,
                'message' => 'ContractTemplates Updated successfull',
            ];
        } else {
            return back()->with('error', 'ContractTemplates added failed,please try again');
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
        if (ContractTemplates::find($id)->delete()) {
            return ['status' => 'success', 'message' => 'Successfully deleted ContractTemplates  '];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete ContractTemplates and ContractTemplates assets'];
        }
    }

    /**
     * [get_contract_document_id_list description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function get_contract_document_id_list(Request $request, $type)
    {
        if ($type == 1) {
            $provider = array('1' => 'Zoom', '2' => 'Teams', '3' => 'Google Meet', '4' => 'Blue Jeans', '5' => 'Goto Meeting', '6' => 'Webex');
        } else {
            $provider = array('1' => 'Docusign', '2' => 'PandaDoc', '3' => 'EverSign', '4' => 'SignRequest', '5' => 'AdobeSign');

        }
        $response = [
            'success' => true,
            'data' => $provider,
            'message' => 'ContractTemplates Updated successfull',
        ];

        return response()->json($response, 200);
    }

}
