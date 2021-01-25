<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
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
                $ContractDocuments = ContractDocuments::select($columns)->leftJoin("quesionaire", "quesionaire.id", "contract_documents.office_asset_id")->whereRaw($whereStr, $whereParams);
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
                $final[$key]['document_description'] = $value->document_description;
                $final[$key]['document_title'] = $value->document_title;
                $final[$key]['document_name'] = $value->document_name;
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
            'document_name' => 'required:mimes:doc,pdf,docx',
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

        $assets_id = $this->
            $document_name = null;
        if ($request->hasFile('document_name')) {
            $office_assets = OfficeAsset::find($inputs['office_asset_id']);
            $building = Building::find($office_assets->building_id);
            $office = Office::find($office_assets->office_id);

            $filename = time() . '.' . $request->file('document_name')->extension();
            $filePath = public_path() . '/'.(str_replace(' ','_', $building->building_name). '/'.(str_replace(' ','_', $office->office_name). '/'.(str_replace(' ','_', $office_assets->name));
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
        $ContractDocuments = ContractDocuments::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.contract_documents.edit', compact('ContractDocuments'))->render(),
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
            'document_title' => 'required',
            'document_name' => 'required:mimes:doc,pdf,docx',
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
        $ContractDocuments->user_id = Auth::id();
        $ContractDocuments->document_title = $inputs['document_title'];
        $ContractDocuments->document_name = $inputs['document_name'];
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
}
