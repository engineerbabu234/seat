<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeImage extends Model
{
    use SoftDeletes;
    protected $table = 'office_images';
    protected $primaryKey = 'office_image_id';

    /**
     * [getImageAttribute description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getImageAttribute($value)
    {
        if (file_exists('uploads/office_image/' . $value)) {
            return asset('uploads/office_image/' . $value);
        }
        return asset('uploads/office_image/default-image.png');
    }

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

}
