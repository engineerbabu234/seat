<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConnections;
use App\Models\Building;
use App\Models\ContractDocuments;
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
                $whereStr .= " AND contract_templates.contract_title like '%{$search}%'";
                $whereStr .= " AND contract_documents.document_title like '%{$search}%'";
            }

            $columns = ['contract_templates.id', 'api_connections.api_title as contract_provider', 'contract_documents.document_title', 'contract_templates.updated_at', 'contract_templates.created_at', 'contract_templates.contract_title', 'contract_templates.contract_id', 'contract_templates.contract_restrict_seat', 'contract_templates.contract_description', '.contract_templates.contract_document_id'];

            $ContractTemplates = ContractTemplates::select($columns)->leftJoin("contract_documents", "contract_documents.id", "contract_templates.contract_document_id")->leftJoin("api_connections", "api_connections.id", "contract_documents.api_connection_id")->whereRaw($whereStr, $whereParams);
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
                $restrict = '';
                if ($value->contract_restrict_seat == 1) {
                    $restrict = 'Yes';
                } else {
                    $restrict = 'No';
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['contract_provider'] = $value->contract_provider;
                $final[$key]['document_title'] = $value->document_title;
                $final[$key]['contract_title'] = $value->contract_title;
                $final[$key]['contract_restrict_seat'] = $restrict;
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
        $api_provider = ApiConnections::where('api_type', 2)->get();

        return view('admin.contract_templates.index', compact('data', 'api_provider'));
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
            'contract_document_id' => 'required|unique:contract_templates,contract_document_id,contract_title', $request->contract_id,
            'contract_title' => 'required|unique:contract_templates',
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
        $ContractTemplates->contract_restrict_seat = $inputs['contract_restrict_seat'];
        $ContractTemplates->contract_description = $inputs['contract_description'];
        if ($ContractTemplates->save()) {
            $response = [
                'success' => true,
                'message' => 'Contract Templates Added successfull',
            ];
        } else {
            return back()->with('error', 'Contract Templates added failed,please try again');
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
        $contract_templates = ContractTemplates::find($id);

        $api_provider = ApiConnections::where('api_type', 2)->get();
        $documents = ContractDocuments::where('api_connection_id', $contract_templates->contract_id)->get();

        $response = [
            'success' => true,
            'html' => view('admin.contract_templates.edit', compact('contract_templates', 'documents', 'api_provider'))->render(),
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
        $ContractTemplates->contract_restrict_seat = $inputs['contract_restrict_seat'];
        $ContractTemplates->contract_description = $inputs['contract_description'];
        if ($ContractTemplates->save()) {
            $response = [
                'success' => true,
                'message' => 'Contract Templates Updated successfull',
            ];
        } else {
            return back()->with('error', 'Contract Templates added failed,please try again');
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
        $document_info = ContractTemplates::find($id);
        if (ContractTemplates::find($id)->delete()) {
            $OfficeAsset = OfficeAsset::Where('document_attech', 'like', '%' . $document_info->contract_document_id . '%')->get();
            $OfficeAsset->isEmpty();
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
            'message' => 'Contract Templates Updated successfull',
        ];

        return response()->json($response, 200);
    }

    /**
     * [add_document description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function add_document(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'document_title' => 'required|unique:contract_documents',
            'document_name' => 'required|mimes:doc,pdf,docx',
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

        $provider_name = ApiConnections::find($inputs['api_connection_id']);

        if ($request->hasFile('document_name')) {

            $file = $request->file('document_name');
            $content_bytes = file_get_contents($file);
            $base64_file_content = base64_encode($content_bytes);
            if ($provider_name->api_provider == 1 and isset($provider_name->username) and $provider_name->username != '' && $provider_name->password != '' && $provider_name->integrator_key != '' && $provider_name->host != '') {
                # Create the document model
                $username = $provider_name->username;
                $password = $provider_name->password;
                $integrator_key = $provider_name->integrator_key;
                $host = $provider_name->host;

                $docusign = new \DocuSign\Rest\Client([
                    'username' => $username,
                    'password' => $password,
                    'integrator_key' => $integrator_key,
                    'host' => $host,
                ]);
                $documentid = 0;
                if (isset($document_id) and $document_id != '') {
                    $documentid = $document_id;
                } else {
                    $documentid = 1;
                }

                $EnvelopeTemplate = $docusign->EnvelopeTemplate([
                    'status' => 'created',
                    'name' => ucfirst($inputs['document_title']),
                    'description' => ucfirst($inputs['document_title']),
                    'documents' => [
                        $docusign->document([
                            'document_base64' => $base64_file_content,
                            'name' => $inputs['document_title'],
                            'file_extension' => $request->file('document_name')->extension(),
                            'document_id' => $documentid,
                        ]),
                    ],
                ]);

                $envelopeSummary = $docusign->templates->createTemplate($EnvelopeTemplate);

                if ($envelopeSummary) {
                    $ContractDocuments = new ContractDocuments();
                    $ContractDocuments->document_title = $inputs['document_title'];
                    $ContractDocuments->api_connection_id = $inputs['api_connection_id'];
                    $ContractDocuments->document_name = str_replace(' ', '_', $request->file('document_name')->getClientOriginalName());
                    $ContractDocuments->document_description = $inputs['document_description'];
                    $ContractDocuments->template_id = $envelopeSummary->getTemplateId();
                    $ContractDocuments->save();

                    $response = [
                        'success' => true,
                        'api_connection_id' => $ContractDocuments->api_connection_id,
                        'document_id' => $ContractDocuments->id,
                        'message' => 'Contract Document Added successfull',
                    ];

                    return response()->json($response, 200);
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Contract Documents Not Added successfull Please contact Admin',
                    ];
                    return response()->json($response, 400);
                }

            } else {
                $response = [
                    'success' => false,
                    'message' => 'Please add Username, password,host and intergrator key or check entered record is correct or not',
                ];
                return response()->json($response, 400);
            }

        } else {
            $response = [
                'success' => false,
                'message' => 'Contract Documents Not Added successfull',
            ];
            return response()->json($response, 400);
        }

    }

    /**
     * [add_document description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function get_document_list(Request $request, $api_connection_id)
    {
        $documents = ContractDocuments::where('api_connection_id', $api_connection_id)->get();

        if ($documents) {
            $response = [
                'success' => true,
                'data' => $documents,
                'message' => 'Contract Documents',
            ];

            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Contract Documents available',
            ];
            return response()->json($response, 400);
        }
    }

    public function check_api(Request $request)
    {

        $inputs = $request->all();

        $rules['contract_id'] = 'required';

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $api_info = ApiConnections::find($inputs['contract_id']);

        if (($api_info->api_type == '2' and $api_info->api_provider == '1')) {

            $docusign = new \DocuSign\Rest\Client([
                'username' => $api_info->username,
                'password' => $api_info->password,
                'integrator_key' => $api_info->integrator_key,
                'host' => $api_info->host,
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

}
