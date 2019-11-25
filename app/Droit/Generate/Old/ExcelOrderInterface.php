<?php
 namespace App\Droit\Generate\Excel;
 
 interface ExcelOrderInterface{
     
     public function exportOrder($orders, $names, $period = null, $details = null);

     public function prepareOrder($orders, $names, $details);
 }