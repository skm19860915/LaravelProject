<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffidavitDocumentRequest extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'affidavit_document_request';

    protected $fillable = [
        'id',
        'index',
        'case_id',
        'uploaded_by',
        'document_type',
        'document',
        'created_at',
        'updated_at',
        'status'
    ];
}   