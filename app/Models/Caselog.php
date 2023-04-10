<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caselog extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'case_log';

    protected $fillable = [
        'id',
        'case_id',
        'message',
        'created_at',
        'updated_at'
    ];
}