<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quesionaire;
use App\Models\Question;
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
    public function index(Request $request, $quesionaire_id = null)
    {

        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND questions.question like '%{$search}%'";
            }

            $columns = ['questions.id', 'questions.question', 'questions.correct_answer', 'quesionaire.title'];

            if (isset($quesionaire_id) && $quesionaire_id != "") {
                $Question = Question::select($columns)->leftJoin("quesionaire", "quesionaire.id", "questions.quesionaire_id")->whereRaw($whereStr, $whereParams);
                $Question = $Question->where("questions.quesionaire_id", $quesionaire_id);

            } else {

                $Question = Question::select($columns)->leftJoin("quesionaire", "quesionaire.id", "questions.quesionaire_id")->whereRaw($whereStr, $whereParams);
            }
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
            $quesionaire = '';
            $number_key = 1;
            foreach ($Question as $key => $value) {
                if ($value->quesionaire_id) {
                    $quesionaire = Quesionaire::whereIn('quesionaire_id', $value->quesionaire_id);

                }
                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['quesionaire'] = $value->title;
                $final[$key]['question'] = $value->question;
                $final[$key]['correct_answer'] = @$answer[$value->correct_answer];
                $number_key++;
            }

            $response['iTotalDisplayRecords'] = count($total);
            $response['iTotalRecords'] = count($total);
            $response['sEcho'] = intval($request->get('sEcho'));
            $response['aaData'] = $final;
            return $response;
        }
        $data = array();
        $questionarie = Quesionaire::get();
        return view('admin.question.index', compact('data', 'questionarie'));
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
            'quesionaire_id' => 'required',
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
        $Question->quesionaire_id = $inputs['quesionaire_id'];
        if ($Question->save()) {
            $response = [
                'success' => true,
                'quesionaire_id' => $inputs['quesionaire_id'],
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
        $questionarie = Quesionaire::get();

        $response = [
            'success' => true,
            'html' => view('admin.question.edit', compact('question', 'questionarie'))->render(),
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
            'quesionaire_id' => 'required',
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
        $Question->quesionaire_id = $inputs['quesionaire_id'];
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

}
