<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationCategories extends Model
{
    use SoftDeletes;
    protected $table = 'notification_categories';

    protected $fillable = [
        'title',
    ];
}
