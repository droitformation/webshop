<?php namespace App\Droit\Shop\Order\Entities;

class OrderPreview
{
    protected $repo_product;
    protected $repo_user;
    protected $repo_shipping;

    public $data;

    public function __construct($data)
    {
        $this->repo_product  = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->repo_user     = \App::make('App\Droit\User\Repo\UserInterface');
        $this->repo_shipping = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');

        $this->data = $data;
    }

    public function products($onlyid = false)
    {
        $order = $this->data['order'];

        return collect($order['products'])->map(function ($product,$key) use ($order,$onlyid) {

            $model = $this->repo_product->find($order['products'][$key]);

            return [
                'product' => $onlyid ? $model->id : $model ,
                'qty'     => $order['qty'][$key],
                'rabais'  => isset($order['rabais'][$key]) && !empty($order['rabais'][$key]) ? $order['rabais'][$key].'%' : null,
                'price'   => isset($order['price'][$key]) && !empty($order['price'][$key]) ? $order['price'][$key].' CHF' : null,
                'gratuit' => isset($order['gratuit'][$key]) && !empty($order['gratuit'][$key]) ? 'oui' : null,
            ];
        });
    }

    public function adresse($onlyarray = false)
    {
        if($onlyarray){
            unset($this->data['adresse']['canton_id'],$this->data['adresse']['pays_id'],$this->data['adresse']['civilite_id']);
            return array_filter($this->data['adresse']);
        }

        if(isset($this->data['user_id']) && !empty($this->data['user_id'])){
            $user = $this->repo_user->find($this->data['user_id']);

            return isset($user->adresse_facturation) ? $user->adresse_facturation : null;
        }

        return factory(\App\Droit\Adresse\Entities\Adresse::class)->make($this->data['adresse']);
    }

    public function shipping()
    {
        $shipping = $this->repo_shipping->find($this->data['shipping_id']);

        return isset($this->data['free']) ? 'Gratuit' : $shipping->title;
    }

    public function paquet()
    {
        return $this->data['paquet'] > 1 ? $this->data['paquet'].' paquets' : $this->data['paquet'].' paquet';
    }

    public function tva()
    {
        return $this->data['tva'];
    }

    public function messages()
    {
        return $this->data['message'];
    }
}