<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadNotes extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'lead_notes';

    protected $fillable = [
        'id',
        'lead_id',
        'notes',
        'created_at',
        'created_by',
        'updated_at',
        'status'
    ];
}   