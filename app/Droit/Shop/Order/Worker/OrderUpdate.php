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
    protected $pdfgenerator;

    public function __construct($request,$order){
        $this->request = $request;
        $this->order   = $order;

        $this->repo_order = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->repo_coupon = \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->repo_product = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->pdfgenerator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    }

    public function updateOrder()
    {
        return $this->prepareData()->coupon()->shipping()->messages()->update();
    }

    public function prepareData()
    {
        $this->data = array_filter(array_only($this->request,['id','created_at','paquet','user_id','adresse_id','comment']));
        $this->data['coupon_id']   = $this->order->coupon_id;
        $this->data['shipping_id'] = $this->order->shipping_id;
        $this->data['comment']     = isset($this->order->comment) ? unserialize($this->order->comment) : null;

        return $this;
    }

    // calculate coupon
    public function coupon()
    {
        $coupon = null;
        $this->data['coupon_id'] = null;

        if(isset($this->request['coupon_id']) && !empty($this->request['coupon_id'])){
            $this->data['coupon_id'] = $this->request['coupon_id'];

            $coupon = $this->repo_coupon->find($this->request['coupon_id']);

            $this->coupon = $coupon;
        }

        $products_updated = $this->updateProducts($coupon);

        $total = $products_updated->map(function ($item, $key) {
            return $item['price'];
        })->sum();

        $this->data['amount']   = number_format($total, 0, '.', '');
        $this->data['products'] = $products_updated->toArray();

        return $this;
    }

    public function shipping()
    {
        if(isset($this->request['shipping_id'])) {

            $paquets = $this->order->paquets();
            $this->order->paquets()->detach();
            $paquets->delete();

            $this->data['shipping_id'] = isset($this->coupon) && ($this->coupon->type == 'priceshipping' || $this->coupon->type == 'shipping') ? 6 : $this->request['shipping_id'];
            $this->data['paquet'] = isset($this->request['paquet']) ? $this->request['paquet'] : null;
        }
        else{
            $orderbox = new \App\Droit\Shop\Order\Entities\OrderBox($this->order);
            $paquets  = $orderbox->calculate($this->order->weight)->getShippingList();

            $this->repo_order->setPaquets($this->order,$paquets);
            //$this->data['boxes'] = $paquets;

            $this->data['shipping_id'] = isset($this->coupon) && ($this->coupon->type == 'priceshipping' || $this->coupon->type == 'shipping') ? 6 : null;
            $this->data['paquet'] = null;
        }

        return $this;
    }

    public function messages()
    {
        if(isset($this->request['comment']) && !empty($this->request['comment'])){
            $this->data['comment'] = $this->request['comment'];
        }

        if(isset($this->request['tva']) && !empty($this->request['tva'])){
            $this->data['tva'] = $this->request['tva'];
        }

        return $this;
    }

    public function update()
    {
        $order = $this->repo_order->update($this->data);

        if(isset($this->data['tva']) && !empty(array_filter($this->data['tva'])))
            $this->pdfgenerator->setTva($this->data['tva']);

        if(isset($this->data['comment']) && !empty($this->data['comment']))
            $this->pdfgenerator->setMsg($this->data['comment']);

        $this->pdfgenerator->factureOrder($order);

        return $order;
    }

    public function updateProducts($coupon = null)
    {
        return $this->order->products->map(function ($product, $key) use ($coupon) {

            $price  = !$product->pivot->isFree ? $product->price_cents : 0;

            // search if product eligible for discount
            if(isset($coupon) && isset($coupon->products) && $coupon->products->contains($product->id)) {
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