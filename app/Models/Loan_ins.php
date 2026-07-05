<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan_ins extends Model
{
    use HasFactory;

    protected $table = 'loan_ins'; // Specify table name

    protected $fillable = [
        'id',
        'added_by',
        'loanId',
        'ins_date',
        'payment_mode',
        'ins_amt',
        'curr_amt',
        'message',
        'ins_doc',
        'created_at',
        'updated_at',
    ];
}
