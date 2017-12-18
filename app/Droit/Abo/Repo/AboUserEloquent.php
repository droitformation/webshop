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

    public function getAll($nbr = null)
    {
        return $this->abo_user->take($nbr)->get();
    }

    public function find($id)
    {
        return $this->abo_user->with(['tiers','user','realuser.primary_adresse','tiers_user','abo','abo.products','factures','factures.rappels'])->find($id);
    }

    public function allByAdresse($id)
    {
        $abos = $this->abo_user->with(['user','abo']);

        if(is_array($id))
        {
            return $abos->whereIn('adresse_id', $id)->get();
        }

        return $abos->where('adresse_id', '=', $id)->get();
    }

    public function findByAdresse($id, $abo_id)
    {
        $abo = $this->abo_user->where('abo_id', '=', $abo_id)->where('adresse_id', '=', $id)->get();

        if(!$abo->isEmpty()){
            return $abo->first();
        }

        return null;
    }

    public function max($abo_id)
    {
        return $this->abo_user->where('abo_id', '=', $abo_id)->max('numero');
    }

    public function create(array $data){

        $abo_user = $this->abo_user->create(array(
            'abo_id'         => $data['abo_id'],
            'numero'         => $data['numero'],
            'exemplaires'    => $data['exemplaires'],
            'adresse_id'     => isset($data['adresse_id']) && $data['adresse_id'] > 0 ? $data['adresse_id'] : null,
            'tiers_id'       => isset($data['tiers_id']) && $data['tiers_id'] > 0 ? $data['tiers_id'] : null,
            'user_id'        => isset($data['user_id']) && $data['user_id'] > 0 ? $data['user_id'] : null,
            'price'          => isset($data['price']) && $data['price'] > 0 ? $data['price'] : null,
            'reference'      => isset($data['reference']) ? $data['reference'] : null,
            'remarque'       => isset($data['remarque']) ? $data['remarque'] : null,
            'status'         => $data['status'],
            'renouvellement' => $data['renouvellement'],
        ));

        if( ! $abo_user )
        {
            return false;
        }

        return $abo_user;
    }

    public function update(array $data){

        $abo_user = $this->abo_user->findOrFail($data['id']);

        if( ! $abo_user ) {
            return false;
        }

        $abo_user->fill($data);
        $abo_user->price = (isset($data['price']) && $data['price'] > 0 ? $data['price'] * 100 : null);

        if(isset($data['adresse_id']) && $data['adresse_id'] > 0 && !isset($data['user_id'])) {
            $abo_user->user_id    = null;
            $abo_user->adresse_id = $data['adresse_id'];
        }

        if( (isset($data['user_id']) && $data['user_id'] > 0) || (isset($data['adresse_id']) && isset($data['user_id'])) ) {
            $abo_user->adresse_id = null;
            $abo_user->user_id    = $data['user_id'];
        }

        $abo_user->tiers_id = (isset($data['tiers_id']) && $data['tiers_id'] > 0) ? $data['tiers_id'] : null;
        $abo_user->tiers_user_id = (isset($data['tiers_user_id']) && $data['tiers_user_id'] > 0) ? $data['tiers_user_id'] : null;

        $abo_user->save();

        return $abo_user;
    }

    public function restore($id)
    {
        return $this->abo_user->withTrashed()->find($id)->restore();
    }

    public function delete($id){

        $abo_user = $this->abo_user->find($id);

        return $abo_user->delete();
    }

    public function makeFacture($data)
    {
        $facture = $this->abo_facture->create($data);

        if(!$facture)
        {
            return false;
        }

        return $facture;
    }
}
