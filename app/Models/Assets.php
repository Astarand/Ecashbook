<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assets extends Model
{
    use HasFactory;

    protected $fillable = [

		// ================= BASIC =================
		'asset_id',
		'added_by',
		'propId',
		'date',
		'asset_name',
		'assetType',
		'currentAssetType',
		'nonCurrentAssetType',

		// ================= FILE / AUDIT =================
		'file1',
		'file2',
		'purchaseByAudit',
		'purchaseDateAudit',
		'approveByAudit',
		'approveDateAudit',

		// ================= FIXED ASSET =================
		'asset_category',
		'asset_code',
		'location',
		'department',

		'vendor_id',
		'invoice_no',
		'invoice_date',
		'purchase_date',
		'invoice_value',
		'pay_status',
		'advance_amt',
		'payable_amt',
		'adjusted_amt',

		'capitalization_date',
		'put_to_use_date',
		'asset_status',

		'depreciation_start_date',
		'depreciation_frequency',
		'useful_life_years',
		'depreciation_method',
		'depreciation_rate',
		'residual_value',
		'depreciation_value',
		'net_book_value',

		// ================= CWIP =================
		'project_name',
		'project_code',
		'cwip_asset_type',

		'expense_type',
		'cwip_vendor_id',
		'cwip_invoice_no',
		'cwip_expense_date',
		'cwip_amount',
		'cwip_pay_status',
		'cwip_advance_amt',
		'cwip_payable_amt',
		'cwip_adjusted_amt',

		'completion_percentage',
		'capitalization_status',
		'work_order_ref',

		// ================= TAX =================
		'tds_applicable',
		'tds_percent',
		'tds_id',
		'tds_amt',

		'gst_applicable',
		'hsn_sac_code',
		'gst_rate',
		'gst_amt',
		'gst_trans',
		'gst_allocation',

		// ================= TIMESTAMP =================
		'created_at',
		'updated_at',
	];
}
