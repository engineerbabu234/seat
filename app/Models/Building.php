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
        'building_name' , 'building_address' , 'description' ,
    ];
}