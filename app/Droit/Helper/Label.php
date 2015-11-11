<?php

namespace App\Droit\Helper;

 class Label{

     protected $profession;
     protected $canton;
     protected $pays;
     protected $specialisation;
     protected $member;

     /**
      * Construct a new SentryUser Object
      */
     public function __construct()
     {
         $this->profession     = \App::make('App\Droit\Profession\Repo\ProfessionInterface');
         $this->canton         = \App::make('App\Droit\Canton\Repo\CantonInterface');
         $this->pays           = \App::make('App\Droit\Pays\Repo\PaysInterface');
         $this->specialisation = \App::make('App\Droit\Specialisation\Repo\SpecialisationInterface');
         $this->member         = \App::make('App\Droit\Member\Repo\MemberInterface');
     }

     public function getLabel($label,$id)
     {
         $label = $this->$label->find($id);

         if($label)
         {
             return $label->title;
         }

         return '';
     }

     public function nameTerms($terms)
     {

         $data = [];

         if(isset($terms['pays']))
         {
             $data['Pays'][] = $this->getLabel('pays',$terms['pays']);
         }

         if(isset($terms['cantons']))
         {
             foreach($terms['cantons'] as $canton)
             {
                 $data['Cantons'][] = $this->getLabel('canton',$canton);
             }
         }

         if(isset($terms['professions']))
         {
             foreach($terms['professions'] as $profession)
             {
                 $data['Professions'][] = $this->getLabel('profession',$profession);
             }
         }

         if(isset($terms['members']))
         {
             foreach($terms['members'] as $member)
             {
                 $data['Membres'][] = $this->getLabel('member',$member);
             }
         }

         if(isset($terms['specialisations']))
         {
             foreach($terms['specialisations'] as $specialisation)
             {
                 $data['Spécialisations'][] = $this->getLabel('specialisation',$specialisation);
             }
         }

         return $data;
     }
 }