<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationQuestions extends Model
{
    use SoftDeletes;
    protected $table = 'notification_questions';

    protected $fillable = [
        'question,category_id',
    ];
}
