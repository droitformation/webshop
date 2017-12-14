<?php
 namespace App\Droit\Generate\Export;

 use Illuminate\Foundation\Bus\DispatchesJobs;
 use Box\Spout\Writer\WriterFactory;
 use Box\Spout\Writer\Style\StyleBuilder;
 use Box\Spout\Common\Type;

 class ExportAdresse{

     use DispatchesJobs;
     
     protected $store = null;

     public function setStore($store)
     {
         $this->store = $store;

         return $this;
     }

     public function export($adresses)
     {
         $adresses = $this->prepareAdresse($adresses);

         $defaultStyle = (new StyleBuilder())->setFontName('Arial')->setFontSize(11)->build();

         $writer = WriterFactory::create(Type::XLSX); // for XLSX files

         $filename = storage_path("excel/file.xlsx");

         $writer->openToBrowser($filename); // write data to a file or to a PHP stream
         //$writer->openToBrowser($fileName); // stream data directly to the browser

         if(!$adresses->isEmpty()){
             $writer->addRowsWithStyle($adresses->toArray(),$defaultStyle); // add multiple rows at a time
         }

         $writer->close();exit;
     }

     /*
      * Export
      * */
    /* public function export($adresses)
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
     }*/

     public function prepareAdresse($adresses)
     {
         $columns = collect(array_keys(config('columns.names')));

         return $adresses->map(function ($adresse) use ($columns) {
             return $columns->map(function ($column) use ($adresse)
             {
                 return trim($adresse->$column);
                 //return iconv(mb_detect_encoding($adresse->$column, mb_detect_order(), true), "UTF-8", $adresse->$column);
             });
         });
     }

 }