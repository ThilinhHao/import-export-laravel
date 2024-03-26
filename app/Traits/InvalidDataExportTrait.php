<?php

namespace App\Traits;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

trait InvalidDataExportTrait
{
    public function exportInvalidData(array $invalidData, string $fileName)
    {
        return Excel::download(new class($invalidData) implements FromArray, WithHeadings {

            private $invalidData;

            public function __construct($invalidData)
            {
                $this->invalidData = $invalidData;
            }

            public function array(): array
            {
                return $this->invalidData;
            }

            public function headings(): array
            {
                return ['code', 'name', 'description', 'imported_at'];
            }
        }, $fileName)->deleteFileAfterSend();
    }
}
