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
        $this->model->load('abonnement','abonnement.user','abonnement.tiers_user');
    }

    public function getDetenteur()
    {
        return $this->model->abonnement->user_adresse; // if tiers
    }

    public function getDetenteurAdresse()
    {
        return $this->model->abonnement->user_adresse;
    }

    public function getAdresse($tiers = null)
    {
        $abonnement = $this->model->abonnement->load('user','tiers_user');

        return $abonnement->user_facturation;
    }

    public function getAbo()
    {
        return $this->model->abonnement;
    }

    public function getFacture()
    {
        return $this->model;
    }

    public function isTiers()
    {
        return $this->model->abonnement->is_tiers;
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

    public function getReferences()
    {
        return $this->model->abonnement->references;
    }
}