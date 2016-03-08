<?php

namespace App\Droit\Generate\Entities;

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

        if($this->model instanceof \App\Droit\Abo\Entities\Abo_factures)
        {
            $this->model->load('abonnement');
            return 'abo';
        }

        if($this->model instanceof \App\Droit\Inscription\Entities\Inscription)
        {
            $this->model->load('groupe','participant');
            return 'inscription';
        }
    }

    public function getAdresse()
    {
        if($this->getType() == 'abo')
        {
            $this->model->abonnement->load('user','tiers','originaluser','originaltiers');

            if($this->model->abonnement->tiers_id > 0)
            {
                return $this->model->abonnement->tiers ? $this->model->abonnement->tiers : $this->model->abonnement->originaltiers;
            }

            if($this->model->abonnement->user)
            {
                return $this->model->abonnement->user;
            }

            if($this->model->abonnement->originaluser)
            {
                return $this->model->abonnement->originaluser;
            }

            return null;
        }

        if($this->getType() == 'inscription' && $this->model->group_id)
        {
            $this->model->groupe->load('user');

            $user = $this->model->groupe->user;
        }
        else
        {
            $user = $this->model->user;
        }

        // Test if user exist
        if (!$user)
        {
            throw new \App\Exceptions\UserNotExistException('No user');
        }

        $user->load('adresses');

        return isset($user->adresse_facturation) ? $user->adresse_facturation : null;
    }

    public function getColloque()
    {
        $this->model->load('colloque');
        $this->model->colloque->load('location','compte','attestation');

        return $this->model->colloque;
    }

    public function getAbo()
    {
        $this->model->load('abonnement');
        return $this->model->abonnement;
    }

    public function getFacture()
    {
        return $this->model;
    }

    public function getNo()
    {
        return $this->getType() == 'group' ? $this->model->inscriptions->pluck('participant.name','inscription_no')->all() : $this->model->inscription_no;
    }

    public function getId()
    {
        return $this->model->id;
    }

    public function getPrice()
    {
        return $this->getType() == 'group' ? $this->model->price : $this->model->price->price_cents;
    }

    public function getParticipant()
    {
        $this->model->load('participant');

        return isset($this->model->participant) && !empty($this->model->participant) ? $this->model->participant->name : null;
    }

    public function getOptions()
    {
        if(!$this->model->user_options->isEmpty())
        {
            foreach($this->model->user_options as $option)
            {
                $option->load('option_groupe');

                $choice['title'] = $option->option->title;

                if($option->option->type == 'choix' && $option->option_groupe)
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

        if( ($annexe == 'facture' || $annexe == 'bv') && $this->getType() == 'group')
        {
            return public_path($path.$name.'_'.$this->model->colloque_id.'-'.$this->model->id.'-'.$this->model->user_id.'.pdf');
        }

        if($annexe == 'rappel')
        {
            return public_path($path.$name.'.pdf');
        }

        if($this->getType() == 'abo')
        {
            $path = 'files/abos/'.$annexe.'/'.$this->model->product_id;

            if (!\File::exists(public_path($path)))
            {
                \File::makeDirectory(public_path($path));
            }

            $file = $path.'/'.$annexe.'_'.$this->model->product->reference.'-'.$this->model->abo_user_id.'_'.$this->model->id.'.pdf';

            return public_path($file);
        }

        return public_path($path.$name.'_'.$this->model->colloque_id.'-'.$this->model->user_id.'.pdf');
    }

}