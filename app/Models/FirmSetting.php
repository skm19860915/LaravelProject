<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirmSetting extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'firm_setting';

    protected $fillable = [
        'firm_id',
        'category',
        'title',
        'message',
        'created_at',
        'updated_at',
        'status'
    ];
}   