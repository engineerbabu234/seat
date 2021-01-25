<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeatLabel extends Model
{
    use SoftDeletes;
    protected $table = 'seat_label';
    protected $primaryKey = 'id';

}
