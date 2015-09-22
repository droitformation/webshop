<?php
 namespace App\Droit\Generate\Excel;
 
 class ExcelGenerator{

     protected $inscription_worker;

     public $colloque;
     public $options;
     public $inscriptions;

     public $parent_options;
     public $groupe_options;

     public function __construct()
     {
         $this->inscription_worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorker');

         setlocale(LC_ALL, 'fr_FR.UTF-8');
     }

     public function setColloque($colloque)
     {
         $colloque->options->load('groupe');

         $this->colloque     = $colloque;
         $this->options      = $this->colloque->options;
         $this->inscriptions = $this->colloque->inscriptions;

         return $this;
     }

     public function getMainOptions()
     {
         if(!$this->options->isEmpty())
         {
             return $this->options->lists('title','id')->all();
         }

         return [];
     }

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
                         $groupes[$groupe->id] = $groupe->text;
                     }
                 }
             }
         }

         return (isset($groupes) ? $groupes : []);
     }
 }