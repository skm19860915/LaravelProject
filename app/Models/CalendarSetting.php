<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarSetting extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'calendar_setting';

    protected $fillable = [
        'user_id',
        'key',
        'value',
        'created_at',
        'updated_at'
    ];
}   