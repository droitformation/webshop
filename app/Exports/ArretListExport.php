<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArretListExport implements FromArray,WithHeadings
{
    use Exportable;

    protected $categorie;

    public function __construct($categorie)
    {
       $this->categorie = $categorie;
    }

    public function headings(): array {
        return ['Arrêts dans '.$this->categorie->title];
    }

    public function array(): array
    {
        return $this->categorie->arrets->map(function ($item) {
            return [$item->reference];
        })->toArray();
    }
}