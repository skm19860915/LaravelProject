<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulePayment extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'schedule_payment';

    protected $fillable = [
        'invoice_id',
        'schedule_for',
        'related_id',
        'recurring_amount',
        'first_payment',
        'next_payment',
        'payment_cycle',
        'credit_card',
        'created_at',
        'updated_at',
        'status'
    ];
}   