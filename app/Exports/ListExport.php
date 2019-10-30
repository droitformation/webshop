<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ListExport implements FromCollection,WithHeadings
{
    use Exportable;

    protected $list;

    public function __construct($list)
    {
       $this->list = $list;
    }

    public function headings(): array {
        return ['Email'];
    }

    public function collection()
    {
       return $this->list->emails->map(function ($item) {
            return [$item->email];
        });
    }
}