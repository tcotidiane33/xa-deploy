<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class TableTemplateExport implements FromArray
{
    private $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function array(): array
    {
        return [$this->columns];
    }
}

