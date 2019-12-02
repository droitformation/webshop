<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DejeunerExport implements FromArray, WithHeadings, WithEvents
{
    use Exportable;

    protected $participants;
    protected $dejeuner;

    public function __construct($participants,$dejeuner)
    {

        $this->participants = collect($participants)->map(function ($person) {
            return [
                'first_name' => $person['first_name'],
                'last_name'  => $person['last_name'],
                'email'      => $person['email'],
                'choices'    => isset($person['choices']) ? implode(', ',$person['choices']) : ''
            ];
        })->toArray();

        $this->dejeuner = $dejeuner;
    }

    public function headings(): array {
        return [$this->dejeuner['title'],'',''];
    }

    public function array(): array
    {
        $date = frontendDate($this->dejeuner['date']);

        return [[$date,'',''],['','',''],['Prénom','Nom','Email','Choix']] + $this->participants;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:C1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(17);
                $cellRange1 = 'A2:C2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange1)->getFont()->setSize(14);
                $cellRange2 = 'A4:D4'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setBold(true);
            },
        ];
    }
}