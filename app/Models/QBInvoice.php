<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QBInvoice extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'qb_invoice';

    protected $fillable = [
        'id',
        'user_id',
        'firm_id',
        'client_id',
        'lead_id',
        'invoice_for',
        'qb_invoice',
        'client_name',
        'client_address',
        'description',
        'comment',
        'due_date',
        'paid_date',
        'destination_account',
        'refrence_number',
        'paid_amount',
        'tax_id',
        'Customer_ID',
        'amount',
        'invoice_id',
        'invoice_items',
        'payment_method',
        'status',
        'created_at',
        'updated_at'
    ];
}