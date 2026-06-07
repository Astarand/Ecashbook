<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'requisition_date',
        'category',
        'details',
        'quantity',
        'amount',
        'priority',
        'return_exchange',
        'attachment',
        'comments',
        'status',
        'added_by',
        'u_type',
    ];
}
