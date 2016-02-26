<?php

namespace App\Droit\Inscription\Entities;

/**
 * Class for inscription docuemnt generator
 * Prepare simple or grouped inscriptions
 *
 * Elements for inscription:
 *
 * # GENERAL #
 * Adresse facturation
 * N°
 * Price
 *
 * # BON #
 * Options
 *
 * Element for document
 *
 * File name
 * Rappel name must be rappel_$rappel->id
 * Document type (facture, bon, bv)
 */

class Generate{

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getType()
    {
        if($this->model instanceof \App\Droit\Inscription\Entities\Groupe)
        {
            $this->model->load('inscriptions.participant');
            return 'group';
        }

        if($this->model instanceof \App\Droit\Inscription\Entities\Inscription)
        {
            return 'inscription';
        }
    }

    public function getAdresse()
    {
        $this->model->load('user');

        $user = $this->model->user;

        // Test if user exist
        if (!$user) {
            throw new \App\Exceptions\UserNotExistException('No user', []);
        }

        $user->load('adresses');

        // Test if adresse exist
        if($user->adresse_facturation) {
            throw new \App\Exceptions\AdresseNotExistException('No adresse', []);
        }

        return $user->adresse_facturation;
    }

    public function getNo()
    {
        return $this->getType() == 'group' ? $this->model->inscriptions->pluck('participant.name','inscription_no')->all() : $this->model->inscription_no;
    }

    public function getPrice()
    {
        return $this->getType() == 'group' ? $this->model->inscriptions->sum('price.price') : $this->model->price->price;
    }

    public function getOptions()
    {
        if(!$this->model->user_options->isEmpty())
        {
            foreach($this->model->user_options as $option)
            {
                $option->load('option_groupe');

                $choice['title'] = $option->option->title;

                if($option->option->type == 'choix')
                {
                    $choice['choice'] = $option->option_groupe->text;
                }
                if($option->option->type == 'text')
                {
                    $choice['choice'] = $option->reponse;
                }

                $list[] = $choice;
            }

            return $list;
        }

        return null;
    }

    public function getFilename($annexe,$name)
    {
        $path = config('documents.colloque.'.$annexe);

        $part = (isset($this->model->participant) ? $this->model->group_id.'-'.$this->model->participant->id : $this->model->user_id);

        if($annexe == 'bon')
        {
            return public_path($path.$name.'_'.$this->model->colloque_id.'-'.$part.'.pdf');
        }

        return public_path($path.$name.'_'.$this->model->colloque_id.'-'.$this->model->user_id.'.pdf');
    }

}