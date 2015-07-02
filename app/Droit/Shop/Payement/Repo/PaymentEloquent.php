<?php namespace App\Droit\Shop\Payment\Repo;

use App\Droit\Shop\Payment\Repo\PaymentInterface;
use App\Droit\Shop\Payment\Entities\Payment as M;

class PaymentEloquent implements PaymentInterface{

    protected $payment;

    public function __construct(M $payment)
    {
        $this->payment = $payment;
    }

    public function getAll(){

        return $this->payment->all();
    }

    public function find($id){

        return $this->payment->find($id);
    }

    public function create(array $data){

        $payment = $this->payment->create(array(
            'title' => $data['title'],
            'image' => $data['image']
        ));

        if( ! $payment )
        {
            return false;
        }

        return $payment;

    }

    public function update(array $data){

        $payment = $this->payment->findOrFail($data['id']);

        if( ! $payment )
        {
            return false;
        }

        $payment->fill($data);

        $payment->save();

        return $payment;
    }

    public function delete($id){

        return $this->payment->find($id)->delete();

    }

}
