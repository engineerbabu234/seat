<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractTemplates extends Model
{
    use SoftDeletes;
    protected $table = 'contract_templates';
    protected $primaryKey = 'id';

}
