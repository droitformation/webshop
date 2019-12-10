<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AboExport implements FromArray, WithHeadings, WithEvents
{
    use Exportable;

    protected $abonnes;
    protected $columns;
    protected $facturation;

    public function __construct($abonnes,$columns,$facturation)
    {
        $this->abonnes = $abonnes;
        $this->columns = $columns;
        $this->facturation = $facturation;
    }

    public function headings(): array {
        return [$this->columns];
    }

    public function array(): array
    {
        return $this->prepareAdresse();
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

    public function prepareAdresse()
    {
        return $this->abonnes->map(function ($abo){

            $adresse = $this->facturation ? $abo->user_facturation : $abo->user_adresse;

            if(isset($adresse)){
                return [
                    trim($adresse->civilite_title),
                    trim($adresse->first_name),
                    trim($adresse->last_name),
                    $this->facturation ? '' : trim($adresse->email),
                    $this->facturation ? '' : trim($adresse->profession_title),
                    trim($adresse->company),
                    trim($adresse->telephone),
                    trim($adresse->mobile),
                    trim($adresse->adresse),
                    trim($adresse->cp_trim),
                    trim($adresse->complement),
                    trim($adresse->npa),
                    trim($adresse->ville),
                    trim($adresse->canton_title),
                    trim($adresse->pays_title),
                    trim($abo->exemplaires),
                    trim($abo->numero),
                ];
            }

            return [];

        })->reject(function ($row, $key) {
            return empty($row);
        })->toArray();
    }
}