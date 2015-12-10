<?php namespace App\Droit\Abo\Repo;

use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Entities\Abo_users;
use App\Droit\Abo\Entities\Abo_factures;
use App\Droit\Abo\Entities\Abo_rappels;

class AboUserEloquent implements AboUserInterface{

    protected $abo_user;
    protected $abo_facture;
    protected $abo_rappel;

    public function __construct(Abo_users $abo_user, Abo_factures $abo_facture, Abo_rappels $abo_rappel)
    {
        $this->abo_user    = $abo_user;
        $this->abo_facture = $abo_facture;
        $this->abo_rappel  = $abo_rappel;
    }

    public function getAll()
    {
        return $this->abo_user->all();
    }

    public function find($id)
    {
        return $this->abo_user->with(['tiers','user','abo','factures'])->find($id);
    }

    public function create(array $data){

        $abo_user = $this->abo_user->create(array(
            'abo_id'         => $data['abo_id'],
            'numero'         => $data['numero'],
            'exemplaires'    => $data['exemplaires'],
            'adresse_id'     => $data['adresse_id'],
            'tiers_id'       => isset($data['tiers_id']) && $data['tiers_id'] > 0 ? $data['tiers_id'] : null,
            'price'          => isset($data['price']) && $data['price'] > 0 ? $data['price'] : null,
            'reference'      => $data['reference'],
            'remarque'       => $data['remarque'],
            'status'         => $data['status'],
            'renouvellement' => $data['renouvellement'],
        ));

        if( ! $abo_user )
        {
            return false;
        }

        // Make first facture
        if(isset($data['product_id']))
        {
            $this->makeFacture(['abo_user_id' => $abo_user->id, 'product_id' => $data['product_id']]);
        }

        return $abo_user;
    }

    public function update(array $data){

        $abo_user = $this->abo_user->findOrFail($data['id']);

        if( ! $abo_user )
        {
            return false;
        }

        $abo_user->fill($data);

        $abo_user->save();

        return $abo_user;
    }

    public function delete($id){

        $abo_user = $this->abo_user->find($id);

        return $abo_user->delete();

    }

    public function makeRappel($data){

    }
}
