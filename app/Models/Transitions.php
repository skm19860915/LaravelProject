<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDFFormMeta extends Model
{

    protected  $primaryKey = 'ID';
    protected $table = 'tila_pdfform_meta';

    protected $fillable = [
        'fieldtype',
        'FieldUniqueID',
        'FieldID',
        'FieldName',
        'pdffile',
        
    ];
}   