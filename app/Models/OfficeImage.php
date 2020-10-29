<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeImage extends Model
{
   	use SoftDeletes;
	protected $table = 'office_images';
	protected $primaryKey = 'office_image_id';

	public function getImageAttribute($value){
		if(file_exists('uploads/office_image/'.$value)){
		return asset('uploads/office_image/'.$value);
		}
		return asset('uploads/office_image/default-image.png');
	}


}
