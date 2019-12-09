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
                return array_values([
                    'civilite_title'   => trim($adresse->civilite_title),
                    'first_name'       => trim($adresse->first_name),
                    'last_name'        => trim($adresse->last_name),
                    'email'            => $this->facturation ? '' : trim($adresse->email),
                    'profession_title' => $this->facturation ? '' : trim($adresse->profession_title),
                    'company'          => trim($adresse->company),
                    'telephone'        => trim($adresse->telephone),
                    'mobile'           => trim($adresse->mobile),
                    'adresse'          => trim($adresse->adresse),
                    'cp_trim'          => trim($adresse->cp_trim),
                    'complement'       => trim($adresse->complement),
                    'npa'              => trim($adresse->npa),
                    'ville'            => trim($adresse->ville),
                    'canton_title'     => trim($adresse->canton_title),
                    'pays_title'       => trim($adresse->pays_title),
                    'exemplaires'      => trim($abo->exemplaires),
                    'numero'           => trim($abo->numero),
                ]);
            }

            return '';

        })->values()->toArray();
    }
}