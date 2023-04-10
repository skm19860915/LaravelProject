<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminTask extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'admintask';

    protected $fillable = [
        'firm_admin_id',
        'task_type',
        'task',
        'mytask',
        'client_task',
        'assigned_to',
        'case_id',
        'allot_user_id',
        'priority',
        'due_date',
        'created_at',
        'updated_at',
        'status'
    ];
}   