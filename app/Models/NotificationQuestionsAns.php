<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationQuestionsAns extends Model
{
    use SoftDeletes;
    protected $table = 'notification_questions_ans';

    protected $fillable = [
        'question_id,answer',
    ];
}
