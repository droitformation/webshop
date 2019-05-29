<?php

namespace App\Droit\Generate\Entities;

/**
 * Element for document
 *
 * File name
 * Rappel name must be rappel_$rappel->id
 * Document type (facture, bon, bv)
 */

class AboGenerate{

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getDetenteur()
    {
        $abonnement = $this->model->abonnement->load('user','tiers','realuser','tiers_user');

        return $abonnement->user_adresse; // if tiers
    }

    public function getDetenteurAdresse()
    {
        $abonnement = $this->model->abonnement->load('user','tiers','realuser','tiers_user');

        return $abonnement->main_adresse;
    }

    public function getAdresse()
    {
        $abonnement = $this->model->abonnement->load('user','tiers','realuser','tiers_user');

        return ( ($abonnement->tiers_id > 0 || $abonnement->tiers_user_id > 0) && isset($abonnement->user_facturation)) ? $abonnement->user_facturation : $abonnement->user_adresse ; // if tiers
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

    public function isTiers()
    {
        return ($this->model->abonnement->tiers_id > 0 || $this->model->abonnement->tiers_user_id > 0) ? true : false;
    }

    public function getFilename($annexe,$name)
    {
        $path = 'files/abos/'.$annexe.'/'.$this->model->product_id;

        if (!\File::exists(public_path($path))) {
            if (!mkdir(public_path($path), 0755, true)) {
                die('Failed to create folders...');
            }
        }

        if($annexe == 'rappel') {
            return public_path($path.'/'.$name.'_'.$this->model->id.'.pdf');
        }

        $file = $path.'/'.$annexe.'_'.$this->model->product->reference.'-'.$this->model->abo_user_id.'_'.$this->model->product_id.'.pdf';

        return public_path($file);
    }
}