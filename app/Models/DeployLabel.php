<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeployLabel extends Model
{
    use SoftDeletes;
    protected $table = 'deploy_label';
    protected $primaryKey = 'id';

}
