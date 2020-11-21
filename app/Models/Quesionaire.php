<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quesionaire extends Model
{
    use SoftDeletes;
    protected $table = 'quesionaire';

    protected $fillable = [
        'user_id', 'title',
    ];
}
