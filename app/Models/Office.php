<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
   	use SoftDeletes;
	protected $table = 'offices';
	protected $primaryKey = 'office_id';

	public function seats(){
		return $this->hasMany('App\Models\Seat','office_id','office_id');
	}

	public function images(){
		return $this->hasMany('App\Models\OfficeImage','office_id','office_id');
	}
	
	public function building(){
		return $this->hasOne('App\Models\Building','building_id','building_id');
	}

}