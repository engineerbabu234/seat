<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExamHistory extends Model
{
    use SoftDeletes;
    protected $table = 'user_exam_history';
    protected $primaryKey = 'id';
}
