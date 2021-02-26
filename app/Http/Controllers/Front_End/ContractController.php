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
use Twilio\Rest\Client;
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

            $columns = ['contract_templates.id', 'user_contract.is_expire', 'user_contract.deleted_at', 'api_connections.api_title as contract_provider', 'contract_documents.document_title', 'user_contract.updated_at', 'user_contract.created_at', 'contract_templates.contract_title', 'contract_templates.contract_id', 'contract_templates.contract_restrict_seat', 'contract_templates.contract_description', '.contract_templates.contract_document_id'];

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

            $ContractTemplates = $ContractTemplates->withTrashed()->get();

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
                $final[$key]['is_expire'] = $value->is_expire;
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

        $Contract = ContractDocuments::leftJoin("contract_templates", "contract_templates.contract_document_id", "contract_documents.id")->where('contract_templates.contract_restrict_seat', 1)->whereNull('contract_templates.deleted_at')->get();

        return view('usercontract.usercontract', compact('data', 'Contract'));
    }

    public function add_contract(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'document_id' => 'required',
        ];

        $messages = [
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
        if ($envelope_id) {
            $UserContract = new UserContract();
            $UserContract->user_id = Auth::id();
            $UserContract->document_id = $inputs['document_id'];
            $UserContract->envolop_id = $envelope_id;
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
        } else {
            $response = [
                'status' => false,
                'message' => 'Contract Already Exist',
            ];
            return response()->json($response, 200);
        }

    }

    public function send_request($document_id)
    {

        if ($document_id) {

            $columns = ['contract_templates.contract_restrict_seat', 'contract_documents.template_id', 'contract_documents.api_connection_id', 'contract_templates.contract_title', 'contract_documents.id', 'contract_documents.document_title', 'contract_documents.document_name', 'contract_templates.expired_option', 'contract_templates.expired_value'];
            $document = ContractDocuments::select($columns)->leftJoin("contract_templates", "contract_templates.contract_document_id", "contract_documents.id")->where('contract_templates.contract_restrict_seat', 1)->where('contract_documents.id', $document_id)->first();

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

                $user_contract = UserContract::where('document_id', $document_id)->where('user_id', Auth::id())->first();
                if ($user_contract) {
                    $envelopeSummary = $docusign->envelopes->getEnvelope($user_contract->envolop_id);
                    $expire_date = date('Y-m-d', strtotime($envelopeSummary->getExpireDateTime()));
                    $today = date('Y-m-d');

                    $voided = $envelopeSummary->getVoidedDateTime();

                }
                if ($user_contract == '' or $voided == '' or ($user_contract && $today >= $expire_date)) {

                    if ($user_contract) {
                        $val = $this->remove_expired_contract($user_contract->envolop_id, $docusign);
                    }
                    return $this->create_docusign_envelop($document->expired_value, $document->template_id, $document->document_title, $docusign);
                } else {

                    return false;
                }
            }

        } else {
            return false;
        }

    }

    public function get_user_contract_sign_result(Request $request, $asset_id)
    {

        $inputs = $request->all();

        $office_assets_info = OfficeAsset::find($asset_id);
        $ContractDocuments = ContractDocuments::leftJoin("contract_templates", "contract_templates.contract_document_id", "contract_documents.id")->whereIn('contract_documents.id', json_decode($office_assets_info->document_attech))->get();

        if ($ContractDocuments) {
            $contract_name = '';

            foreach ($ContractDocuments as $key => $value) {
                $contract_name .= '<p class="font-weight-bold">' . $value->contract_title . '</p>';
            }

            $contract = "<p>There are " . $contract_name . "  Contract attached to this seat, below are your customized results</p>";
            return $contract;
        }

    }

    public function remove_expired_contract($envelop_id, $docusign)
    {

        $user_contract = UserContract::where('envolop_id', $envelop_id)->first();
        $user_contract->is_expire = 1;
        $user_contract->save();

        $user_contract_de = UserContract::where('envolop_id', $envelop_id)->delete();

        $envelope_definition = $docusign->envelopeDefinition();
        $envelope_definition->setStatus('voided');
        $envelope_definition->setVoidedReason('Remove for expired envelope');
        $docusign->envelopes->update($envelop_id, $envelope_definition);
        return true;
    }

    public function create_docusign_envelop($expired_value, $template_id, $document_title, $docusign)
    {
        $user_id = Auth::id();
        $users = User::find($user_id);
        $email_subject = "Please Sign " . $document_title . " Document";
        $envelope_definition = $docusign->envelopeDefinition([
            'status' => 'sent', 'template_id' => $template_id, 'email_subject' => 'Please Sign This Document',
        ]);

        $signer = $docusign->TemplateRole([
            'email' => $users->email, 'name' => $users->user_name,
            'role_name' => 'signer',
        ]);

        $cc = $docusign->TemplateRole([
            'email' => $users->email, 'name' => $users->user_name,
            'role_name' => 'cc',
        ]);

        if ($expired_value != '') {
            $envelope_definition->setExpireEnabled('true');
            $envelope_definition->setExpireAfter($expired_value);
        }
        $envelope_definition->setTemplateRoles([$signer, $cc]);

        if ($expired_value) {
            $notification = new \DocuSign\eSign\Model\Notification();
            $notification->setUseAccountDefaults('false');
            $expirations = new \DocuSign\eSign\Model\Expirations();
            $expirations->setExpireEnabled('true');
            $expirations->setExpireAfter($expired_value);
            $expirations->setExpireWarn('0');
            $notification->setExpirations($expirations);
            $envelope_definition->setNotification($notification);
        }

        $results = $docusign->envelopes->createEnvelope($envelope_definition);
        $envelope_id = $results->getEnvelopeId();
        return $envelope_id;
    }

    public function listenToReplies(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', "https://api.github.com/users/$body");
            $githubResponse = json_decode($response->getBody());
            if ($response->getStatusCode() == 200) {
                $message = "*Name:* $githubResponse->name\n";
                $message .= "*Bio:* $githubResponse->bio\n";
                $message .= "*Lives in:* $githubResponse->location\n";
                $message .= "*Number of Repos:* $githubResponse->public_repos\n";
                $message .= "*Followers:* $githubResponse->followers devs\n";
                $message .= "*Following:* $githubResponse->following devs\n";
                $message .= "*URL:* $githubResponse->html_url\n";
                $this->sendWhatsAppMessage($message, $from);
            } else {
                $this->sendWhatsAppMessage($githubResponse->message, $from);
            }
        } catch (RequestException $th) {
            $response = json_decode($th->getResponse()->getBody());
            $this->sendWhatsAppMessage($response->message, $from);
        }
        return;
    }

    /**
     * Sends a WhatsApp message  to user using
     * @param string $message Body of sms
     * @param string $recipient Number of recipient
     */
    public function sendWhatsAppMessage(string $message, string $recipient)
    {

        $twilio_whatsapp_number = '+14155238886';
        $account_sid = $sid;
        $auth_token = $token;

        $user_id = Auth::id();
        $userinfo = User::find($user_id);

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }

}
