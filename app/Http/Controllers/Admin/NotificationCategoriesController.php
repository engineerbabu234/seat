<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConnections;
use App\Models\NotificationCategories;
use Illuminate\Http\Request;
use Validator;

class NotificationCategoriesController extends Controller
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
                $whereStr .= " AND notification_categories.title like '%{$search}%'";
            }

            $columns = ['notification_categories.id', 'notification_categories.title', 'notification_categories.description', 'api_connections.api_title', 'notification_categories.updated_at'];

            $NotificationCategories = NotificationCategories::select($columns)->leftJoin("api_connections", "api_connections.id", "notification_categories.notification_api_id")->whereRaw($whereStr, $whereParams);
            $NotificationCategories = $NotificationCategories->orderBy('notification_categories.id', 'desc');

            if ($NotificationCategories) {
                $total = $NotificationCategories->get();
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $NotificationCategories = $NotificationCategories->take($request->get('length'))->skip($request->get('start'));
            }

            if ($request->has('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $NotificationCategories = $NotificationCategories->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $NotificationCategories = $NotificationCategories->get();

            $final = [];
            $number_key = 1;
            foreach ($NotificationCategories as $key => $value) {

                $final[$key]['number_key'] = $number_key;
                $final[$key]['id'] = $value->id;
                $final[$key]['title'] = $value->title;
                $final[$key]['api_title'] = $value->api_title;
                $final[$key]['description'] = $value->description;
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
        $notification_api = ApiConnections::where('api_type', '3')->get();

        return view('admin.notification_categories.index', compact('data', 'notification_api'));
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
            'title' => 'required|unique:notification_categories,title,NULL,id,deleted_at,NULL',
            'description' => 'required',
            'notification_api_id' => 'required',

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

        $NotificationCategories = new NotificationCategories();

        $NotificationCategories->title = $inputs['title'];
        $NotificationCategories->description = $inputs['description'];
        $NotificationCategories->notification_api_id = $inputs['notification_api_id'];

        if ($NotificationCategories->save()) {
            $response = [
                'success' => true,
                'quesionaire_id' => $NotificationCategories->id,
                'message' => 'Notification Categories Added successfull',
            ];
        } else {
            return back()->with('error', 'Notification Categories added failed,please try again');
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
        $notification_categories = NotificationCategories::find($id);
        $notification_api = ApiConnections::where('api_type', '3')->get();
        $response = [
            'success' => true,
            'html' => view('admin.notification_categories.edit', compact('notification_categories', 'id', 'notification_api'))->render(),
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
            'notification_api_id' => 'required',
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

        $NotificationCategories = NotificationCategories::find($id);
        $NotificationCategories->title = $inputs['title'];
        $NotificationCategories->description = $inputs['description'];
        $NotificationCategories->notification_api_id = $inputs['notification_api_id'];

        if ($NotificationCategories->save()) {
            $response = [
                'success' => true,
                'message' => 'Notification Categories Updated successfull',
            ];
        } else {
            return back()->with('error', 'Notification Categories Updated failed,please try again');
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

        $NotificationCategories = NotificationCategories::find($id)->delete();
        if ($NotificationCategories->isEmpty()) {

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
