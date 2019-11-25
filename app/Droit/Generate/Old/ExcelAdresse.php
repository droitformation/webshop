<?php
 namespace App\Droit\Generate\Excel;
 
 class ExcelAdresse{

     /*
      * Export
      * */
     /*public function exportAdresse($adresses, $store = false)
     {
         $export = \Excel::create('Export_Adresses_' . date('dmy'), function ($excel) use ($adresses){
             $excel->sheet('Export_Adresses', function ($sheet) use ($adresses){

                 $converted = $this->prepareAdresse($adresses);

                 $sheet->appendRow(array_values(config('columns.names')));
                 $sheet->row($sheet->getHighestRow(), function ($row) { $row->setFontWeight('bold')->setFontSize(14);});
                 $sheet->rows($converted->toArray());
             });
         });

         if ($store)
         {
             $export->store('xls', storage_path('excel/exports'));
         }
         else
         {
             $export->download('xls');
         }
     }

     public function prepareAdresse($adresses)
     {
         return $adresses->map(function ($adresse) {

             $columns = array_keys(config('columns.names'));
             $convert = new \App\Droit\Helper\Convert();

             foreach ($columns as $column) {
                 $convert->setAttribute($column, $adresse->$column);
             }

             return $convert;
         });
     }*/
 }