<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'questionnaire';

    protected $fillable = [
        'client_id',
        'type',
        'name',
        'file',
        'data',
        'language',
        'created_at',
        'updated_at',
        'status'
    ];
}  