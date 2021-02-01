<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Models\InviteUser;
use App\Helpers\ImageHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;
use App\User;
use auth;

class HomeController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $data['buildings'] = Building::whereNull('deleted_at')->get();
        if ($data['buildings']) {
            foreach ($data['buildings'] as $key => $value) {
                if ($value->building_id) {
                    $OfficeCount = Office::where('building_id', $value->building_id)->whereNull('deleted_at')->count();
                    $value->office_count = $OfficeCount;
                }
            }
        }
        //return $data['building'];
        return view('admin.dashboard', compact('data'));
    }

    /**
     * [termCondition description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function termCondition(Request $request)
    {
        return view('term_condition');
    }

    public function inviteUsers(Request $request){
        $users = InviteUser::where('invited_user_id',auth::id())->orderBy('id','desc')->paginate(20);
        return view('admin.invite_user.index',compact('users'));
    }

    public function createInvitatinLink(){
        return view('admin.invite_user.create');
    }

    public function storeInvitationLink(Request $request){

        $inputs   = $request->all();
        $rules = [
            'name'              => 'required',
            'email'             => 'required|string|email|regex:/(.+)@(.+).(.+)/i|regex:/^[a-z]{4}/'
        ];        
        $this->validate($request,$rules);

        $InviteUser = new InviteUser;
        $InviteUser->name  = $request->name;
        $InviteUser->email = $request->email;
        $InviteUser->invited_user_id = auth::id();
        if($InviteUser->save()){
            
            $logo=env('Logo');
            if($logo){
                $Admin = User::where('role','1')->first();
                $logo_url = ImageHelper::getProfileImage($Admin->logo_image);

            }else{
                $logo_url = asset('front_end/images/logo.png');
            }
            $userMailData = array(
                'name'          => $request->name,
                'form_name'     => 'Support@gmail.com',
                'schedule_name' => 'weBOOK',
                'template'      => 'invite_user',
                'subject'       => 'Invite',
                'base_url'      => url('invite/user/registration',encrypt($InviteUser->id)),
                'logo_url'      => $logo_url,
            );
            if(!empty($userMailData)){
                Mail::to($InviteUser->email)->send(new NotifyMail($userMailData));
            }


             return redirect()->route('invite.users')->with('status',true)->with('message','Successfully sent invitation');
        }
        else{
             return redirect()->route('invite.users')->with('status',false)->with('message','Failed to send invitation');
        }
    }


    /**
     * [get_new_time description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function get_new_time(Request $request)
    {
        ;
        $response = [
            'success' => true,
            'time' => date('d/m/Y h:i A'),
        ];
        return response()->json($response, 200);
    }
}
