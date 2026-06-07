<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTangibleDetail extends Model
{
    use HasFactory;

    protected $table = 'asset_tangible_details';

    protected $fillable = [
        'asset_id',
        'tangible_category',
        'location',
        'department',
        'depreciation_method',
        'useful_life',
        'salvage_value',
        'warranty_information',
        'maintenance_schedule',
        'insurance_details',
        'vendor_name',
        'purchase_by',
        'approve_by',
        'approve_date',
        'assetPurchaseCost',
        'gstDate',
        'gstcomponent',
        'valuationMethod',
        'lendername',
        'royaltyAgreementID',
        'royaltyPercentage',
        'tds_applicable',
        'tds_percentage',
        'tds_amount',
        'tds_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'useful_life' => 'integer',
        'salvage_value' => 'float',
        'assetPurchaseCost' => 'float',
        'gstDate' => 'date',
        'approve_date' => 'date',
    ];

    //  Relation back to main asset
    public function asset()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
