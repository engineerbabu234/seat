<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use App\Models\Team;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Validator;

class TeamController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teams = Team::whereNull('deleted_at')
                ->where('user_id',auth::id())
                ->get();
            return datatables()->of($teams)->make(true);
        }
        $totalTeams = Team::whereNull('deleted_at')
                    ->where('user_id',auth::id())
                    ->count();
        return view('admin.team.index', compact('totalTeams'));
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
        $input  = $request->all();
        $rules  = [
            'title'    => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return array('status' => 'error' , 'msg' => 'failed to add title', '' , 'errors' => $validator->errors());
        }

        $team = Team::insertGetId([
            'title'   => $input['title'],
            'description' => $input['description'],
            'user_id' => auth::id()
        ]);
        if($team)
            return ['status'=>'success','message'=>'Team added successfully'];
        else
            return ['status'=>'failed','message'=>'Failed to add team'];
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
                ->where('o.user_id',auth::id())
                ->get();
            $number_key = 1;
            foreach ($offices as $key => $value) {
                $value->number_key  = $number_key;
                $seats_count        = DB::table('seats as s')->where('s.office_id', $value->office_id)->whereNull('s.deleted_at')->count();
                $value->seats_count = $seats_count;
                $number_key++;
            }
            //print_r($offices);
            return datatables()->of($offices)->make(true);
        }
        $data['office_count'] = DB::table('offices as o')->where('o.building_id', $id)->whereNull('o.deleted_at')->where('o.user_id',auth::id())->count();
        $data['building_id']  = $id;
        $data['js']           = ['building/office_list.js'];
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
        $team = Team::find($id);
        if($team)
           return ['status'=>'success','message'=>'Record found','data'=>$team];
        else
           return ['status'=>'failed','message'=>'Record not found'];
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        $input  = $request->all();
        $rules  = [
            'title'    => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return array('status' => 'error' , 'msg' => 'failed to update title', '' , 'errors' => $validator->errors());
        }

        $team = Team::where('id',$input['id'])->update([
            'title'   => $input['title'],
            'description' => $input['description'] ?? ''
        ]);
        return ['status'=>'success','message'=>'Team updated successfully'];
        if($team)
            return ['status'=>'success','message'=>'Team updated successfully'];
        else
            return ['status'=>'failed','message'=>'Failed to update team'];
    }

    public function teamAccessRule(Request $request,$id){
        $buildings = \DB::table('buildings')->where('user_id',auth::id())->whereNull('deleted_at')->get();
        if($request->ajax()){
            $blocks    = \DB::table('block_team')
                         ->select('block_team.id','buildings.building_name','offices.office_name','office_seats.number','block_team.created_at')
                         ->join('buildings','block_team.building_id','=','buildings.building_id')
                         ->leftJoin('offices','block_team.office_id','=','offices.office_id')
                         ->leftJoin('office_seats','block_team.seat_id','=','office_seats.id')
                         ->where('team_id',$id)->get();
            return datatables()->of($blocks)->make(true);
        }
        return view('admin.team.access_rule',compact('buildings','id'));
    }

    public function teamBlockDelete($id){
         if(DB::table('block_team')->where('id',$id)->delete())
           return ['status'=>'success','message'=>'Deleted Successfully'];
         else
           return ['status'=>'failed','message'=>'Failed to delete'];
    }
    
    public function teamUsers($id){
        $users = \App\User::all();
        $selectedUsers = \DB::table('team_users')->where('team_id',$id)->get();
        $selectedUsers = array_column($selectedUsers->toarray(),'user_id');
        return view('admin.team.user',compact('id','users','selectedUsers'));
    }

    public function teamUserStore(Request $request){

        DB::beginTransaction();
        try{
            $users = $request->users;
            $storeData = array();
            if($users){
                foreach($users as $user){
                    array_push($storeData,[
                        'team_id' => $request->id,
                        'user_id' => $user
                    ]);
                }
                \DB::table('team_users')->where('team_id',$request->id)->delete();
                \DB::table('team_users')->insert($storeData);
            }
            \DB::commit();
            return back()->with('status','success')->with('message','User successfully added');
        }catch(\Exception $e){
            \DB::rollback();
            return back()->with('status','failed')->with('message','Failed to add user');
        }
    }
   
    public function ajaxGetoffice($id){
        $offices = \DB::table('offices')->where('building_id',$id)->whereNull('deleted_at')->get();
        if($offices->toArray())
          return ['status'=>'success','message'=>'Record found','data'=>$offices];
        else
        return ['status'=>'failed','message'=>'Record not found'];
    }

    public function ajaxGetSeat($id){
        $seats = \DB::table('office_seats')->where('office_id',$id)->whereNull('deleted_at')->get();
        if($seats->toArray())
          return ['status'=>'success','message'=>'Record found','data'=>$seats];
        else
          return ['status'=>'failed','message'=>'Record not found'];
    }

    public function teamBlock(Request $request){
        $input = $request->all();
        $storeData = array();
        $storeData['team_id'] = $input['id'];
        $storeData['building_id'] = $input['building'];
        $storeData['office_id']   = $input['office'];
        $storeData['seat_id']     = $input['seat'];
        $insetGetId = \DB::table('block_team')->insertGetid($storeData);
        if($insetGetId){
              return back()->with('status',true)->with('message','Blocked Successfully');
        }else{
              return back()->with('status',true)->with('message','Failed to block');
        }
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        if (Team::find($id)) {
            Team::find($id)->delete();
            return ['status' => 'success', 'message' => 'Successfully deleted building and all ossociated data'];
        } else {
            return ['status' => 'failed', 'message' => 'Failed delete team'];
        }
    }
}
