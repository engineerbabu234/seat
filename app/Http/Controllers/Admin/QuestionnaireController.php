<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Auth;
use Illuminate\Http\Request;
use Validator;

class QuestionnaireController extends Controller
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
                $whereStr .= " AND questionnaires.questionnaire like '%{$search}%'";
            }

            $columns = ['questionnaires.id', 'questionnaires.questionnaire', 'questionnaires.correct_answer', 'questionnaires.created_at'];

            $Questionnaire = Questionnaire::select($columns)->whereRaw($whereStr, $whereParams);
            $Questionnaire = $Questionnaire->where("questionnaires.user_id", Auth::id());
            $Questionnaire = $Questionnaire->orderBy('questionnaires.id', 'desc');

            if ($Questionnaire) {
                $total = $Questionnaire->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $Questionnaire = $Questionnaire->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Questionnaire = $Questionnaire->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $Questionnaire = $Questionnaire->get();

            $final = [];
            $answer = array('0' => 'No', '1' => 'Yes');
            foreach ($Questionnaire as $key => $value) {

                $final[$key]['id'] = $value->id;
                $final[$key]['questionnaire'] = $value->questionnaire;
                $final[$key]['correct_answer'] = @$answer[$value->correct_answer];
                $final[$key]['created_at'] = date('d-m-Y H:i:s', strtotime($value->created_at));
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();

        return view('admin.questionnaire.index', compact('data'));
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
            'title' => 'required',
            'description' => 'required',
            'expired_date_option' => 'required',
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

        // $OfficeCount = DB::table('offices')->whereNull('deleted_at')->count();
        // $TOTAL_MAX_OFFICES = env('TOTAL_MAX_OFFICES');
        // if (isset($TOTAL_MAX_OFFICES) && $OfficeCount >= $TOTAL_MAX_OFFICES) {
        //     return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_OFFICES . ' office'];
        // }

        $Questionnaire = new Questionnaire();
        $Questionnaire->user_id = Auth::id();
        $Questionnaire->title = $inputs['title'];
        $Questionnaire->description = $inputs['description'];
        $Questionnaire->expired_date_option = $inputs['expired_date_option'];
        $Questionnaire->restrict_option = $inputs['restrict_option'];
        $Questionnaire->expired_date_value = $inputs['expired_date_value'];
        $Questionnaire->start_date = date('Y-m-d', strtotime($inputs['start_date']));
        $Questionnaire->expired_date = date('Y-m-d', strtotime($inputs['expired_date']));
        if ($Questionnaire->save()) {
            $response = [
                'success' => true,
                'message' => 'Questionnaire Added successfull',
            ];
        } else {
            return back()->with('error', 'Questionnaire added failed,please try again');
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
        $questionnaire = Questionnaire::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.questionnaire.edit', compact('questionnaire'))->render(),
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
            'title' => 'required',
            'description' => 'required',
            'expired_date_option' => 'required',
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

        // $OfficeCount = DB::table('offices')->whereNull('deleted_at')->count();
        // $TOTAL_MAX_OFFICES = env('TOTAL_MAX_OFFICES');
        // if (isset($TOTAL_MAX_OFFICES) && $OfficeCount >= $TOTAL_MAX_OFFICES) {
        //     return ['status' => 'failed', 'message' => 'You can add only ' . $TOTAL_MAX_OFFICES . ' office'];
        // }

        $Questionnaire = Questionnaire::find($id);
        $Questionnaire->user_id = Auth::id();
        $Questionnaire->title = $inputs['title'];
        $Questionnaire->description = $inputs['description'];
        $Questionnaire->expired_date_option = $inputs['expired_date_option'];
        $Questionnaire->restrict_option = $inputs['restrict_option'];
        $Questionnaire->expired_date_value = $inputs['expired_date_value'];
        $Questionnaire->start_date = date('Y-m-d', strtotime($inputs['start_date']));
        $Questionnaire->expired_date = date('Y-m-d', strtotime($inputs['expired_date']));
        if ($Questionnaire->save()) {
            $response = [
                'success' => true,
                'message' => 'Questionnaire Updated successfull',
            ];
        } else {
            return back()->with('error', 'Questionnaire Updated failed,please try again');
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
        if (Questionnaire::find($id)->delete()) {

            return ['status' => 'success', 'message' => 'Successfully deleted Questionnaire'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete Questionnaire'];
        }
    }

}
