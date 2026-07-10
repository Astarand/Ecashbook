<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentVoucherExport implements FromCollection, WithHeadings
{
    protected $request;
    protected $userId;

    public function __construct($request, $userId)
    {
        $this->request = $request;
        $this->userId  = $userId;
    }

    public function collection()
    {
        $request = $this->request;

        $query = DB::table('payment_vouchers');

        $query->where('added_by', $this->userId);

        if ($request->prop_Id) {
            $query->where('propId', $request->prop_Id);
        }

        if ($request->from_date) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->voucher_no) {
            $query->where('voucher_no', 'like', '%' . $request->voucher_no . '%');
        }

        if ($request->party_name) {
            $query->where('party_name', 'like', '%' . $request->party_name . '%');
        }

        if ($request->voucher_type) {
            $query->where('voucher_type', $request->voucher_type);
        }

        if ($request->payment_mode) {
            $query->where('payment_mode', $request->payment_mode);
        }

        if ($request->bank_id) {
            $query->where('bank_id', $request->bank_id);
        }

        if ($request->is_paid != '') {
            $query->where('is_paid', $request->is_paid);
        }

        if ($request->party_type) {
            $query->where('party_type', $request->party_type);
        }

        if ($request->record_type) {
            $query->where('record_type', $request->record_type);
        }

        return $query
            ->orderBy('date', 'desc')
            ->get([
                'date',
                'voucher_no',
                'voucher_type',
                'source',
                'party_type',
                'party_name',
                'amount',
                'credit_debit',
                'payment_mode',
                DB::raw("CASE WHEN is_paid=1 THEN 'Paid' ELSE 'Outstanding' END as payment_status"),
                'record_type'
            ]);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Voucher No',
            'Voucher Type',
            'Source',
            'Party Type',
            'Party Name',
            'Amount',
            'CR/DR',
            'Payment Mode',
            'Payment Status',
            'Record Type'
        ];
    }
}