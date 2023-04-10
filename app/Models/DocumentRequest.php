<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'document_request';

    protected $fillable = [
        'id',
        'client_id',
        'family_id',
        'case_id',
        'requested_by',
        'document_type',
        'document',
        'expiration_date',
        'alert',
        'created_at',
        'updated_at',
        'status'
    ];
}   