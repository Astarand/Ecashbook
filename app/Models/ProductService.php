<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//die('hello');
class ProductService extends Model
{
    use HasFactory;protected $fillable = [
        'id',
        'added_by',
        'item_id',
        'item_type',
        'item_name',
        'hsn_code',
        'opening_stock_bal',
        'base_unit',
        'purchase_price',
        'selling_price',
        'disc_sell',
        'prod_image',
        'service_name',
        'sac_code',
        'created_at',
        'created_at',
    ];
}
