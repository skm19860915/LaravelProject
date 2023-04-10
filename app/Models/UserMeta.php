<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'usermeta';

    protected $fillable = [
        'id',
        'user_id',
        'meta_key',
        'meta_value',
        'created_at',
        'updated_at',
    ];
}   