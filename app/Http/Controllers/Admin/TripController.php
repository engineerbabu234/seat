<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Trip;

use App\Helpers\ImageHelper;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use Hash;
use Redirect,Response,DB,Config;
use Datatables;


class TripController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $trips = DB::table('trips as t')
                            ->select('t.*','u.user_name','d.user_name as driver_name')
                            ->leftJoin('users as u','u.id', '=','t.user_id')
                            ->leftJoin('users as d','d.id', '=','t.driver_id')
                            ->orderBy('t.trip_id','desc')
                            ->whereNull('t.deleted_at')
                            ->get();
            $number_key=1;
            foreach ($trips as $key => $value) {
                $value->number_key=$number_key; 
                if($value->status=='0'){
                    $value->status='Pending';
                }elseif($value->status=='1'){
                    $value->status='Completed';
                }elseif($value->status=='2'|| $value->status=='3'){
                    $value->status='Cancelled';
                }   
            }
            return datatables()->of($trips)->make(true);
        }
       $data['js'] = ['trip/index.js'];
        return view('admin.trip.index',compact('data'));
    }

    public function show(Request $request, $id){
        $data['trip']          = Trip::find($id);
   // return  $data['trip']->user->user_name;
        //$data['zones']          = Zone::get();
        //$data['zone_stop']      = ZoneStop::where('zone_id',$data['order']->zone_id)->get();
        //return $data['zone_stop'];

        /*if($data['order']){         
            if($request->ajax()){
                $order_products = DB::table('order_products as op')
                            ->select('op.*','p.product_name' ,'p.product_image')
                            ->leftJoin('products as p','p.product_id', '=','op.product_id')
                            ->orderBy('op.order_product_id','desc')
                            ->where('op.order_id',$data['order']->order_id)
                            ->whereNull('op.deleted_at')
                            ->get();
                $number_key=1;
                foreach ($order_products as $key => $value) {
                    $value->product_image= ImageHelper::getProductImage($value->product_image);
                    $value->number_key=$number_key;
                    $number_key++;
                    $value->total_price = $value->price*$value->quantity;
                }
                return datatables()->of($order_products)->make(true);
            }
        }*/
        //$data['js'] = ['trip/show.js'];
        return view('admin.trip.show',compact('data'));
    }

    public function edit($id){
        $data['category']=Category::find($id);
        $data['js'] = ['category/create.js'];
        return view('admin.category.edit',compact('data'));
    }
    public function update(Request $request,$id){
        $inputs      = $request->all();
        $rules = [
            'category_name'       => 'required',
            'category_image'      => 'required',
        ];
        $this->validate($request,$rules);
        $category_image = null;
        if ($request->hasFile('category_image')) {
            $category_image = str_random('10').'_'.time().'.'.request()->category_image->getClientOriginalExtension();
            request()->category_image->move(public_path('uploads/category/'), $category_image);
        }

        $Category                       = Category::find($id);
        $Category->category_name        = $inputs['category_name'];
        if($category_image){
           $Category->category_image    = $category_image; 
        }
        
        if($Category->update()){
            return back()->with('success','Category updated successfully');
        }else{
            return back()->with('error','Category updated failed');
        }
    }

    public function orderStatusChange(Request $request){
        $inputs                     = $request->all();
        $status                     = $inputs['status'];
    
        $Order                          = Order::find($inputs['order_id']);
        $Order->status                  = $inputs['status'];
        if($Order->update()){
            if($status==0){
                return ['status' => 'success' , 'message' => 'Order pending successfully', 'data'=>$Order];
            }elseif($status==1){
                return ['status' => 'success' , 'message' => 'Order completed successfully', 'data'=>$Order]; 
            }elseif($status==2){
                return ['status' => 'success' , 'message' => 'Order Rejecet successfully', 'data'=>$Order]; 
            }else{
                return ['status' => 'success' , 'message' => 'Order other successfully', 'data'=>$Order];  
            }
        }else{
           return ['status' => 'failed' , 'message' => 'Status updated failed'];   
        }
    }

    public function orderZoneChange(Request $request){
        $inputs                  = $request->all();
        $zone_id                 = $inputs['zone_id'];
    
        $Order                   = Order::find($inputs['order_id']);
        $Order->zone_id          = $inputs['zone_id'];
        if($Order->update()){
            return ['status' => 'success' , 'message' => 'Zone changed successfully', 'data'=>$Order];
        }else{
           return ['status' => 'failed' , 'message' => 'Zone changed failed,please try again'];   
        }
    }
}
