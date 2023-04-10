<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFirmEmailTemplate extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'firm_email_template';

    protected $fillable = [
        'title',
        'message',
        'created_at',
        'updated_at',
        'status'
    ];
}   