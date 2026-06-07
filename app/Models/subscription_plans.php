<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubscriptionPlanFeature;
use App\Models\Coupons;
class subscription_plans extends Model
{
    protected $fillable = [
        'userId', 'utype', 'title', 'monthly_price', 'yearly_price', 'ca_percentage', 'icon', 'status'
    ];

    public function features()
    {
        return $this->hasMany(SubscriptionPlanFeature::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupons::class, 'coupon_plan', 'plan_id', 'coupon_id');
    }
}