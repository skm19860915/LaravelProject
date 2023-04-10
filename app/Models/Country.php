<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'countries';

    protected $fillable = [
        'id',
        'name',
        'code',
        'ISD',
        'updated_at'
    ];
}   