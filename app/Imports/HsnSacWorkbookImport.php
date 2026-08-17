<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HsnSacWorkbookImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new HsnSacImport('HSN'),
            1 => new HsnSacImport('SAC'),
        ];
    }
}