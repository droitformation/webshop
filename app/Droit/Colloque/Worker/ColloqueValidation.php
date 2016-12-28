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
        $this->hasAttestation()->hasPrice();

        if(!empty($this->errors)){
            throw new \App\Exceptions\ColloqueMissingInfoException(implode(', ', $this->errors));
        }

        return true;
    }

    public function hasAttestation()
    {
        if(!count($this->colloque->attestation)){
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
}