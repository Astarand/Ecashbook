<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//die('hrrr');
class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'added_by',
        'item_id',
        'item_type',
        'item_name',
        'service_name',
        'hsn_code',
        'sac_code',
        'gst_rate',
        'gov_pay',
        'ser_pay',
        'opening_stock_bal',
        'base_unit',
        'purchase_price',
        'selling_price',
        'ser_selling_price',
        'disc_sell',
        'ser_disc_sell',
        'disc_sell_type',
        'ser_disc_sell_type',
        'prod_desc',
        'ser_desc',
        'prod_image',
        'ser_image',
        'created_at',
        'updated_at',
    ];
}
