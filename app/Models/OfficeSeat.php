<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeSeat extends Model
{
    use SoftDeletes;
    protected $table = 'seats';
    protected $primaryKey = 'seat_id';
}
