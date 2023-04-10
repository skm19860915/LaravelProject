<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'log';

    protected $fillable = [
        'id',
        'title',
        'related_id',
        'message',
        'created_at',
        'updated_at',
        'status'
    ];
}   