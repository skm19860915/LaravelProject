<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_document';

    protected $fillable = [
        'id',
        'client_id',
        'case_id',
        'uploaded_by',
        'document',
        'title',
        'description',
        'created_at',
        'updated_at'
    ];
}   