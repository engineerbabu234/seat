<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Redirect;

class VehicleTypeController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vehicleTypes = DB::table('vehicle_types as vt')
                ->select('vt.*', )
            //->join('categories as c', 'c.category_id','=','p.category_id')
                ->whereNull('vt.deleted_at')
                ->orderBy('vt.vehicle_type_id', 'desc')
                ->get();
            $number_key = 1;
            foreach ($vehicleTypes as $key => $value) {
                $value->number_key = $number_key;
                $number_key++;
                $value->image = ImageHelper::getVehicleTypeImage($value->image);
            }
            return datatables()->of($vehicleTypes)->make(true);
        }
        $data['js'] = ['vehicle_type/index.js'];
        return view('admin.vehicle_type.index', compact('data'));
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        //$data['category']=Category::get();
        $data['js'] = ['vehicle_type/create.js'];
        //return $data['js'];
        return view('admin.vehicle_type.create', compact('data'));
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
            'vehicle_type_name' => 'required',
            'image' => 'required',

        ];

        $this->validate($request, $rules);
        $image = null;
        if ($request->hasFile('image')) {
            $image = str_random('10') . '_' . time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('uploads/vehicle_type/'), $image);
        }

        $VehicleType = new VehicleType();
        $VehicleType->name = $inputs['vehicle_type_name'];
        if ($image) {
            $VehicleType->image = $image;
        }
        if ($VehicleType->save()) {
            return Redirect('admin/vehicle_type')->with('success', 'Vehicle Type added successfully');
        } else {
            return back()->with('error', 'Vehicle Type added failed,please try again');
        }
    }

    /**
     * [show description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function show(Request $request, $id)
    {
        $data['product'] = Product::find($id);
        if ($data['product']) {
            $data['product']->product_image = ImageHelper::getProductImage($data['product']->product_image);
            if ($data['product']->category_id == 2) {
                if ($request->ajax()) {
                    $product_combos = DB::table('product_combos as pc')
                        ->select('pc.*')
                        ->where('pc.product_id', $data['product']->product_id)
                        ->whereNull('pc.deleted_at')
                        ->get();
                    $number_key = 1;
                    foreach ($product_combos as $key => $value) {
                        $value->number_key = $number_key;
                        $number_key++;
                    }
                    return datatables()->of($product_combos)->make(true);
                }
            }
        }
        $data['js'] = ['product/show.js'];
        return view('admin.product.show', compact('data'));
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $data['vehicle_type'] = VehicleType::find($id);
        if ($data['vehicle_type']) {
            $data['vehicle_type']->image = ImageHelper::getVehicleTypeImage($data['vehicle_type']->image);
        }
        $data['js'] = ['vehicle_type/create.js'];
        return view('admin.vehicle_type.edit', compact('data'));
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
            'vehicle_type_name' => 'required',
            //'image'                 => 'required',

        ];

        $this->validate($request, $rules);
        $image = null;
        if ($request->hasFile('image')) {
            $image = str_random('10') . '_' . time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('uploads/vehicle_type/'), $image);
        }

        $VehicleType = VehicleType::find($id);
        $VehicleType->name = $inputs['vehicle_type_name'];
        if ($image) {
            $VehicleType->image = $image;
        }
        if ($VehicleType->save()) {
            return Redirect('admin/vehicle_type')->with('success', 'Vehicle type updated successfully');
        } else {
            return back()->with('error', 'Vehicle type updated failed,please try again');
        }
    }
}
