<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\subscription_plans;
class Coupons extends Model
{
    protected $fillable = [
        'code', 'discount', 'type', 'valid_from',
        'valid_until', 'description', 'is_active'
    ];

    public function subscription_plans()
    {
        return $this->belongsToMany(subscription_plans::class, 'coupon_plan', 'coupon_id', 'plan_id')->withTimestamps();
    }


}
