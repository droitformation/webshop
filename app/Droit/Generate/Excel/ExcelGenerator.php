<?php
 namespace App\Droit\Generate\Excel;
 
 class ExcelGenerator{

     /*
     * Helper class for misc functions
     **/
     protected $helper;

     /*
      * Inscription Worker
      * Register or manipulate inscription lists
      **/
     protected $inscription_worker;

     /*
      * Default no sort
      **/
     protected $sort = false;

     /*
      * Default columns
      **/
     public $columns = [
         'civilite_title' ,'name', 'email', 'profession_title','company', 'telephone','mobile', 'adresse', 'cp', 'complement','npa', 'ville', 'canton_title','pays_title'
     ];

     /*
      * The current colloque
      * The options for the colloque
      * All the inscriptions for the colloque
      **/
     public $colloque;
     public $options;
     public $inscriptions;

     /*
     * Simple options checkboxes
     * Grouped options radio inputs
     **/
     public $parent_options;
     public $groupe_options;

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
         $names = ($details ? ['Numero','Montant','Date','Paye','Status','Titre','Quantité','Prix','Gratuit','Rabais'] : (['Numero','Montant','Date','Paye','Status'] + $names));

         \Excel::create('Export Commandes', function($excel) use ($prepared, $sum, $title, $names)
         {
             $excel->sheet('Export_Commandes', function($sheet) use ($prepared, $sum, $title, $names)
             {
                 // Set header
                 $sheet->row(1, [$title]);
                 $sheet->row(1,function($row) {
                     $row->setFontWeight('bold');
                     $row->setFontSize(14);
                 });

                 // Set Columns
                 $sheet->row(2,['']);
                 $sheet->row(3, $names);
                 $sheet->row(3,function($row) {
                     $row->setFontWeight('bold');
                     $row->setFontSize(12);
                 });

                 // Set Orders list
                 $sheet->rows($prepared);
                 $sheet->appendRow(['']);
                 $sheet->appendRow(['Total', $sum.' CHF']);
                 $sheet->row($sheet->getHighestRow(), function ($row)
                 {
                     $row->setFontWeight('bold');
                     $row->setFontSize(13);
                 });

             });
         })->export('xls');
     }

     public function prepareOrder($orders, $names, $details)
     {
         if(!$orders->isEmpty())
         {
             foreach($orders as $order)
             {
                 $info['Numero']  = $order->order_no;
                 $info['Montant'] = $order->price_cents.' CHF';
                 $info['Date']    = $order->created_at->formatLocalized('%d %B %Y');
                 $info['Paye']    = $order->payed_at ? $order->payed_at->formatLocalized('%d %B %Y') : '';
                 $info['Status']  = $order->status_code['status'];

                 // Only details of each order
                 // Group by product in orde, count qty
                 if($details)
                 {
                     $grouped = $order->products->groupBy('id');

                     foreach($grouped as $product)
                     {
                         $data['title']  = $product->first()->title;
                         $data['qty']    = $product->count();
                         $data['prix']   = $product->first()->price_cents;
                         $data['free']   = $product->first()->pivot->isFree ? 'Oui' : '';
                         $data['rabais'] = $product->first()->pivot->rabais ? ceil($product->first()->pivot->rabais).'%' : '';

                         $converted[] = $info + $data;
                     }
                 }
                 else
                 {
                     // Get columns requested from user adresse
                     if($order->user && !$order->user->adresses->isEmpty())
                     {
                         $columns = array_keys($names);

                         foreach($columns as $column)
                         {
                             $data[$column] = $order->user->adresses->first()->$column;
                         }

                         $converted[] = $info + $data;
                     }
                 }

             }

             return $converted;
         }
     }
 }