<?php
 namespace App\Droit\Generate\Excel;
 
 class ExcelInscription{

     public function __construct()
     {
         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     /*
      * column names
      * if we want to sort
      * */
     public function exportInscription($inscriptions, $colloque, $names, $sort = null)
     {
         \Excel::create('Export inscriptions', function ($excel) use ($inscriptions,$colloque, $sort, $names) {
             $excel->sheet('Export', function ($sheet) use ($inscriptions,$colloque, $sort, $names) {

                 $sheet->setOrientation('landscape');

                 $options   = $colloque->options->whereLoose('type', 'choix')->pluck('title', 'id')->toArray();
                 $groupes   = $colloque->groupes->pluck('text', 'id')->toArray();
                 $converted = $this->prepareInscription($inscriptions, $options , $names, $sort = null);

                 if ($sort && !empty($groupes))
                 {
                     $names['option_title'] = 'Choix';

                     foreach($converted as $option_id => $option)
                     {
                         $sheet->appendRow(['Options', $options[$option_id]]);
                         $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16);});
                         $sheet->appendRow(['']);

                         foreach ($option as $group_id => $group)
                         {
                             $sheet->appendRow(['']);
                             $sheet->appendRow(['Choix', $groupes[$group_id]]);
                             $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14);});

                             $sheet->appendRow(['']);

                             $sheet->appendRow(['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $names);
                             $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14);});
                             $sheet->rows($group);
                         }
                     }
                 }
                 else
                 {
                     $sheet->appendRow(['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $names);
                     $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14);});
                     $sheet->rows($converted);
                 }
             });

         })->export('xls');

     }

     public function prepareInscription($inscriptions, $options , $names, $sort = null)
     {
         $names = collect($names);

         $converted = [];

         if(!$inscriptions->isEmpty())
         {
             foreach($inscriptions as $inscription)
             {
                 $data = [];
                 $user = $inscription->inscrit;

                 $data['Present']     = $inscription->present ? 'Oui' : '';
                 $data['Numéro']      = $inscription->inscription_no;
                 $data['Prix']        = $inscription->price_cents;
                 $data['Status']      = $inscription->status_name['status'];
                 $data['Date']        = $inscription->created_at->format('m/d/Y');
                 $data['Participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

                 // Adresse columns
                 if($user && !$user->adresses->isEmpty())
                 {
                     $data += $names->map(function ($item, $key) use ($user) {
                         return $user->adresses->first()->$key;
                     })->toArray();
                 }

                 // Options checkbox
                 if(!$inscription->user_options->isEmpty())
                 {
                     $data['checkbox'] = $inscription->user_options->load('option')->whereLoose('groupe_id', null)->implode('option.title', PHP_EOL);
                 }

                 // Do we need to sort, Sort by choix options
                 if ($sort && !empty($groupes))
                 {
                     foreach($inscription->user_options as $option)
                     {
                         if (in_array($option->option_id, array_keys($options))){
                             $converted[$option->option_id][$option->groupe_id][] = $data;
                         }
                     }
                 }
                 else
                 {
                     // String with the options
                     if (!$inscription->user_options->isEmpty())
                     {
                         $html = $inscription->user_options->map(function ($group, $key)
                         {
                             return $group->option->title.($group->groupe_id ? ':' : '').($group->groupe_id ? $group->option_groupe->text : '');
                         })->implode(';');

                         $data['checkbox'] = $html;
                     }

                     $converted[] = $data;
                 }
             }
         }

         return $converted;
     }
 }