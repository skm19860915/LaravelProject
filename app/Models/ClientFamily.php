<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientFamily extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_family';

    protected $fillable = [
        'id',
        'client_id',
        'case_id',
        'type',
        'name',
        'email',
        'gender',
        'phon_number',
        'dob',
        'relationship',
        'created_at',
        'updated_at',
        'status'
    ];
}   