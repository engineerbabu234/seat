<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Office;

class HomeController extends Controller
{
    public function index()
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

    public function termCondition()
    {
        return view('term_condition');
    }
}
