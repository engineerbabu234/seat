<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use SoftDeletes;
    protected $table = 'offices';
    protected $primaryKey = 'office_id';

    /**
     * [building description]
     * @return [type] [description]
     */
    public function building()
    {
        return $this->hasOne('App\Models\Building', 'building_id', 'building_id');
    }

    /**
     * [office_asset description]
     * @return [type] [description]
     */
    public function office_asset()
    {
        return $this->hasMany('App\Models\OfficeAsset', 'building_id', 'building_id');
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
