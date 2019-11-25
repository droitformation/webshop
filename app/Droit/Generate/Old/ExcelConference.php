<?php
 namespace App\Droit\Generate\Excel;

 /*
  * Used for export dejeuner academiques participant list
  * */

 class ExcelConference{

     protected $title;
     protected $date;

     public function __construct($title,$date)
     {
         $this->title = $title;
         $this->date  = frontendDate($date);
     }

     /*
      * Export
      * */
     public function export($academiques, $store = null)
     {
         $export = \Excel::create('inscriptions_dejeuner_academiques_' . date('dmy'), function ($excel) use ($academiques){
             $excel->sheet('inscriptions', function ($sheet) use ($academiques){

                 $sheet->appendRow([$this->title]);
                 $sheet->row($sheet->getHighestRow(), function ($row) { $row->setFontWeight('bold')->setFontSize(17);});

                 $sheet->appendRow([$this->date]);
                 $sheet->row($sheet->getHighestRow(), function ($row) { $row->setFontWeight('bold')->setFontSize(14);});

                 $sheet->rows([[' ']]);

                 $sheet->appendRow(['Prénom','Nom','Email']);
                 $sheet->row($sheet->getHighestRow(), function ($row) { $row->setFontWeight('bold')->setFontSize(12);});
                 $sheet->rows($academiques);
             });
         });

         if ($store) {
             return $export->store('xls', storage_path('excel/conferences'),true);
         }

         $export->download('xls');
     }
 }