<?php namespace App\Droit\Shop\Order\Entities;

class OrderPreview
{
    protected $repo_product;
    protected $repo_user;
    protected $repo_shipping;
    protected $order_maker;

    public $data;

    public function __construct($data)
    {
        $this->repo_product  = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->repo_user     = \App::make('App\Droit\User\Repo\UserInterface');
        $this->repo_shipping = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');
        $this->order_maker   = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

        $this->data = $data;
    }

    public function products($onlyid = false)
    {
        $order = $this->data['order'];

        return collect($order['products'])->map(function ($product,$key) use ($order,$onlyid) {

            $money = new \App\Droit\Shop\Product\Entities\Money;

            $model    = $this->repo_product->find($order['products'][$key]);

            $rabais   = isset($order['rabais'][$key]) && !empty($order['rabais'][$key]) ? $order['rabais'][$key] : null;
            $special  = isset($order['price'][$key]) && !empty($order['price'][$key]) ? $order['price'][$key] : null;
            $gratuit  = isset($order['gratuit'][$key]) && !empty($order['gratuit'][$key]) ? true : false;

            $computed = $rabais  ? ($model->price - ($model->price * $rabais/100)) / 100 : $model->price_cents;
            $computed = $special ? $special : $computed;
            $computed = $gratuit ? 0 : $computed;

            return [
                'product'  => $onlyid ? $model->id : $model ,
                'qty'      => $order['qty'][$key],
                'rabais'   => $rabais,
                'price'    => $special,
                'gratuit'  => $gratuit ? 'oui' : null,
                'prix'     => $model->price_cents,
                'computed' => $money->format($computed * $order['qty'][$key])
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

    public function paquet()
    {
        if(isset($this->data['paquet']) && !empty($this->data['paquet'])){
            return $this->data['paquet'] > 1 ? $this->data['paquet'].' paquets' : $this->data['paquet'].' paquet';
        }

        $weight = $this->order_maker->total($this->data['order'], 'weight');
        $boxes  = orderBoxes($weight);

        return $boxes->map(function ($nbr,$boxe) {
            return $nbr .' paquet à '.$boxe;
        })->reduce(function ($carry, $item) {
            return $carry.'<span>'.$item.'</span><br/>';
        }, '');
    }

    public function tva()
    {
        return isset($this->data['tva']) ? $this->data['tva'] : null;
    }

    public function messages()
    {
        return isset($this->data['comment']) && !empty(array_filter($this->data['comment'])) ? $this->data['comment'] : null;
    }

    public function shipping_total()
    {
        // if is free return 0
        if(isset($this->data['free']) && !empty($this->data['free'])){ return 0; }

        if(isset($this->data['shipping_id']) && $this->data['shipping_id'] > 0){
            // Get paquet nbr and shipping costs
            $paquet   = isset($this->data['paquet']) ? $this->data['paquet'] : 1;
            $shipping = $this->repo_shipping->find($this->data['shipping_id']);

            return $paquet * $shipping->price_cents;
        }
        else{
            // Calculate nbr of paquets with weight
            $weight = $this->order_maker->total($this->data['order'], 'weight');
            $boxes  = orderBoxesShipping($weight);

            // Format price correctly
            $money       = new \App\Droit\Shop\Product\Entities\Money;
            $price_total = collect($boxes)->sum('price');
            $price       = $price_total/ 100;

            return $money->format($price);
        }
    }

    public function shipping()
    {
        if(isset($this->data['shipping_id']) && $this->data['shipping_id'] > 0){
            $shipping = $this->repo_shipping->find($this->data['shipping_id']);

            return isset($this->data['free']) ? 'Gratuit' : $shipping->title.' | '.$shipping->price_cents. ' CHF';
        }

        return '';
    }

    public function order_total()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        $product_prices = $this->order_maker->total($this->data['order']);
        $product_prices = $money->format($product_prices / 100);

        $shipping_price = $this->shipping_total();
        $total = $product_prices + $shipping_price;

        return $money->format($total);
    }

    public function references()
    {
        $html = '';

        if( (isset($this->data['reference_no']) && !empty($this->data['reference_no'])) || ( isset($this->data['transaction_no']) &&  !empty($this->data['transaction_no'])) ){
            $html .= '<dt>Références</dt>';
            $html .= isset($this->data['reference_no']) && !empty($this->data['reference_no']) ? '<dd>N° référence: <i> '.$this->data['reference_no'].'</i></dd>' : '';
            $html .= isset($this->data['transaction_no']) && !empty($this->data['transaction_no']) ?'<dd>N° commande: <i> '.$this->data['transaction_no'].'</i></dd>' : '';
        }

        return $html;
    }
}