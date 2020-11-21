<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeAsset extends Model
{
    use SoftDeletes;
    protected $table = 'office_asset';

    /**
     * [building description]
     * @return [type] [description]
     */
    public function building()
    {
        return $this->hasOne('App\Models\Building', 'building_id', 'building_id');
    }

    /**
     * [office description]
     * @return [type] [description]
     */
    public function office()
    {
        return $this->hasOne('App\Models\Office', 'office_id', 'office_id');
    }

    /**
     * [seats description]
     * @return [type] [description]
     */
    public function seats()
    {
        return $this->hasMany('App\Models\Seat', 'office_id', 'office_id');
    }

    /**
     * [images description]
     * @return [type] [description]
     */
    public function images()
    {
        return $this->hasMany('App\Models\OfficeImage', 'office_id', 'office_id');
    }
}
