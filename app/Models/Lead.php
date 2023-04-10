<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'lead';

    protected $fillable = [
        'id',
        'name',
        'last_name',
        'email',
        'firm_id',
        'is_detained',
        'is_deported',
        'cell_phone',
        'home_phone',
        'dob',
        'language',
        'current_lat',
        'current_long',
        'Current_address',
        'lead_note',
        'birth_address',
        'document_path',
        'created_at',
        'updated_at',
        'status'
    ];
}   