<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientFiles extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_files';

    protected $fillable = [
        'id',
        'client_id',
        'petitioner',
        'beneficiary',
        'case_number',
        'case_type',
        'case_venue',
        'sponsor_type',
        'open_date',
        'staff_assigned',
        'attorney_of_record',
        'VA_Assigned',
        'created_at',
        'updated_at',
        'status'
    ];
}   