<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationCategories;
use App\Models\NotificationQuestions;
use App\Models\NotificationQuestionsAns;
use Illuminate\Http\Request;
use Validator;

class NotificationQuestionsController extends Controller
{

    /**
     * [index description]
     * @param  Request $request    [description]
     * @param  [type]  $buildingId [description]
     * @return [type]              [description]
     */
    public function index(Request $request)
    {

        $anstype = array('1' => 'Single Choice Answer', '2' => 'Multiple Choice Answer', '3' => 'Free Text');

        if ($request->ajax()) {

            $whereStr = '1 = ?';
            $whereParams = [1];

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = trim(addslashes($request->search['value']));
                $whereStr .= " AND notification_questions.question like '%{$search}%'";
            }

            $columns = ['notification_questions.id', 'notification_questions.question', 'notification_questions.description', 'notification_questions.question', 'notification_questions.start_date', 'notification_questions.end_date', 'notification_questions.ans_type', 'notification_questions.user_type', 'notification_questions.repeat_option', 'notification_questions.repeat_value', 'notification_questions.updated_at'];

            $NotificationQuestions = NotificationQuestions::select($columns)->whereRaw($whereStr, $whereParams);
            $NotificationQuestions = $NotificationQuestions->orderBy('notification_questions.id', 'desc');

            if ($NotificationQuestions) {
                $total = $NotificationQuestions->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $NotificationQuestions = $NotificationQuestions->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $NotificationQuestions = $NotificationQuestions->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $NotificationQuestions = $NotificationQuestions->get();

            $final = [];
            $number_key = 1;
            foreach ($NotificationQuestions as $key => $value) {
                $user_type = '';
                if ($value->user_type == 2) {
                    $user_type = 'User';
                } else {
                    $user_type = 'Cleaner';
                }

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['question'] = $value->question;
                $final[$key]['description'] = $value->description;
                $final[$key]['user_type'] = $user_type;
                $final[$key]['ans_type'] = @$anstype[$value->ans_type];
                $final[$key]['repeat_value'] = ucfirst($value->repeat_value);
                $final[$key]['start_date'] = date('d/m/Y', strtotime($value->start_date));
                $final[$key]['end_date'] = date('d/m/Y', strtotime($value->end_date));
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
        $notification_category = NotificationCategories::get();

        return view('admin.notification_questions.index', compact('notification_category', 'anstype'));
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $rules = [];

        $rules = [
            'question' => 'required|unique:notification_questions,question',
            'category_id' => 'required',
            'user_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'ans_type' => 'required',
            'repeat_value' => 'required',
        ];

        if ($inputs['repeat'] == 1) {

            if (strtotime($inputs['start_date']) > strtotime($inputs['end_date'])) {
                $rules['end_date'] = 'required|after:start_date';
            }

            if (strtotime($inputs['start_date']) > strtotime($inputs['end_date'])) {
                $rules['end_date'] = 'required|after:start_date';
            }

        }

        if ($inputs['ans_type'] == 1 or $inputs['ans_type'] == 2) {
            $rules['answer.*'] = 'required';
        }

        $messages = [];
        $messages['category_id.required'] = 'Please select Category';
        $messages['question.required'] = 'Please Add question';
        $messages['user_type.required'] = 'Please Select User Type';
        $messages['start_date.required'] = 'Please Start Date';
        $messages['end_date.required'] = 'Please End Date';
        $messages['ans_type.required'] = 'Please Answare Type';
        $messages['repeat_value.required'] = 'Please Select Repeat value';

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $NotificationQuestions = new NotificationQuestions();

        $NotificationQuestions->question = $inputs['question'];
        $NotificationQuestions->description = $inputs['description'];
        $NotificationQuestions->category_id = $inputs['category_id'];
        $NotificationQuestions->start_date = date('Y-m-d', strtotime($inputs['start_date']));
        $NotificationQuestions->end_date = date('Y-m-d', strtotime($inputs['end_date']));
        $NotificationQuestions->user_type = $inputs['user_type'];
        $NotificationQuestions->ans_type = $inputs['ans_type'];
        $NotificationQuestions->repeat = $inputs['repeat'];
        $NotificationQuestions->repeat_value = $inputs['repeat_value'];

        if ($NotificationQuestions->save()) {

            if (isset($inputs['answer']) && $inputs['answer'] && $inputs['ans_type'] == 1 or $inputs['ans_type'] == 2) {
                foreach ($inputs['answer'] as $key => $value) {
                    $NotificationQuestionsAns = new NotificationQuestionsAns();
                    $NotificationQuestionsAns->question_id = $NotificationQuestions->id;
                    $NotificationQuestionsAns->answer = $value;
                    $NotificationQuestionsAns->save();
                }
            }

            $response = [
                'success' => true,
                'quesionaire_id' => $NotificationQuestions->id,
                'message' => 'Notification Questions Added successfull',
            ];
        } else {
            return back()->with('error', 'Notification Questions added failed,please try again');
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
        $notification_questions = NotificationQuestions::find($id);
        $notification_category = NotificationCategories::get();
        $answers = NotificationQuestionsAns::where('question_id', $id)->get();
        $anstype = array('1' => 'Single Choice Answer', '2' => 'Multiple Choice Answer', '3' => 'Free Text');

        $response = [
            'success' => true,
            'ans_type' => $notification_questions->ans_type,
            'html' => view('admin.notification_questions.edit', compact('notification_questions', 'notification_category', 'id', 'answers', 'anstype'))->render(),
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
            'question' => 'required|unique:notification_questions,question,' . $id,
            'category_id' => 'required',
            'user_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'ans_type' => 'required',
            'repeat_value' => 'required',
            'repeat' => 'required',
        ];

        if ($inputs['repeat'] == 1) {

            if (strtotime($inputs['start_date']) > strtotime($inputs['end_date'])) {
                $rules['end_date'] = 'required|after:start_date';
            }
        }

        $messages = [];
        $messages['category_id.required'] = 'Please select Category';
        $messages['question.required'] = 'Please Add question';
        $messages['user_type.required'] = 'Please Select User Type';
        $messages['start_date.required'] = 'Please Start Date';
        $messages['end_date.required'] = 'Please End Date';
        $messages['ans_type.required'] = 'Please Answare Type';
        $messages['repeat_value.required'] = 'Please Select Repeat value';
        $messages['repeat.required'] = 'Please Select Repeat ';

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $NotificationQuestions = NotificationQuestions::find($id);

        $NotificationQuestions->question = $inputs['question'];
        $NotificationQuestions->description = $inputs['description'];
        $NotificationQuestions->category_id = $inputs['category_id'];
        $NotificationQuestions->start_date = date('Y-m-d', strtotime($inputs['start_date']));
        $NotificationQuestions->end_date = date('Y-m-d', strtotime($inputs['end_date']));
        $NotificationQuestions->user_type = $inputs['user_type'];
        $NotificationQuestions->ans_type = $inputs['ans_type'];
        $NotificationQuestions->repeat = $inputs['repeat'];
        $NotificationQuestions->repeat_value = $inputs['repeat_value'];

        if ($NotificationQuestions->save()) {

            if (isset($inputs['answer']) && $inputs['answer'] && $inputs['ans_type'] == 1 or $inputs['ans_type'] == 2) {

                $delete = NotificationQuestionsAns::where('question_id', $id)->delete();

                foreach ($inputs['answer'] as $key => $value) {
                    $NotificationQuestionsAns = new NotificationQuestionsAns();
                    $NotificationQuestionsAns->question_id = $NotificationQuestions->id;
                    $NotificationQuestionsAns->answer = $value;
                    $NotificationQuestionsAns->save();
                }
            }

            $response = [
                'success' => true,
                'message' => 'Notification Questions Updated successfull',
            ];
        } else {
            return back()->with('error', 'Notification Questions Updated failed,please try again');
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

        $NotificationQuestions = NotificationQuestions::find($id)->delete();
        if ($NotificationQuestions->isEmpty()) {

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

}
