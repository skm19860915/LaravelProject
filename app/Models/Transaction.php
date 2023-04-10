<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected  $primaryKey = 'id';
    protected $table = 'transactions';

    protected $fillable = [
        'tx_id',
        'amount',
        'user_id',
        'type',
        'related_id',
        'responce',
        'paymenttype'
        
    ];
}   