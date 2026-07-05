<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liabilities extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'added_by',
        'propId',
        'added_date',
        'liabilities_type',
        'status',
        'created_at',
        'updated_at'
    ];
}
