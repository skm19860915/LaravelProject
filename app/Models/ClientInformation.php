<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientInformation extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_information_forms';

    protected $fillable = [
        'client_id',
        'file_type',
        'file',
        'case_id',
        'firm_id',
        'information',
        'status',
        'created_at',
        'updated_at'       
    ];
}   