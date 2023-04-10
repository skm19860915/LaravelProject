<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirmCase extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'case';

    protected $fillable = [
        'id',
        'user_id',
        'assign_paralegal',
        'client_id',
        'firm_id',
        'case_category',
        'case_type',
        'case_cost',
        'priority',
        'case_file_path',
        'created_at',
        'updated_at',
        'VP_Assistance',
        'CourtDates',
        'additional_service',
        'CourtDates_Time',
        'created_by'
    ];
}   