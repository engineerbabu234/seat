<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model
{
    use SoftDeletes;
    protected $table = 'questions';

    protected $fillable = [
        'user_id', 'question',
    ];
}
