<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets_new extends Model
{
    use HasFactory;

    protected $table = 'assets_new';

    protected $fillable = [
        'asset_id',
        'added_by',
        'date',
        'asset_name',
        'assetType',
        'purchase_date',
        'purchase_cost',
        'currentAssetType',
        'current_investmentsType',
        'maturitydate',
        'market_value',
        'purchase_from',
       // 'vendor_name',
        'invoice_number',
        'asset_image',
        'documentation',
        'attachment',
        'remarks',
        'assetStatus',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'date' => 'date',
        'purchase_cost' => 'float',
    ];

    //  Relationships
    public function tangibleDetail()
    {
        return $this->hasOne(AssetTangibleDetail::class, 'asset_id');
    }

    public function intangibleDetail()
    {
        return $this->hasOne(AssetIntangibleDetail::class, 'asset_id');
    }

    //  Example helper method
    public function getTypeLabelAttribute()
    {
        return ucfirst($this->assetType);
    }
}
