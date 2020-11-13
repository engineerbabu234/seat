<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Validator;

class BuildingController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $buildings = DB::table('buildings as b')
                ->select('b.*')
                ->where('b.user_id', Auth::id())
            /*->leftJoin('users as u','u.id', '=','t.user_id')
            ->leftJoin('users as d','d.id', '=','t.driver_id')*/
                ->orderBy('b.building_id', 'desc')
                ->whereNull('b.deleted_at')
                ->get();
            $number_key = 1;
            foreach ($buildings as $key => $value) {
                $value->number_key = $number_key;
                $office_count = DB::table('offices as o')->where('o.building_id', $value->building_id)->whereNull('o.deleted_at')->count();
                $value->office_count = $office_count;
                $number_key++;
            }
            return datatables()->of($buildings)->make(true);
        }
        $data['js'] = ['building/index.js'];
        $data['building_count'] = DB::table('buildings as b')->whereNull('deleted_at')->count();
        return view('admin.building.index', compact('data'));
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin.building.create');
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
            'building_name' => 'required',
            'building_address' => 'required',
            'description' => 'required',
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

        $BuildingCount = Building::whereNull('deleted_at')->count();
        $TOTAL_MAX_BUILDINGS = env('TOTAL_MAX_BUILDINGS');
        if (isset($TOTAL_MAX_BUILDINGS) && $BuildingCount >= $TOTAL_MAX_BUILDINGS) {
            return back()->with('error', 'You can add only ' . $TOTAL_MAX_BUILDINGS . ' buildings');
        }

        $Building = new Building();
        $Building->user_id = Auth::id();
        $Building->building_name = $inputs['building_name'];
        $Building->building_address = $inputs['building_address'];
        $Building->description = $inputs['description'];
        if ($Building->save()) {
            $response = [
                'success' => true,
                'message' => 'Building Added success',
            ];
        } else {
            return back()->with('error', 'Building added failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [officeList description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function officeList(Request $request, $id)
    {
        if ($request->ajax()) {
            $offices = DB::table('offices as o')
                ->select('o.*')
                ->where('o.building_id', $id)
                ->whereNull('o.deleted_at')
                ->orderBy('o.office_id', 'desc')
                ->get();
            $number_key = 1;
            foreach ($offices as $key => $value) {
                $value->number_key = $number_key;
                $seats_count = DB::table('seats as s')->where('s.office_id', $value->office_id)->whereNull('s.deleted_at')->count();
                $value->seats_count = $seats_count;
                $number_key++;
            }
            //print_r($offices);
            return datatables()->of($offices)->make(true);
        }
        $data['office_count'] = DB::table('offices as o')->where('o.building_id', $id)->whereNull('o.deleted_at')->count();
        $data['building_id'] = $id;
        $data['js'] = ['building/office_list.js'];
        return view('admin.building.office_list', compact('data'));
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id)
    {
        return view('admin.building.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $building = Building::find($id);

        $response = [
            'success' => true,
            'html' => view('admin.building.edit', compact('building'))->render(),
        ];

        return response()->json($response, 200);
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $rules = [
            'building_name' => 'required',
            'building_address' => 'required',
            'description' => 'required',
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

        $Building = Building::find($id);
        $Building->user_id = Auth::id();
        $Building->building_name = $inputs['building_name'];
        $Building->building_address = $inputs['building_address'];
        $Building->description = $inputs['description'];
        if ($Building->save()) {
            $response = [
                'success' => true,
                'message' => 'Building Updated success',
            ];
        } else {
            return back()->with('error', 'Building update failed,please try again');
        }

        return response()->json($response, 200);
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        if (Building::find($id)->delete()) {
            Office::where('building_id', $id)->delete();
            return ['status' => 'success', 'message' => 'Successfully deleted building and all ossociated offices'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete building'];
        }
    }
}
