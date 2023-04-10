<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TilaEmailTemplate extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'tila_email_template';

    protected $fillable = [
        'title',
        'subtitle',
        'massage',
        'standard_massage',
        'created_at',
        'updated_at',
        'is_undo',
        'status'
    ];
}   