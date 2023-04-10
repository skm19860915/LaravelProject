<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newclient extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'new_client';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'user_id',
        'lead_id',
        'firm_id',
        'email',
        'cell_phone',
        'language',
        'type',
        'image_path',
        'is_portal_access',
        'is_detained',
        'is_deported',
        'is_outside_us',
        'residence_address',
        'mailing_address',
        'full_legal_name',
        'dob',
        'client_aliases',
        'previous_name',
        'maiden_name',
        'alien_number',
        'Social_security_number',
        'birth_address',
        'gender',
        'eye_color',
        'hair_color',
        'height',
        'weight',
        'race',
        'ethnicity',
        'religion',
        'created_at',
        'updated_at',
        'status',
        'QBCustomerID'
    ];
}   