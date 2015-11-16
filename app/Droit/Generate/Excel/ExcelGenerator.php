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
         $this->inscription_worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorker');
         $this->helper             = new \App\Droit\Helper\Helper();
         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     public function init($colloque, $options = []){

         $this->setColloque($colloque);

         if(isset($options['order']) && $options['order'])
         {
             $this->setSort($options['order']);
         }

         if(!$this->inscriptions->isEmpty())
         {
             $this->sort();
             return $this->toRow($this->inscriptions);
         }

         return false;
     }

     /*
      * Set the current colloque and set options and inscriptions
      **/
     public function setColloque($colloque)
     {
         $colloque->options->load('groupe');

         $this->colloque     = $colloque;
         $this->options      = $this->colloque->options;
         $this->inscriptions = $this->colloque->inscriptions;

         return $this;
     }

     /*
      * Override default sort type
      **/
     public function setSort($sort)
     {
         $this->sort = $sort;

         return $this;
     }

     /*
     * Override default columns
     **/
     public function setColumns($columns)
     {
         $this->columns = $columns;

         return $this;
     }

     /*
      * Prepare one row for inscription with all infos
      **/
     public function row($inscription)
     {
         $row  = $this->user($inscription);
         $row['participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

         $sort = array_merge(array_slice($this->columns, 0, 2), ['participant'], array_slice($this->columns, 2));
         $row  = $this->helper->sortArrayByArray($row,$sort);

         $row['numero'] = $inscription->inscription_no;
         $row['date']   = $inscription->created_at->format('d/m/Y');

         $filtered = $inscription->options->filter(function ($item) {  return $item->type == 'checkbox';  });
         $row['options'] = implode('\n',$filtered->lists('title')->all());

         array_walk($row, array($this, 'makeRow'));

         return $row;
     }

     public function toRow($inscriptions)
     {
         $data = [];
         
         foreach($inscriptions as $key => $inscription)
         {
             if(is_array($inscription))
             {
                 foreach($inscription as $option => $value)
                 {
                     if(is_array($value))
                     {
                         foreach ($value as $final)
                         {
                             $data[$key][$option][] = $this->row($final);
                         }
                     }
                     else
                     {
                         $data[$key][] = $this->row($value);
                     }
                 }
             }
             else
             {
                 $data[] = $this->row($inscription);
             }
         }

         return $data;
     }

     /*
     * Get user infos
     **/
     public function user($model)
     {
         foreach($this->columns as $column)
         {
             $user[$column] = (isset($model->adresse_facturation->$column) ? trim($model->adresse_facturation->$column) : '');
         }

         return $user;
     }

     /*
     * Each table row
     **/
     public function makeRow(&$item,$key)
     {
         $item = '<td>'.$item.'</td>';
     }

     /*
      * Dispatch inscription by sort
      **/
     public function sort()
     {
         if($this->sort == 'checkbox')
         {
             $options = $this->getMainOptions();
         }

         if($this->sort == 'choix')
         {
             $options = $this->getGroupeOptions();
         }

         if(!empty($options))
         {
            $this->dispatch($this->inscriptions,$options);
            $this->inscriptions = $this->dispatch;
         }

         return $this;
     }

     /*
      * Get simple options
      **/
     public function getMainOptions()
     {
         if(!$this->options->isEmpty())
         {
             if($this->sort == 'checkbox')
             {
                 $options = $this->options->where('type','checkbox');
             }
             else
             {
                 $options = $this->options;
             }

             return $options->lists('title','id')->all();
         }

         return [];
     }

     /*
     * Get grouped options
     **/
     public function getGroupeOptions()
     {
         if(!$this->options->isEmpty())
         {
             foreach($this->options as $option)
             {
                 if(isset($option->groupe) && !$option->groupe->isEmpty())
                 {
                     foreach($option->groupe as $groupe)
                     {
                         $groupes[$option->id][$groupe->id] = $groupe->text;
                     }
                 }
             }
         }

         return (isset($groupes) ? $groupes : []);
     }

     public function dispatch($inscriptions, $options)
     {
         foreach($inscriptions as $inscription)
         {
             $groupe_choix = $inscription->user_options->groupBy('option_id');

             foreach($options as $option_id => $option)
             {
                 if(isset($groupe_choix[$option_id]))
                 {
                     $current = $groupe_choix[$option_id];

                     $this->optionDispatch($option,$option_id,$current,$inscription);
                 }
                 else
                 {
                     $this->dispatch[0][] = $inscription;
                 }
             }
         }
     }

     public function optionDispatch($option,$option_id,$current,$inscription)
     {
         if(is_array($option))
         {
             $key = key($option);

             if($current->contains('groupe_id', $key))
             {
                 $this->dispatch[$option_id][$key][] = $inscription;
             }
         }
         else
         {
             $this->dispatch[$option_id][] = $inscription;
         }
     }

     /*
      * Order functions
      * */

     /* Each user row */
     public function toRowUser($user)
     {
         $user = $this->user($user);
         array_walk($user, array($this, 'makeRow'));

         return implode('',$user);
     }

 }