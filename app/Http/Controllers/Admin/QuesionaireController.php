<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quesionaire;
use Auth;
use Illuminate\Http\Request;
use Validator;

class QuesionaireController extends Controller
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
                $whereStr .= " AND quesionaire.title like '%{$search}%'";
            }

            $columns = ['quesionaire.id', 'quesionaire.title', 'quesionaire.description', 'quesionaire.expired_date_option', 'quesionaire.start_date', 'quesionaire.expired_date', 'quesionaire.restriction'];

            $Quesionaire = Quesionaire::select($columns)->whereRaw($whereStr, $whereParams);
            $Quesionaire = $Quesionaire->where("quesionaire.user_id", Auth::id());
            $Quesionaire = $Quesionaire->orderBy('quesionaire.id', 'desc');

            if ($Quesionaire) {
                $total = $Quesionaire->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $Quesionaire = $Quesionaire->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Quesionaire = $Quesionaire->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $Quesionaire = $Quesionaire->get();

            $final = [];

            foreach ($Quesionaire as $key => $value) {

                $final[$key]['id'] = $value->id;
                $final[$key]['title'] = $value->title;
                $final[$key]['description'] = $value->description;
                $final[$key]['expired_date_option'] = $value->expired_date_option;
                $final[$key]['start_date'] = date('d-m-Y', strtotime($value->start_date));
                $final[$key]['expired_date'] = date('d-m-Y', strtotime($value->expired_date));
                $final[$key]['created_at'] = date('d-m-Y H:i:s', strtotime($value->created_at));
                $final[$key]['correct_answer'] = @$answer[$value->correct_answer];
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();

        return view('admin.quesionaire.index', compact('data'));
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

        $Quesionaire = new Quesionaire();
        $Quesionaire->user_id = Auth::id();
        $Quesionaire->title = $inputs['title'];
        $Quesionaire->description = $inputs['description'];
        $Quesionaire->expired_date_option = $inputs['expired_date_option'];
        $Quesionaire->restriction = $inputs['restriction'];
        $Quesionaire->expired_date_value = $inputs['expired_date_value'];
        $Quesionaire->start_date = date('Y-m-d', strtotime($inputs['start_date']));
        $Quesionaire->expired_date = date('Y-m-d', strtotime($inputs['expired_date']));
        if ($Quesionaire->save()) {
            $response = [
                'success' => true,
                'message' => 'Quesionaire Added successfull',
            ];
        } else {
            return back()->with('error', 'Quesionaire added failed,please try again');
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
        $quesionaire = Quesionaire::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.quesionaire.edit', compact('quesionaire'))->render(),
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

        $Quesionaire = Quesionaire::find($id);
        $Quesionaire->user_id = Auth::id();
        $Quesionaire->title = $inputs['title'];
        $Quesionaire->description = $inputs['description'];
        $Quesionaire->expired_date_option = $inputs['expired_date_option'];
        $Quesionaire->restriction = $inputs['restriction'];
        $Quesionaire->expired_date_value = $inputs['expired_date_value'];
        $Quesionaire->start_date = date('Y-m-d', strtotime($inputs['start_date']));
        $Quesionaire->expired_date = date('Y-m-d', strtotime($inputs['expired_date']));
        if ($Quesionaire->save()) {
            $response = [
                'success' => true,
                'message' => 'Quesionaire Updated successfull',
            ];
        } else {
            return back()->with('error', 'Quesionaire Updated failed,please try again');
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
        if (Quesionaire::find($id)->delete()) {

            return ['status' => 'success', 'message' => 'Successfully deleted Quesionaire'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete Quesionaire'];
        }
    }

}
