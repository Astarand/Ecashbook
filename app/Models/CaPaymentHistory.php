<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'ca_payment_history'; // Specify the table name

    protected $fillable = [
        'payment_date', 'entity_type', 'name', 'customer_id', 'agent_id', 
        'payment_phone', 'payment_type', 'gov_fees', 'service_fees', 'total_amount',
        'payment_method', 'payment_purpose', 'added_by'
    ];
}
