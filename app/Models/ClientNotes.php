<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNotes extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_notes';

    protected $fillable = [
        'id',
        'task_for',
        'related_id',
        'subject',
        'notes',
        'created_by',
        'created_at',
        'updated_at',
        'status'
    ];
}   