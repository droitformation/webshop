<?php namespace App\Droit\Abo\Repo;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Entities\Abo_factures as M;

class AboFactureEloquent implements AboFactureInterface{

    protected $facture;

    public function __construct(M $facture)
    {
        $this->facture = $facture;
    }

    public function getAll($product_id)
    {
        return $this->facture->where('product_id','=',$product_id)->get();
    }

    public function find($id){

        return $this->facture->with(['abonnement','product'])->find($id);
    }

    public function findByUserAndProduct($abo_user_id, $product_id)
    {
        $facture = $this->facture->where('abo_user_id','=',$abo_user_id)->where('product_id','=',$product_id)->get();

        if(!$facture->isEmpty())
        {
            return $facture->first();
        }

        return null;
    }

    public function create(array $data){

        $facture = $this->facture->create(array(
            'abo_user_id' => $data['abo_user_id'],
            'product_id'  => $data['product_id'],
            'created_at'  => \Carbon\Carbon::now(),
        ));

        if( ! $facture )
        {
            return false;
        }

        return $facture;
    }

    public function update(array $data){

        $facture = $this->facture->findOrFail($data['id']);

        if( !$facture )
        {
            return false;
        }

        $facture->fill($data);
        $facture->payed_at = (isset($data['payed_at']) && !empty($data['payed_at']) ? $data['payed_at'] : null);
        $facture->save();

        return $facture;
    }

    public function delete($id)
    {
        $facture = $this->facture->find($id);

        return $facture->delete();
    }
}
