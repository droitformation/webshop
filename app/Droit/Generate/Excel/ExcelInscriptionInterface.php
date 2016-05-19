<?php
 namespace App\Droit\Generate\Excel;
 
 interface ExcelInscriptionInterface{
     
     /*
      * column names
      * if we want to sort
      * */
     public function exportInscription($inscriptions, $colloque, $names, $sort = null);

     public function prepareInscription($inscriptions, $options , $names, $sort = null);

     public function userOptionsHtml($inscription);
 }