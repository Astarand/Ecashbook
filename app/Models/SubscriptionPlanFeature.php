<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{
    protected $fillable = ['subscription_plans_id', 'name','feature_id', 'is_enabled'];

    public function plan()
    {
        return $this->belongsTo(subscription_plans::class);
    }
}
