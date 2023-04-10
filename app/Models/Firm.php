<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'firms';

    protected $fillable = [
        'firm_name',
        'account_type',
        'email',
        'firm_admin_name',
        'firm_logo_path',
        'created_at',
        'updated_at',
        'status',
        'usercost',
        'translation',
        'is_vp_services'
    ];
}   