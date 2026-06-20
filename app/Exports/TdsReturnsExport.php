<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class TdsReturnsExport implements FromArray
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return array_merge(
            [
                ['TDS Returns Report'], // First row same as PDF title
                [], // Empty row
                [
                    '#',
                    'Vendor/ Employee ID',
                    'Name',
                    'Pan',
                    'Section',
                    'Nature Of Payment',
                    'Gross Amount',
                    'TDS Rate (%)',
                    'TDS Deduction',
                    'Challan No',
                    'Payment Date',
                    'Return Quarter',
                    'Remarks'
                ]
            ],
            array_map(function ($row) {
                return array_values($row);
            }, $this->data)
        );
    }
}