<?php
 namespace App\Droit\Generate\Export;
 
 class ExportInscription{

     protected $columns = [];
     protected $sort = null;
     protected $dispatch = null;
     
     public $options = null;
     public $groupes = null;

     public function __construct()
     {
         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     public function setColumns($columns)
     {
         $this->columns = $columns;

         return $this;
     }

     public function setSort($sort)
     {
         $this->sort = $sort;

         return $this;
     }

     public function setDispatch($dispatch)
     {
         $this->dispatch = $dispatch;

         return $this;
     }

     /*
      * column names
      * if we want to sort
      * */
     public function export($inscriptions, $colloque = null, $occurences = null)
     {
         // Group by occurrences if any else just group on 0 to have a loop
         $grouped = $inscriptions->groupBy(function ($item, $key) use ($occurences){
             if($this->dispatch)
             {
                 if(!empty(array_filter($occurences)))
                 {
                     return $item->occurrences->whereIn('id',$occurences)->pluck('title')->all();
                 }

                 return $item->occurrences->pluck('title')->all();
             }
             return 0;
         });

         \Excel::create('Export inscriptions', function ($excel) use ($grouped,$colloque) {
             $excel->sheet('Export', function ($sheet) use ($grouped,$colloque) {

                 $sheet->setOrientation('landscape');

                 // start grouped loop and test if we need to display the name of the occureence
                 foreach ($grouped as $group => $inscriptions)
                 {
                     if(!empty($group))
                     {
                         $sheet->appendRow([$group]);
                         $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16)->setFontColor('#009cff');});
                         $sheet->mergeCells('A'.$sheet->getHighestRow().':H'.$sheet->getHighestRow())->appendRow(['']);
                     }

                     // Get options and grouped options
                     $this->options = $colloque->options->where('type', 'choix')->pluck('title', 'id')->toArray();
                     $this->groupes = $colloque->groupes->pluck('text', 'id')->toArray();

                     // Prepare the inscriptions with infos
                     $converted = $this->prepareInscription($inscriptions);

                     if ($this->sort && !empty($this->groupes))
                     {
                         $names['option_title'] = 'Choix';

                         foreach ($converted as $option_id => $option)
                         {
                             $sheet->appendRow([$this->options[$option_id]]);
                             $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16)->setFontColor('#595959');});
                             $sheet->mergeCells('A'.$sheet->getHighestRow().':H'.$sheet->getHighestRow())->appendRow(['']);

                             foreach ($option as $group_id => $group)
                             {
                                 $sheet->appendRow([isset($this->groupes[$group_id]) ? '- '.$this->groupes[$group_id] : 'aucun']);
                                 $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14)->setFontColor('#003e65'); })->appendRow(['']);

                                 $this->makeHeader($sheet);

                                 $sheet->rows($group)->appendRow(['']);
                             }
                         }
                     }
                     else
                     {
                         $this->makeHeader($sheet);

                         $sheet->rows($converted);
                     }

                     $sheet->appendRow(['']);

                 } // end grouped loop
             });

         })->export('xls');

     }

     protected function makeHeader($sheet)
     {
         $header = ['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $this->columns;

         $sheet->appendRow($header);
         $sheet->row($sheet->getHighestRow(), function ($row) {
             $row->setFontSize(13)->setFontWeight('bold');
         });
     }

     public function prepareInscription($inscriptions)
     {
         $converted = [];

         if(!$inscriptions->isEmpty())
         {
             foreach($inscriptions as $inscription)
             {
                 $user = $inscription->inscrit;
                 $data = [];

                 $data['Present']     = $inscription->present ? 'Oui' : '';
                 $data['Numéro']      = $inscription->inscription_no;
                 $data['Prix']        = $inscription->price_cents;
                 $data['Status']      = $inscription->status_name['status'];
                 $data['Date']        = $inscription->created_at->format('m/d/Y');
                 $data['Participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

                 // Adresse columns
                 if($user && !$user->adresses->isEmpty())
                 {
                     $names = collect($this->columns);
                     $data += $names->map(function ($item, $key) use ($user) {
                         return $user->adresses->first()->$key;
                     })->toArray();
                 }

                 // Options checkbox
                 if(!$inscription->user_options->isEmpty())
                 {
                     $data['checkbox'] = $inscription->user_options->load('option')->where('groupe_id', null)->implode('option.title', PHP_EOL);
                 }

                 // Do we need to sort, Sort by choix options
                 if ($this->sort && !empty($this->options))
                 {
                     $converted = $this->sortByOption($inscription, $data, $converted);
                 }
                 else
                 {
                     // String with the options
                     if (!$inscription->user_options->isEmpty())
                     {
                         $data['checkbox'] = $this->userOptionsHtml($inscription);
                     }

                     $converted[] = $data;
                 }
             }
         }

         return isset($converted) ? $converted : [];
     }

     public function sortByOption($inscription, $data, $result)
     {
         // Filter the options that exist
         $filter = $inscription->user_options->filter(function ($option, $key) {
             return in_array($option->option_id, array_keys($this->options)) ? true : false;
         })->toArray();

         // Sort each person in each options
         array_walk($filter, function (&$value,$key) use (&$result, $data) {
             $result[$value['option_id']][$value['groupe_id']][] = $data;
         });

         return $result;
     }

     public function userOptionsHtml($inscription)
     {
         return $inscription->user_options->map(function ($group, $key)
         {
             return $group->option->title.($group->groupe_id ? ':' : '').($group->groupe_id ? $group->option_groupe->text : '');
         })->implode(';');
     }
 }