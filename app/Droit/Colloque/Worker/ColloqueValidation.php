<?php

namespace App\Droit\Colloque\Worker;

use App\Droit\Colloque\Entities\Colloque;

class ColloqueValidation
{
    protected $colloque;
    
    public $errors = [];

    public function __construct(Colloque $colloque)
    {
        $this->colloque = $colloque;
    }

    public function activate()
    {
        $this->hasAttestation()->hasPrice()->hasInvoice();

        if(!empty($this->errors)){
            flash(implode(', ', $this->errors))->warning();
            return redirect()->back();
        }

        return true;
    }

    public function hasAttestation()
    {
        if(!isset($this->colloque->attestation)){
            $this->errors[] = 'Il manque les infos d\'attestation pour activer le colloque';
        }

        return $this;
    }

    public function hasPrice()
    {
        if($this->colloque->prices->isEmpty()){
            $this->errors[] = 'Il manque au moins un prix';
        }

        return $this;
    }

    public function hasInvoice()
    {
        // test if we have a compte to make the bv
        if($this->colloque->facture && (!isset($this->colloque->compte) || empty($this->colloque->compte)))
        {
            $this->errors[] = 'Il manque un compte pour le créer la facture';
        }

        return $this;
    }
}