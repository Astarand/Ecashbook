<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetIntangibleDetail extends Model
{
    use HasFactory;

    protected $table = 'asset_intangible_details';

    protected $fillable = [
        'asset_id',
        'intangible_asset_name',
        'intangible_asset_category',
        'acquisition_date',
        'acquisition_cost',
        'amortisation_method',
        'accumulated_amortisation',
        'residual_value',
        'impairment_flag',
        'impairment_loss',
        'useful_life',
        'revaluation_adjustment',
        'royalty_agreement_id',
        'royalty_percentage',
        'royalty_due_date',
        'royalty_amount',
        'investment_type',
        'non_current_asset_type',
        'other_investment_type',
        'deferred_tax_asset_rate',
        'deferred_tax_asset_standard',
        'remarks',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'acquisition_cost' => 'float',
        'residual_value' => 'float',
        'impairment_loss' => 'float',
        'useful_life' => 'integer',
        'royalty_percentage' => 'float',
        'deferred_tax_asset_rate' => 'float',
    ];

    //  Relation back to main asset
    public function asset()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
