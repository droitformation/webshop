<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatusEmailExport implements FromCollection,WithHeadings
{
    use Exportable;

    protected $rows;

    public function __construct($rows)
    {
       $this->rows = $rows;
    }

    public function headings(): array {
        return ['Email','Status'];
    }

    public function collection()
    {
       return $this->rows;
    }
}