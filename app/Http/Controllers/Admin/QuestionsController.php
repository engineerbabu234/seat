<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class QuestionsController extends Controller
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
                $whereStr .= " AND questions.question like '%{$search}%'";
            }

            $columns = ['questions.id', 'questions.question', 'questions.correct_answer', 'questions.created_at'];

            $Question = Question::select($columns)->whereRaw($whereStr, $whereParams);
            $Question = $Question->where("questions.user_id", Auth::id());
            $Question = $Question->orderBy('questions.id', 'desc');

            if ($Question) {
                $total = $Question->get();
            }

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $Question = $Question->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Question = $Question->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $Question = $Question->get();

            $final = [];
            $answer = array('0' => 'No', '1' => 'Yes');
            foreach ($Question as $key => $value) {

                $final[$key]['id'] = $value->id;
                $final[$key]['question'] = $value->question;
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

        return view('admin.question.index', compact('data'));
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
            'question' => 'required',
            'correct_answer' => 'required',
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

        $Question = new Question();
        $Question->user_id = Auth::id();
        $Question->question = $inputs['question'];
        $Question->correct_answer = $inputs['correct_answer'];
        if ($Question->save()) {
            $response = [
                'success' => true,
                'message' => 'Question Added successfull',
            ];
        } else {
            return back()->with('error', 'Question added failed,please try again');
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
        $question = Question::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.question.edit', compact('question'))->render(),
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
            'question' => 'required',
            'correct_answer' => 'required',
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

        $Question = Question::find($id);
        $Question->user_id = Auth::id();
        $Question->question = $inputs['question'];
        $Question->correct_answer = $inputs['correct_answer'];
        if ($Question->save()) {
            $response = [
                'success' => true,
                'message' => 'Question Updated successfull',
            ];
        } else {
            return back()->with('error', 'Question Updated failed,please try again');
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
        if (Question::find($id)->delete()) {

            return ['status' => 'success', 'message' => 'Successfully deleted Question'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete Question'];
        }
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function question_logic()
    {
        $question = Question::where('user_id', Auth::id())->get();
        $logic_ans = $answer = array('0' => 'No', '1' => 'Yes');
        $response = [
            'success' => true,
            'html' => view('admin.question.question_logic', compact('question', 'logic_ans'))->render(),
        ];

        return response()->json($response, 200);

    }

    /**
     * [save_question_logic description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function save_question_logic(Request $request)
    {
        $inputs = $request->all();
        $values = array();
        foreach ($inputs['logic'] as $key => $value) {
            if ($value != '') {
                $values[] .= $value;
            }
        }

        $rules = [
            'logic' => 'required',

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

        $question_logic = User::find(Auth::id());
        $question_logic->covid_logic = json_encode($values);
        if ($question_logic->save()) {
            $response = [
                'success' => true,
                'message' => 'Question Logic Added successfull',
            ];
        } else {
            return back()->with('error', 'Question Logic added failed,please try again');
        }

        return response()->json($response, 200);

    }

}
