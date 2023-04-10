<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyInformation extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'family_information_forms';

    protected $fillable = [
        'family_id',
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