<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpfullTips extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'help_tips';

    protected $fillable = [
        'id',
        'title',
        'message',
        'created_at',
        'updated_at',
        'status'
    ];
}   