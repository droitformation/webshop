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
         fputcsv($handle, array_map(function($text) {
             return iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
         }, array_values(config('columns.names'))), ";",'"');

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
             return $columns->map(function ($column) use ($adresse)
             {
                 return iconv(mb_detect_encoding($adresse->$column, mb_detect_order(), true), "UTF-8", $adresse->$column);
             });
         });
     }

 }