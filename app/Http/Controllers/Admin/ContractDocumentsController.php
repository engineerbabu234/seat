<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\ContractDocuments;
use App\Models\Office;
use App\Models\OfficeAsset;
use Illuminate\Http\Request;
use Validator;

class ContractDocumentsController extends Controller
{

    /**
     * [index description]
     * @param  Request $request    [description]
     * @param  [type]  $buildingId [description]
     * @return [type]              [description]
     */
    public function index(Request $request, $office_asset_id = null)
    {

        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND contract_documents.document_title like '%{$search}%'";
            }

            $columns = ['contract_documents.id', 'contract_documents.document_title', 'contract_documents.document_name', 'contract_documents.document_description'];

            if (isset($office_asset_id) && $office_asset_id != "") {
                $ContractDocuments = ContractDocuments::select($columns)->leftJoin("office_asset", "office_asset.id", "contract_documents.office_asset_id")->whereRaw($whereStr, $whereParams);
                $ContractDocuments = $ContractDocuments->where("contract_documents.office_asset_id", $office_asset_id);

            }

            $ContractDocuments = $ContractDocuments->orderBy('contract_documents.id', 'desc');
            if ($ContractDocuments) {
                $total = $ContractDocuments->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $ContractDocuments = $ContractDocuments->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $ContractDocuments = $ContractDocuments->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $ContractDocuments = $ContractDocuments->get();

            $final = [];
            $number_key = 1;
            foreach ($ContractDocuments as $key => $value) {

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['document_title'] = $value->document_title;
                $final[$key]['document_name'] = $value->document_name;
                $final[$key]['document_description'] = $value->document_description;
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();

        return view('admin.contract_documents.index', compact('data'));
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
            'document_title' => 'required',
            'document_name' => 'required|mimes:doc,pdf,docx',
            'office_asset_id' => 'required',
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

        if ($request->hasFile('document_name')) {
            $file = $request->file('document_name');
            $office_assets = OfficeAsset::find($inputs['office_asset_id']);
            $building = Building::find($office_assets->building_id);
            $office = Office::find($office_assets->office_id);
            $document_path = public_path() . '/uploads/documents/';
            $filename = ApiHelper::getUniqueFileName() . '.' . $request->file('document_name')->extension();
            $building_path = $document_path . (str_replace(' ', '_', $building->building_name));

            $office_path = $document_path . (str_replace(' ', '_', $building->building_name)) . '/' . (str_replace(' ', '_', $office->office_name));
            $assets_path = $document_path . (str_replace(' ', '_', $building->building_name)) . '/' . (str_replace(' ', '_', $office->office_name)) . '/' . (str_replace(' ', '_', $office_assets->title));
            if (!file_exists($building_path)) {
                ApiHelper::makeDir($building_path, true);
            }

            if (!file_exists($office_path)) {
                ApiHelper::makeDir($office_path, true);
            }

            if (!file_exists($assets_path)) {
                ApiHelper::makeDir($assets_path, true);

            }
            $filePath = $assets_path;
            $file->move($filePath, $filename);
        }

        $ContractDocuments = new ContractDocuments();
        $ContractDocuments->document_title = $inputs['document_title'];
        $ContractDocuments->document_name = $filename;
        $ContractDocuments->document_description = $inputs['document_description'];
        $ContractDocuments->office_asset_id = $inputs['office_asset_id'];
        if ($ContractDocuments->save()) {
            $response = [
                'success' => true,
                'office_asset_id' => $inputs['office_asset_id'],
                'message' => 'Contract Documents Added successfull',
            ];
        } else {
            return back()->with('error', 'Contract Documents added failed,please try again');
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
        $document = ContractDocuments::find($id);

        $office_assets = OfficeAsset::find($document->office_asset_id);
        $building = Building::find($office_assets->building_id);
        $office = Office::find($office_assets->office_id);
        $document_path = public_path() . '/uploads/documents/';
        $assets_path = $document_path . (str_replace(' ', '_', $building->building_name)) . '/' . (str_replace(' ', '_', $office->office_name)) . '/' . (str_replace(' ', '_', $office_assets->title));
        $path = public_path($assets_path . '/' . $document->document_name);

        $response = [
            'success' => true,
            'html' => view('admin.contract_document.edit', compact('document', 'assets_path'))->render(),
        ];

        return response()->json($response, 200);

    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function document_details($id)
    {

        $office_assets = OfficeAsset::find($id);
        $columns = ['contract_documents.id', 'contract_templates.contract_document_id', 'contract_documents.document_title', 'contract_documents.document_name', 'contract_documents.document_description'];

        $documents = ContractDocuments::select($columns)->leftJoin("contract_templates", "contract_templates.contract_document_id", "contract_documents.id")->whereNotNull('api_connection_id')->where("contract_templates.contract_restrict_seat", 1)->get();

        if (isset($office_assets->document_attech) && $office_assets->document_attech != '') {

            $document_attech = array_values(json_decode($office_assets->document_attech, true));
            $response = [
                'success' => true,
                'document_attech' => $document_attech,
                'html' => view('admin.contract_document.view_documents', compact('documents', 'id'))->render(),
            ];

        } else {
            $response = [
                'success' => true,
                'html' => view('admin.contract_document.view_documents', compact('documents', 'id'))->render(),
            ];
        }

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
            'document_title' => 'required',
            'document_name' => 'mimes:doc,pdf,docx',
            'office_asset_id' => 'required',
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

        $ContractDocuments = ContractDocuments::find($id);

        if ($request->hasFile('document_name')) {
            $file = $request->file('document_name');
            $office_assets = OfficeAsset::find($inputs['office_asset_id']);
            $building = Building::find($office_assets->building_id);
            $office = Office::find($office_assets->office_id);
            $document_path = public_path() . '/uploads/documents/';
            $filename = ApiHelper::getUniqueFileName() . '.' . $request->file('document_name')->extension();
            $building_path = $document_path . (str_replace(' ', '_', $building->building_name));

            $office_path = $document_path . (str_replace(' ', '_', $building->building_name)) . '/' . (str_replace(' ', '_', $office->office_name));
            $assets_path = $document_path . (str_replace(' ', '_', $building->building_name)) . '/' . (str_replace(' ', '_', $office->office_name)) . '/' . (str_replace(' ', '_', $office_assets->title));
            if (!file_exists($building_path)) {
                ApiHelper::makeDir($building_path, true);
            }

            if (!file_exists($office_path)) {
                ApiHelper::makeDir($office_path, true);
            }

            if (!file_exists($assets_path)) {
                ApiHelper::makeDir($assets_path, true);

            }
            $filePath = $assets_path;
            $file->move($filePath, $filename);
            $ContractDocuments->document_name = $filename;
        }

        $ContractDocuments->document_title = $inputs['document_title'];
        $ContractDocuments->document_description = $inputs['document_description'];
        $ContractDocuments->office_asset_id = $inputs['office_asset_id'];
        if ($ContractDocuments->save()) {
            $response = [
                'success' => true,
                'message' => 'Contract Documents Updated successfull',
            ];
        } else {
            return back()->with('error', 'Contract Documents Updated failed,please try again');
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
        if (ContractDocuments::find($id)->delete()) {

            return ['status' => 'success', 'message' => 'Successfully deleted Contract Documents'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete Contract Documents'];
        }
    }

    /**
     * [save_documets_attech description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function save_documets_attech(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'document_attech' => 'required',

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

        $document_attech_info = OfficeAsset::find($inputs['office_assets_id']);

        $document_attech_info->document_attech = json_encode($inputs['document_attech']);
        if ($document_attech_info->save()) {
            $response = [
                'success' => true,
                'message' => 'Document Attech updated successfull',
            ];
        } else {
            return back()->with('error', 'Document Attech updated failed,please try again');
        }

        return response()->json($response, 200);

    }
}
