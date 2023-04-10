<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client_profile extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_profile';

    protected $fillable = [
        'id', 
        'user_id',  
        'client_name',
        'client_dob',
        'petitioner_name',
        'petitioner_dob',
        'contact_phone',
        'in_city',
        'in_state',
        'out_city',
        'out_state',
        'note',
        'created_at',  
        'updated_at'
    ];
}   