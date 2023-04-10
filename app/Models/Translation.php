<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'translation';

    protected $fillable = [
        'document',
        'language',
        'can_tila_contact',
        'client_id',
        'case_id',
        'created_at',
        'updated_at'
    ];
}   