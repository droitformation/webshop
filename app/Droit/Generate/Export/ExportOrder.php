<?php
 namespace App\Droit\Generate\Export;
 
 class ExportOrder{
     
     protected $helper;
     protected $details;
     protected $period;
     protected $free;
     protected $columns = [];

     public $simple_cols = ['Numero','Montant de la commande','Date','Paye','Status'];
     public $extend_cols = ['Numero','Montant de la commande','Date','Payé','Status','Titre','Quantité','Prix','Prix spécial','Gratuit','Rabais'];

     public function __construct()
     {
         $this->helper = new \App\Droit\Helper\Helper();

         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     public function setPeriod($period)
     {
         $this->period = $period;

         return $this;
     }

     public function setColumns($columns)
     {
         $this->columns = $columns;

         return $this;
     }

     public function setDetail($details)
     {
         $this->details = $details;

         return $this;
     }

     public function setFree($free)
     {
         $this->free = $free;

         return $this;
     }

     /*
      * column names
      * period dates
      * if we want all details
      * */
     public function export($orders)
     {
         // Title and sum of all orders
         $title = ($this->period ? 'Commandes du '.$this->helper->formatTwoDates($this->period['start'],$this->period['end']) : 'Commandes');
         $sum   = $orders->sum('price_cents');

         // prepare orders
         $prepared = $this->prepareOrder($orders, $this->columns, $this->details);

         // Columns add columns requested if we want user and not details
         $names = ($this->details ? $this->extend_cols : ($this->simple_cols + $this->columns));

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

     public function prepareOrder($orders, $names, $details = null)
     {
         if(!$orders->isEmpty())
         {
             $converted = [];

             foreach($orders as $order)
             {
                 // we want only free books and wee have some? If wee don't skip the iteration for thsi order
                 if($this->free && !$this->hasFreeProducts($order)){
                     continue;
                 }

                 $info['Numero']  = $order->order_no;
                 $info['Montant'] = $order->price_cents.' CHF';
                 $info['Date']    = $order->created_at->formatLocalized('%d %B %Y');
                 $info['Paye']    = $order->payed_at ? $order->payed_at->formatLocalized('%d %B %Y') : '';
                 $info['Status']  = $order->status_code['status'];

                 // Only details of each order and group by product in orde, count qty
                 if($details)
                 {
                     $products = $this->free ? $this->hasFreeProducts($order) : $order->products;
                     $grouped  = $products->groupBy('id');

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

     public function hasFreeProducts($order)
     {
         $products = $order->products->reject(function ($product, $key) {
             return !$product->pivot->isFree;
         });

         return !$products->isEmpty() ? $products : false;
     }
 }