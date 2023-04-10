<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'support_ticket';

    protected $fillable = [
        'by_role_id',
        'by_user_id',
        'message',
        'priority',
        'supporter_id',
        'created_at',
        'updated_at',
        'status'
    ];
}   