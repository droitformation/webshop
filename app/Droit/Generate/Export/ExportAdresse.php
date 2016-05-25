<?php
 namespace App\Droit\Generate\Export;

 use Illuminate\Foundation\Bus\DispatchesJobs;
 use App\Jobs\ExportBatchAdresse;
 use App\Jobs\ExportAdresses;
 use PHPExcel;

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
         // $converted = $this->prepareAdresse($adresses);
         $converted = $adresses->chunk(100);

         $path = storage_path('excel/exports/Export_Adresses_'.date('dmy').'.xls');

         // Create file who will hold the data
         $export = \Excel::create('Export_Adresses_'.date('dmy'), function ($excel) use ($adresses){
             $excel->sheet('Export_Adresses', function ($sheet) use ($adresses){});
         })->store('xls', storage_path('excel/exports'));

         // add rows in batches
         $converted->each(function ($adresses, $key) use ($path) {
             $this->dispatch(new ExportBatchAdresse($path,$adresses));
         });

         // Format first row with header
         $this->dispatch(new ExportAdresses($path));

         if(!$this->store)
         {
            // \Excel::load($path, function($reader) {})->download('xls');
         }
     }

     public function prepareAdresse($adresses)
     {
         $columns = collect(array_keys(config('columns.names')));

         return $adresses->map(function ($adresse) use ($columns) {
             return $columns->map(function ($column) use ($adresse){
                 return $adresse->$column;
             });
         });
     }

     public function merge()
     {
         $filenames = array(storage_path('exports/one.xlsx'), storage_path('exports/two.xlsx'));

         $bigExcel = new PHPExcel();
         $bigExcel->removeSheetByIndex(0);

         $reader = \PHPExcel_IOFactory::createReader('Excel5');

         foreach ($filenames as $filename) {
             $excel = $reader->load($filename);

             foreach ($excel->getAllSheets() as $sheet) {
                 $bigExcel->addExternalSheet($sheet);
             }

             foreach ($excel->getNamedRanges() as $namedRange) {
                 $bigExcel->addNamedRange($namedRange);
             }
         }

         $writer = \PHPExcel_IOFactory::createWriter($bigExcel, 'Excel5');

         $file_creation_date = date("Y-m-d");

         // name of file, which needs to be attached during email sending
         $saving_name = "Report_Name" . $file_creation_date . '.xls';


         // save file at some random location
         $writer->save(storage_path('exports/'.$saving_name));
     }
 }