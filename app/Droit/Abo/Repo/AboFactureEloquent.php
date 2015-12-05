<?php namespace App\Droit\Abo\Repo;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Entities\Abo_factures as M;

class AboFactureEloquent implements AboFactureInterface{

    protected $facture;

    public function __construct(M $facture)
    {
        $this->facture = $facture;
    }

    public function getAll(){

        return $this->facture->all();
    }

    public function find($id){

        return $this->facture->with(['abonnement','product'])->find($id);
    }

    public function create(array $data){

        $facture = $this->facture->create(array(
            'abo_user_id' => $data['abo_user_id'],
            'payed_at'    => $data['payed_at'],
            'product_id'  => $data['product_id']
        ));

        if( ! $facture )
        {
            return false;
        }

        return $facture;
    }

    public function update(array $data){

        $facture = $this->facture->findOrFail($data['id']);

        if( ! $facture )
        {
            return false;
        }

        $facture->fill($data);
        $facture->save();

        return $facture;
    }

    public function delete($id){

        $facture = $this->facture->find($id);

        return $facture->delete();
    }
}
