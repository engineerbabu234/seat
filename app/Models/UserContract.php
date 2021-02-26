<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserContract extends Model
{
    use SoftDeletes;
    protected $table = 'user_contract';
    protected $primaryKey = 'id';

}
