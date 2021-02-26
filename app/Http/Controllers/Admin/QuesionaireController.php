<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeAsset;
use App\Models\Quesionaire;
use App\Models\Question;
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

            $columns = ['quesionaire.id', 'quesionaire.title', 'quesionaire.description', 'quesionaire.expired_option', 'quesionaire.expired_value', 'quesionaire.start_date', 'quesionaire.expired_date', 'quesionaire.restriction', 'quesionaire.updated_at'];

            $Quesionaire = Quesionaire::select($columns)->whereRaw($whereStr, $whereParams);
            $Quesionaire = $Quesionaire->orderBy('quesionaire.id', 'desc');

            if ($Quesionaire) {
                $total = $Quesionaire->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $Quesionaire = $Quesionaire->take($request->get('length'))->skip($request->get('start'));
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
            $number_key = 1;
            $expired_value = '';
            $restriction = array('0' => 'No', '1' => 'Yes');
            foreach ($Quesionaire as $key => $value) {
                $questions = Question::where('quesionaire_id', $value->id)->count();
                if ($questions > 0) {
                    $questions_total = $questions;
                } else {
                    $questions_total = 0;
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['title'] = $value->title;
                $final[$key]['description'] = $value->description;
                $final[$key]['expired_option'] = $value->expired_value . ' ' . $value->expired_option;
                $final[$key]['questions'] = $questions_total;
                $final[$key]['updated_at'] = date('d/m/Y', strtotime($value->updated_at));
                $final[$key]['restriction'] = @$restriction[$value->restriction];
                $number_key++;
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
            'expired_option' => 'required',
            'expired_value' => 'required',
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

        $expired_option = '';
        $expired_value = '';
        if ($inputs['expired_value']) {
            $expired_data = explode('_', $inputs['expired_value']);
            $expired_option = $expired_data[0];
            $expired_value = $expired_data[1];
        }

        $Quesionaire = new Quesionaire();
        $Quesionaire->user_id = Auth::id();
        $Quesionaire->title = $inputs['title'];
        $Quesionaire->description = $inputs['description'];
        $Quesionaire->expired_option = $expired_option;
        $Quesionaire->expired_value = $expired_value;
        $Quesionaire->restriction = $inputs['restriction'];
        $Quesionaire->start_date = date('Y-m-d');

        if ($expired_option == 'Day') {
            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' days'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 day'));
            }
            $Quesionaire->expired_date = date('Y-m-d', strtotime($expired_date));

        } else if ($expired_option == 'Month') {

            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' months'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 month'));
            }
            $Quesionaire->expired_date = date('Y-m-d', strtotime($expired_date));

        } else if ($expired_option == 'Week') {
            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' weeks'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 week'));
            }
            $Quesionaire->expired_date = date('Y-m-d', strtotime($expired_date));
        }

        if ($Quesionaire->save()) {
            $response = [
                'success' => true,
                'quesionaire_id' => $Quesionaire->id,
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
        $questionarie = Quesionaire::get();
        $response = [
            'success' => true,
            'html' => view('admin.quesionaire.edit', compact('quesionaire', 'questionarie', 'id'))->render(),
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
            'expired_option' => 'required',
            'expired_value' => 'required',
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
        $expired_option = '';
        $expired_value = '';
        if ($inputs['expired_value']) {
            $expired_data = explode('_', $inputs['expired_value']);
            $expired_option = ucfirst($inputs['expired_option']);
            $expired_value = $expired_data[1];
        }
        $Quesionaire = Quesionaire::find($id);
        $Quesionaire->user_id = Auth::id();
        $Quesionaire->title = $inputs['title'];
        $Quesionaire->description = $inputs['description'];
        $Quesionaire->expired_option = ucfirst($inputs['expired_option']);
        $Quesionaire->expired_value = $expired_value;
        $Quesionaire->restriction = $inputs['restriction'];
        $Quesionaire->start_date = date('Y-m-d');

        if ($expired_option == 'Day') {
            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' days'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 day'));
            }
            $Quesionaire->expired_date = date('Y-m-d', strtotime($expired_date));

        } else if ($expired_option == 'Month') {

            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' months'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 month'));
            }
            $Quesionaire->expired_date = date('Y-m-d', strtotime($expired_date));

        } else if ($expired_option == 'Week') {
            if ($expired_value > 1) {
                $expired_date = date('Y-m-d H:i:s', strtotime('+ ' . $expired_value . ' weeks'));
            } else {
                $expired_date = date('Y-m-d H:i:s', strtotime('+1 week'));
            }
            $Quesionaire->expired_date = date('Y-m-d', strtotime($expired_date));
        }

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

        $OfficeAsset = OfficeAsset::Where('quesionaire_id', 'like', '%' . $id . '%')->get();

        if ($OfficeAsset->isEmpty()) {

            $modal_type = "delete_questions";
            $response = [
                'success' => true,
                'html' => view('modal_content', compact('modal_type', 'id'))->render(),
            ];

            return response()->json($response, 200);

        } else {
            $modal_type = "deleted_failed";
            $response = [
                'success' => false,
                'exist_inassets' => 1,
                'html' => view('modal_content', compact('modal_type'))->render(),
            ];

            return response()->json($response, 400);

        }
    }

    /**
     * [destroy_questions description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function destroy_questions(Request $request, $id)
    {
        // $office_assets = OfficeAsset::where('quesionaire_id', $id)->first();
        // $office_assets->quesionaire_id = JSON_REMOVE('quesionaire_id', replace(json_search('quesionaire_id', 'one', $id), '"', ''));
        // $office_assets->save();

        $questionarie = Quesionaire::find($id)->delete();
        if ($questionarie) {
            Question::where('quesionaire_id', $id)->delete();

            return ['status' => 'success', 'message' => 'Successfully deleted quesionaire and quesions'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete quesionaire and quesions'];
        }
    }

}
