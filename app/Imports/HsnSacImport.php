<?php

namespace App\Imports;

use App\Models\Hsn_sac_masters;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HsnSacImport implements ToCollection, WithHeadingRow
{
    protected string $codeType;

    public int $inserted = 0;
    public int $updated = 0;
    public int $skipped = 0;

    public function __construct(string $codeType)
    {
        $this->codeType = strtoupper($codeType);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            /*
             * HSN Excel:
             * HSN Code
             * HSN Description
             *
             * SAC Excel:
             * SAC Code
             * SAC Description
             */

            $codeKey = $this->codeType === 'HSN'
                ? 'hsn_code'
                : 'sac_code';

            $descriptionKey = $this->codeType === 'HSN'
                ? 'hsn_description'
                : 'sac_description';

            $code = isset($row[$codeKey])
                ? trim((string) $row[$codeKey])
                : '';

            $description = isset($row[$descriptionKey])
                ? trim((string) $row[$descriptionKey])
                : '';

            $gstRate = isset($row['applicable_gst_rate'])
                ? trim((string) $row['applicable_gst_rate'])
                : '';

            $apply_cond = isset($row['condition_when_this_rate_applies'])
                ? trim((string) $row['condition_when_this_rate_applies'])
                : '';

            if ($code === '') {
                $this->skipped++;
                continue;
            }

            /*
             * Convert:
             * 5%
             * 12%
             * 18%
             * 0%
             *
             * to numeric values.
             */
            $gstRate = str_replace('%', '', $gstRate);
            $gstRate = is_numeric($gstRate)
                ? (float) $gstRate
                : 0;

            /*
             * If same code + GST rate + condition exists,
             * update it.
             *
             * Otherwise create a new record.
             */
            $existing = Hsn_sac_masters::where('code_type', $this->codeType)
                ->where('code', $code)
                ->where('gst_rate', $gstRate)
                ->where('apply_cond', $apply_cond)
                ->first();

            if ($existing) {

                $existing->update([
                    'description' => $description,
                    'status' => 1,
                ]);

                $this->updated++;

            } else {

                Hsn_sac_masters::create([
                    'code_type' => $this->codeType,
                    'code' => $code,
                    'description' => $description,
                    'gst_rate' => $gstRate,
                    'apply_cond' => $apply_cond,
                    'status' => 1,
                ]);

                $this->inserted++;
            }
        }
    }
}