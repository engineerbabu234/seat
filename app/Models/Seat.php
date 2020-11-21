<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
   	use SoftDeletes;
	protected $table = 'seats';
	protected $primaryKey = 'seat_id';
	
    protected $fillable = [
        'office_id' , 'seat_no' , 'description' , 'booking_mode' , 'seat_type' ,
    ];
}