<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextMessage extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'text_message';

    protected $fillable = [
        'id',
        'msgfrom',
        'msgto',
        'msg',
        'subject',
        'type',
        'created_at',
        'updated_at'
    ];
}   