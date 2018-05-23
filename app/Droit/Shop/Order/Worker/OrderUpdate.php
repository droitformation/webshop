<?php

namespace App\Droit\Shop\Order\Worker;

class OrderUpdate
{
    public $data;
    protected $order;
    protected $request;
    protected $coupon;

    protected $repo_order;
    protected $repo_coupon;
    protected $repo_product;

    public function __construct($request,$order){
        $this->request = $request;
        $this->order   = $order;

        $this->repo_order = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->repo_coupon = \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->repo_product = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');
    }

    public function prepareData()
    {
        $this->data = array_filter(array_only($this->request,['id','created_at','paquet','user_id','adresse_id','comment']));
        $this->data['coupon_id']   = null;
        $this->data['shipping_id'] = null;

        $this->coupon()->shipping();
    }

    // calculate coupon
    public function coupon()
    {
        if(isset($this->request['coupon_id']) && !empty($this->request['coupon_id'])){
            $this->data['coupon_id'] = $this->request['coupon_id'];

            $coupon = $this->repo_coupon->find($this->request['coupon_id']);

            $products_updated = $this->updateProducts($coupon);

            $total = $products_updated->map(function ($item, $key) {
                return $item['price'];
            })->sum();

            $this->data['amount']   = number_format($total, 0, '.', '');
            $this->data['products'] = $products_updated->toArray();

            $this->coupon = $coupon;
        }

        return $this;
    }

    public function shipping()
    {
        if(isset($this->request['shipping_id'])) {
/*            $paquets = $this->order->paquets();
            $this->order->paquets()->detach();
            $paquets->delete();*/

            $this->data['shipping_id'] = isset($this->coupon) && ($this->coupon->type == 'priceshipping' || $this->coupon->type == 'shipping') ? 6 : $this->request['shipping_id'];
            $this->data['paquet'] = isset($this->request['paquet']) ? $this->request['paquet'] : null;
        }
        else{
            $orderbox = new \App\Droit\Shop\Order\Entities\OrderBox($this->order);
            $paquets  = $orderbox->calculate($this->order->weight)->getShippingList();

           // $this->repo_order->setPaquets($this->order,$paquets);
            $this->data['boxes'] = $paquets;

            $this->data['shipping_id'] = isset($this->coupon) && ($this->coupon->type == 'priceshipping' || $this->coupon->type == 'shipping') ? 6 : null;
            $this->data['paquet'] = null;
        }

        return $this;
    }

    public function update()
    {
        $order = $this->repo_order->update($this->data);

        $messages = array_filter(array_only($this->request,['tva','message']));

        if(isset($messages['tva']) && !empty($messages['tva']))
            $this->pdfgenerator->setTva($messages['tva']);

        if(isset($messages['message']) && !empty($messages['message']))
            $this->pdfgenerator->setMsg($messages['message']);

        $this->pdfgenerator->factureOrder($order);
    }

    public function updateProducts($coupon)
    {
        return $this->order->products->map(function ($product, $key) use ($coupon) {

            $price  = !$product->pivot->isFree ? $product->price_cents : 0;

            // search if product eligible for discount
            if(isset($coupon->products) && $coupon->products->contains($product->id)) {
                if($coupon->type == 'product') {
                    $price = $this->calculPriceWithCoupon($product, $coupon, 'percent');
                }

                if($coupon->type == 'price' || $coupon->type == 'priceshipping') {
                    $price = $this->calculPriceWithCoupon($product, $coupon, 'minus');
                }
            }

            if(isset($coupon) && $coupon->type == 'global') {
                $price = $this->calculPriceWithCoupon($product, $coupon, 'percent');
            }

            return ['id' => $product->id, 'price' => $price * 100, 'isFree' => $product->pivot->isFree, 'rabais' => $product->pivot->rabais];
        });
    }

    /**
     * Calculat price from product and apply coupon discount
     * IIT
     * @return float
     * */
    public function calculPriceWithCoupon($product,$coupon,$operand)
    {
        if($operand == 'percent') {
            return $product->price_normal - ($product->price_normal * ($coupon->value)/100);
        }

        if($operand == 'minus') {
            return $product->price_normal - $coupon->value;
        }
    }
    // calculate shipping paquets
    // set messages
    // remake pdf
}