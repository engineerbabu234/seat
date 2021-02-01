<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiConnections extends Model
{
    use SoftDeletes;
    protected $table = 'api_connections';
    protected $primaryKey = 'id';

}
