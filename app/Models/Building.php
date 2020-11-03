<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use SoftDeletes;
    protected $table = 'buildings';
    protected $primaryKey = 'building_id';

    protected $fillable = [
        'building_name', 'building_address', 'description',
    ];

    /**
     * [offices description]
     * @return [type] [description]
     */
    public function offices()
    {
        return $this->hasMany('App\Models\Office', 'building_id', 'building_id');
    }

    /**
     * [office_asset description]
     * @return [type] [description]
     */
    public function office_asset()
    {
        return $this->hasMany('App\Models\OfficeAsset', 'building_id', 'building_id');
    }
}
