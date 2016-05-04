<?php
 namespace App\Droit\Generate\Excel;
 
 class ExcelOrder{

     /*
     * Helper class for misc functions
     **/
     protected $helper;

     public function __construct()
     {
         $this->helper = new \App\Droit\Helper\Helper();

         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     /*
      * column names
      * period dates
      * if we want all details
      * */
     public function exportOrder($orders, $names, $period = null, $details = null)
     {
         // Title and sum of all orders
         $title = 'Commandes du '.$this->helper->formatTwoDates($period['start'],$period['end']);
         $sum   = $orders->sum('price_cents');

         // prepare orders
         $prepared = $this->prepareOrder($orders, $names, $details);

         // Columns add columns requested if we want user and not details
         $names = ($details ? ['Numero','Montant','Date','Payé','Status','Titre','Quantité','Prix','Prix spécial','Gratuit','Rabais'] : (['Numero','Montant','Date','Paye','Status'] + $names));

         \Excel::create('Export Commandes', function($excel) use ($prepared, $sum, $title, $names)
         {
             $excel->sheet('Export_Commandes', function($sheet) use ($prepared, $sum, $title, $names)
             {
                 // Set header
                 $sheet->row(1,[$title]);
                 $sheet->row(1,function($row) {$row->setFontWeight('bold')->setFontSize(14);});

                 // Set Columns
                 $sheet->row(2,['']);
                 $sheet->row(3,$names);
                 $sheet->row(3,function($row) {$row->setFontWeight('bold')->setFontSize(12);});

                 // Set Orders list and total
                 $sheet->rows($prepared);
                 $sheet->rows([[''],['Total', $sum.' CHF']]);

                 $sheet->row($sheet->getHighestRow(), function ($row){ $row->setFontWeight('bold')->setFontSize(13); });

             });
         })->export('xls');
     }

     public function prepareOrder($orders, $names, $details)
     {
         if(!$orders->isEmpty())
         {
             $converted = [];

             foreach($orders as $order)
             {
                 $info['Numero']  = $order->order_no;
                 $info['Montant'] = $order->price_cents.' CHF';
                 $info['Date']    = $order->created_at->formatLocalized('%d %B %Y');
                 $info['Paye']    = $order->payed_at ? $order->payed_at->formatLocalized('%d %B %Y') : '';
                 $info['Status']  = $order->status_code['status'];

                 // Only details of each order and group by product in orde, count qty
                 if($details)
                 {
                     $grouped = $order->products->groupBy('id');

                     foreach($grouped as $product)
                     {
                         $data['title']   = $product->first()->title;
                         $data['qty']     = $product->count();
                         $data['prix']    = $product->first()->price_normal.' CHF';
                         $data['special'] = $product->first()->price_special ? $product->first()->price_special.' CHF' : '';
                         $data['free']    = $product->first()->pivot->isFree ? 'Oui' : '';
                         $data['rabais']  = $product->first()->pivot->rabais ? ceil($product->first()->pivot->rabais).'%' : '';

                         $converted[] = $info + $data;
                     }
                 }
                 else
                 {
                     if($order->order_adresse)
                     {
                         $columns = array_keys($names);
                         // Get columns requested from user adresse
                         foreach($columns as $column)
                         {
                             $data[$column] = $order->order_adresse->$column;
                         }

                         $converted[] = $info + $data;
                     }
                 }
             }

             return $converted;
         }
     }
 }