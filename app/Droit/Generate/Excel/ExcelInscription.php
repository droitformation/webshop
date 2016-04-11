<?php
 namespace App\Droit\Generate\Excel;
 
 class ExcelInscription{

     /*
     * Helper class for misc functions
     **/
     protected $helper;

     public function __construct()
     {
         $this->helper = new \App\Droit\Helper\Helper();

         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     public function getInscriptions($colloque)
     {
         $options  = $colloque->options->whereLoose('type', 'choix')->pluck('title', 'id')->toArray();
         $groupes  = $colloque->groupes->pluck('text', 'id')->toArray();

         $inscriptions = $this->inscription->getByColloque($colloque->id);

     }

     /*
      * column names
      * if we want to sort
      * */
     public function exportInscription($colloque_id, $names, $sort = null)
     {

     }

     public function prepareInscription($inscriptions, $options , $names, $sort = null)
     {
         if (!$inscriptions->isEmpty())
         {
             foreach ($inscriptions as $inscription)
             {

                 $user = $inscription->inscrit;

                 $data['Present']     = $inscription->present ? 'Oui' : '';
                 $data['Numéro']      = $inscription->inscription_no;
                 $data['Prix']        = $inscription->price_cents;
                 $data['Status']      = $inscription->status_name['status'];
                 $data['Date']        = $inscription->created_at->format('m/d/Y');
                 $data['Participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

                 // Adresse
                 if ($user && !$user->adresses->isEmpty())
                 {
                     foreach($names as $column => $title) {
                         $data[$title] = $user->adresses->first()->$column;
                     }
                 }

                 if (!$inscription->user_options->isEmpty())
                 {
                     $data['checkbox'] = $inscription->user_options->load('option')->whereLoose('groupe_id', null)->implode('option.title', PHP_EOL);
                 }

                 if ($sort && !empty($groupes))
                 {
                     foreach($inscription->user_options as $option)
                     {
                         if (in_array($option->option_id, array_keys($options)))
                         {
                             $converted[$option->option_id][$option->groupe_id][] = $data;
                         }
                     }
                 }
                 else
                 {
                     if (!$inscription->user_options->isEmpty()) {

                         $html = $inscription->user_options->map(function ($group, $key)
                         {
                             return $group->map(function ($item, $key) {
                                 $html  = $item->option->title;
                                 $html .= $item->groupe_id ? ':' : '';
                                 $html .= ($item->groupe_id ? $item->option_groupe->text : '');

                                 return $html;
                             });
                         })->flatten()->implode(';');

                         $data['checkbox'] = $html;
                     }

                     $converted[] = $data;
                 }

             }
         }
     }
 }