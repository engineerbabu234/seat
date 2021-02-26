<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQuestionsAns extends Model
{
    use SoftDeletes;
    protected $table = 'user_questions_ans';
    protected $primaryKey = 'id';
}
