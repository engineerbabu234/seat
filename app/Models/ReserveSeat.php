<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReserveSeat extends Model
{
   	use SoftDeletes;
	protected $table = 'reserve_seats';
	protected $primaryKey = 'reserve_seat_id';
	
    protected $fillable = [
        'user_id','seat_id' , 'office_id' , 'seat_no' , 'reserve_date',
    ];
}