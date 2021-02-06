<?php

namespace App\Http\Controllers\Front_End;

use App\Http\Controllers\Controller;
use App\Models\ApiConnections;
use App\Models\ContractDocuments;
use App\Models\OfficeAsset;
use App\Models\User;
use App\Models\UserContract;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ContractController extends Controller
{

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

            $columns = ['contract_templates.id', 'api_connections.api_title as contract_provider', 'contract_documents.document_title', 'user_contract.updated_at', 'user_contract.created_at', 'contract_templates.contract_title', 'contract_templates.contract_id', 'contract_templates.contract_restrict_seat', 'contract_templates.contract_description', '.contract_templates.contract_document_id'];

            $ContractTemplates = UserContract::select($columns)->leftJoin("contract_documents", "contract_documents.id", "user_contract.document_id")->leftJoin("api_connections", "api_connections.id", "contract_documents.api_connection_id")->leftJoin("contract_templates", "contract_templates.contract_document_id", "contract_documents.id")->whereRaw($whereStr, $whereParams);

            $ContractTemplates = $ContractTemplates->where("user_contract.user_id", Auth::User()->id);

            $ContractTemplates = $ContractTemplates->orderBy('user_contract.id', 'desc');

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

        $Contract = ContractDocuments::leftJoin("contract_templates", "contract_templates.contract_document_id", "contract_documents.id")->where('contract_templates.contract_restrict_seat', 1)->get();

        return view('usercontract.usercontract', compact('data', 'Contract'));
    }

    public function add_contract(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'document_id' => 'required|unique:user_contract,document_id,user_id',
        ];

        $messages = [
            'document_id.unique' => 'This Document has already been taken',
            'document_id.required' => 'Please Select Document For Contract',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $envelope_id = $this->send_request($inputs['document_id']);

        $UserContract = new UserContract();
        $UserContract->user_id = Auth::id();
        $UserContract->document_id = $inputs['document_id'];
        if ($envelope_id) {
            $UserContract->envolop_id = $envelope_id;
        }

        if ($UserContract->save()) {
            $response = [
                'success' => true,
                'message' => 'Contract Added successfull, Please check your Email for signature',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => true,
                'message' => 'Contract Not Added successfull Please Contact Admin',
            ];
            return response()->json($response, 400);
        }

    }

    public function send_request($document_id)
    {

        if ($document_id) {

            $document = ContractDocuments::where('id', $document_id)->first();
            $provider_name = ApiConnections::find($document->api_connection_id);
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

                $user_id = Auth::id();
                $users = User::find($user_id);

                $envelope_definition = $docusign->envelopeDefinition([
                    'status' => 'sent', 'template_id' => $document->template_id, 'email_subject' => 'Please Sign This Document',
                ]);

                $signer = $docusign->TemplateRole([
                    'email' => $users->email, 'name' => $users->user_name,
                    'role_name' => 'signer',
                ]);

                $cc = $docusign->TemplateRole([
                    'email' => $users->email, 'name' => $users->user_name,
                    'role_name' => 'cc',
                ]);

                $envelope_definition->setTemplateRoles([$signer, $cc]);

                $results = $docusign->envelopes->createEnvelope($envelope_definition);

                $envelope_id = $results->getEnvelopeId();
                return $envelope_id;
            }

        } else {
            return false;
        }

    }

    public function get_user_contract_sign_result(Request $request)
    {

        $inputs = $request->all();

        $office_assets_info = OfficeAsset::find($inputs['asset_id']);
        $ContractDocuments = ContractDocuments::whereIn('id', json_decode($office_assets_info->document_attech))->pluck('id')->all();

        if ($ContractDocuments) {
            $contract = "<p>There are " . count($ContractDocuments) . "  Contract attached to this seat, below are your customized results</p>";
            return $contract;
        }

    }

}
