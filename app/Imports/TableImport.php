<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TableImport implements ToModel, WithHeadingRow
{
    private $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function model(array $row)
    {
        DB::table($this->tableName)->insert($row);
    }
}