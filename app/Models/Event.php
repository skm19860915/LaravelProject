<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'event';

    protected $fillable = [
        'id',
        'title',
        'event_type',
        'event_title',
        'event_description',
        'related_id',
        's_date',
        'e_date',
        's_time',
        'e_time',
        'who_consult_with',
        'event_reminder',
        'attorney',
        'created_at',
        'updated_at',
        'status',
        'coutner',
        'google_id'
    ];
}   