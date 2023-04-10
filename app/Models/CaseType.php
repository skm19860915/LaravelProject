<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'case_types';

    protected $fillable = [
        'id', 
        'Case_Category',  
        'Case_Type',
        'actual_cost',
        'VP_Pricing',
        'Required_Forms',
        'Required_Documentation_en',
        'Required_Documentation_es',
        'is_additional_service',
        "additional_services",
        'status',
        'created_at',  
        'updated_at'
    ];
}   