<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AdresseExport implements FromArray, WithHeadings, WithEvents
{
    use Exportable;

    protected $adresses;

    public function __construct($adresses)
    {
        $this->adresses = $adresses;
    }

    public function headings(): array {
        return [config('columns.names')];
    }

    public function array(): array
    {
        return $this->prepareAdresse($this->adresses);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AA1')->getFont()->setBold(true);
            },
        ];
    }

    public function prepareAdresse($adresses)
    {
        $columns = collect(array_keys(config('columns.names')));

        return $adresses->map(function ($adresse) use ($columns) {
            return $columns->map(function ($column) use ($adresse)
            {
                // withdraw substitute @publications-droit.ch emails from excel
                if($column == 'email' && (substr(strrchr($adresse->$column, "@"), 1) == 'publications-droit.ch')){
                    return '';
                }

                return trim(html_entity_decode($adresse->$column));
            });
        })->toArray();
    }
}