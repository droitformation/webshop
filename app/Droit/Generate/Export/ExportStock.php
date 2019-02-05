<?php
 namespace App\Droit\Generate\Export;

 use Illuminate\Foundation\Bus\DispatchesJobs;

 class ExportStock{

     use DispatchesJobs;

     public function export($stocks)
     {
         $stocks = $this->prepareStock($stocks);

         \Excel::create('Export historique stock', function($excel) use ($stocks) {
             $excel->sheet('Export_historique_stock', function($sheet) use ($stocks) {
                 $sheet->setOrientation('landscape');
                 $sheet->appendRow(['Date','No','direction','Nbr.','État du stock']);
                 $sheet->rows($stocks);
             });
         })->download('xlsx');
     }

     public function prepareStock($stocks)
     {
         $helper = new \App\Droit\Helper\Helper();
         $etat = 0;
         $results = [];

         foreach($stocks as $stock){

             if($stock->operator == '+'){
                 $etat +=  $stock->amount;
             }
             else{
                 $etat -= $stock->amount;
             }

             $stock_calc =  [
                 $stock->created_at->format('d/m/y'),
                 $stock->motif,
                 $stock->operator,
                 $stock->amount,
                 $etat
             ];

             $results[] = $stock_calc;
         }

         return $results;
     }

 }