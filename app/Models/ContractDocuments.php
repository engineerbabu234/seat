<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractDocuments extends Model
{
    use SoftDeletes;
    protected $table = 'contract_documents';
    protected $primaryKey = 'id';

}
