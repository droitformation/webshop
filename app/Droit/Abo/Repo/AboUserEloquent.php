<?php namespace App\Droit\Abo\Repo;

use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Entities\Abo_users as M;

class AboUserEloquent implements AboUserInterface{

    protected $abo_user;

    public function __construct(M $abo_user)
    {
        $this->abo_user = $abo_user;
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
            'tiers_id'       => $data['tiers_id'],
            'price'          => $data['price'],
            'reference'      => $data['reference'],
            'remarque'       => $data['remarque'],
            'status'         => $data['status'],
            'renouvellement' => $data['renouvellement'],
            'plan'           => $data['plan']
        ));

        if( ! $abo_user )
        {
            return false;
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
}
