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
         $adresses = $this->prepareAdresse($adresses);
         
         return $adresses->toArray();
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

 }