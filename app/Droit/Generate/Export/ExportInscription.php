<?php
 namespace App\Droit\Generate\Export;
 
 class ExportInscription{

     protected $columns = [];
     protected $sort = null;
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

     /*
      * column names
      * if we want to sort
      * */
     public function export($inscriptions, $colloque = null)
     {
         \Excel::create('Export inscriptions', function ($excel) use ($inscriptions,$colloque) {
             $excel->sheet('Export', function ($sheet) use ($inscriptions,$colloque) {

                 $sheet->setOrientation('landscape');

                 $this->options = $colloque->options->where('type', 'choix')->pluck('title', 'id')->toArray();
                 $this->groupes = $colloque->groupes->pluck('text', 'id')->toArray();

                 $converted = $this->prepareInscription($inscriptions);

                 if ($this->sort && !empty($this->groupes))
                 {
                     $names['option_title'] = 'Choix';

                     foreach($converted as $option_id => $option)
                     {
                         $sheet->appendRow(['Options', $this->options[$option_id]]);
                         $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16);});
                         $sheet->appendRow(['']);

                         foreach($option as $group_id => $group)
                         {
                             $sheet->appendRow(['']);
                             $sheet->appendRow(['Choix', $this->groupes[$group_id]]);
                             $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14);});

                             $sheet->appendRow(['']);
                             $sheet->appendRow(['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $this->columns);
                             $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14);});
                             $sheet->rows($group);
                         }
                         $sheet->appendRow(['']);
                     }
                 }
                 else
                 {
                     $sheet->appendRow(['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $this->columns);
                     $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14);});
                     $sheet->rows($converted);
                 }
             });

         })->export('xls');

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