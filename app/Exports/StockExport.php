<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromArray,WithHeadings
{
    use Exportable;

    protected $stocks;

    public function __construct($stocks)
    {
       $this->stocks = $stocks;
    }

    public function headings(): array {
        return ['Date','No','direction','Nbr.','État du stock'];
    }

    public function array(): array
    {
       return $this->prepareStock($this->stocks);
    }

    public function prepareStock($stocks)
    {
        $etat = 0;
        $results = [];

        foreach($stocks as $stock){
            if($stock->operator == '+'){
                $etat +=  $stock->amount;
            }
            else{
                $etat -= $stock->amount;
            }

            $results[] = [$stock->created_at->format('d/m/y'), $stock->motif, $stock->operator, $stock->amount, $etat];
        }

        return $results;
    }
}