<?php

namespace App\Droit\Shop\Rappel\Repo;

use App\Droit\Shop\Rappel\Repo\RappelInterface;
use App\Droit\Shop\Rappel\Entities\Rappel as M;

class RappelEloquent implements RappelInterface
{
    protected $rappel;

    public function __construct(M $rappel)
    {
        $this->rappel = $rappel;
    }

    public function getAll($period)
    {
        return $this->rappel->with(['order'])->period($period)->get();
    }

    public function find($id){

        return $this->rappel->find($id);
    }

    public function create(array $data)
    {
        $rappel = $this->rappel->create([
            'order_id' => $data['order_id'],
            'montant'  => (isset($data['montant']) ? $data['montant'] * 100 : null),
            'payed_at' => (isset($data['payed_at']) ? $data['payed_at'] : null),
        ]);

        if( ! $rappel )
        {
            return false;
        }

        return $rappel;
    }

    public function update(array $data)
    {
        $rappel = $this->rappel->findOrFail($data['id']);

        if( ! $rappel )
        {
            return false;
        }

        $rappel->fill($data);
        $rappel->save();

        return $rappel;
    }

    public function delete($id)
    {
        return $this->rappel->find($id)->delete();
    }
}