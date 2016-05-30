<?php
 namespace App\Droit\Generate\Export;

 use Illuminate\Foundation\Bus\DispatchesJobs;

 class ExportAdresse{

     use DispatchesJobs;
     
     protected $store = null;

     public function setStore($store)
     {
         $this->store = $store;

         return $this;
     }

     /*
      * Export
      * */
     public function export($adresses)
     {
         $adresses = $this->prepareAdresse($adresses);

         $filename = "file.csv";

         // Open handle
         $handle   = fopen($filename, 'w+');

         // Add columns names
         fputcsv($handle, array_map("utf8_decode", array_values(config('columns.names'))), ";",'"');

         // Put all adresses in csv
         foreach($adresses as $row)
         {
             fputcsv($handle, $row->toArray() , ";",'"');
         }

         // Close handle
         fclose($handle);

         return $filename;
     }

     public function prepareAdresse($adresses)
     {
         $columns = collect(array_keys(config('columns.names')));

         return $adresses->map(function ($adresse) use ($columns) {
             return $columns->map(function ($column) use ($adresse){
                 return utf8_decode($adresse->$column);
             });
         });
     }

 }