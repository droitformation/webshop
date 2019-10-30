<?php namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmailImport implements ToArray, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param array $row
     *
     * @return Client|null
     */
    public function array(array $row)
    {
        if(!empty(array_filter($row))){
            return isset($row[0]) && !empty($row[0]) ? $row[0] : null;
        }
    }
}