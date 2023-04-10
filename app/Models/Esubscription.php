<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Esubscription extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'esubscription';

    protected $fillable = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at',
        'status'
    ];
}   