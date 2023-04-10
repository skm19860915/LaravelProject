<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'client_task';

    protected $fillable = [
        'type',
        'task_for',
        'related_id',
        'title',
        'description',
        // 's_date',
        // 's_time',
        'e_date',
        'e_time',
        'created_at',
        'updated_at',
        'created_by',
        'status'
    ];
}   