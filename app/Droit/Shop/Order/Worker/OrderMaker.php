<?php

namespace App\Droit\Shop\Order\Worker;

use App\Droit\Shop\Order\Worker\OrderMakerInterface;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;
use App\Droit\Shop\Cart\Repo\CartInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Stock\Repo\StockInterface;

class OrderMaker implements OrderMakerInterface{

    protected $order;
    protected $product;
    protected $shipping;
    protected $adresse;
    protected $generator;
    protected $cart;
    protected $worker;
    protected $stock;

    public function __construct(
        OrderInterface $order,
        ProductInterface $product,
        ShippingInterface $shipping,
        AdresseInterface $adresse,
        PdfGeneratorInterface $generator,
        CartInterface $cart,
        CartWorkerInterface $worker,
        StockInterface $stock
    )
    {
        $this->order     = $order;
        $this->product   = $product;
        $this->shipping  = $shipping;
        $this->adresse   = $adresse;
        $this->generator = $generator;
        $this->cart      = $cart;
        $this->worker    = $worker;
        $this->stock     = $stock;
    }

    /*
     * Prepare data and insert order in DB
     * We can pass shipping already calculated and coupon from shop
     * Generate a invoice in pdf and add messages and/or change TVA
     * */
    public function make($commande, $shipping = null, $coupon = null)
    {
        $data  = $this->prepare($commande, $shipping, $coupon);

        $order = $this->insert($data);

        // Update Qty of products
        $this->resetQty($order,'-');

        // Create invoice for order
        if(isset($commande['tva']) && !empty(array_filter($commande['tva'])))
            $this->generator->setTva(array_filter($commande['tva']));

        if(isset($commande['message']) && !empty(array_filter($commande['message'])))
            $this->generator->setMsg(array_filter($commande['message']));

        $this->generator->factureOrder($order);

        return $order;
    }

    /*
    * Prepare data for order
    * From frontend and backend
    * */
    public function prepare($order = null, $shipping = null, $coupon = null)
    {
        $data = [
            'order_no'    => $this->order->newOrderNumber(),
            'amount'      => isset($order['admin']) ? $this->total($order['order']) : \Cart::instance('shop')->total() * 100,
            'coupon_id'   => ($coupon ? $coupon['id'] : null),
            'shipping_id' => $shipping ? $shipping->id : $this->getShipping($order),
            'paquet'      => isset($order['paquet']) ? $order['paquet'] : null,
            'payement_id' => 1,
            'products'    => isset($order['admin']) ? $this->getProducts($order['order']) : $this->getProductsCart(\Cart::instance('shop')->content())
        ];

        $user = isset($order['admin']) ? $this->getUser($order) : ['user_id' => \Auth::user()->id];
        $data = array_merge($user,$data);

        return $data;
    }

    /*
    * Insert new order
    * Save the cart if any
    * */
    public function insert($data)
    {
        $order = $this->order->create($data);

        $cart  = \Cart::instance('shop')->content();

        if(!$order && !empty($cart) && !$cart->isEmpty())
        {
            $this->cart->create([
                'user_id'   => $data['user_id'],
                'cart'      => serialize($cart),
                'coupon_id' => $data['coupon_id']
            ]);

            \Log::error('Problème lors de la commande'. serialize($data));

            throw new \App\Exceptions\OrderCreationException('Problème lors de la commande');
        }

        return $order;
    }

    /*
     *  Get the user or make new adresse from backend
     * */
    public function getUser($order)
    {
        if(isset($order['user_id']) || isset($order['adresse_id']))
        {
            if(isset($order['user_id'])) {
                return ['user_id' => $order['user_id']];
            }
            else {
                return ['adresse_id' => $order['adresse_id']];
            }
        }
        else
        {
            $adresse = $this->adresse->create($order['adresse']);
            return ['adresse_id' => $adresse->id];
        }
    }

    /*
    * Form admin the values for rabais and free can be send as null in the order array
    * Unset empty elements to create new order
    * */
    public function removeEmpty($items)
    {
        foreach($items as $key => $value)
        {
            if(is_null($value) || $value == '')
                unset($items[$key]);
        }

        return $items;
    }

    /*
    * Count qty for each product in order
    * */
    public function getCountProducts($order)
    {
        $products = $order->products->groupBy('id');

        foreach($products as $id => $product)
        {
            $count[$id] = $product->sum(function ($item) {
                return count($item['id']);
            });
        }

        return $count;
    }

    /*
     * Reset qty of products
     * */
    public function resetQty($order,$operator)
    {
        $products = $this->getQty($order);

        if(!empty($products))
        {
            foreach($products as $product_id => $qty)
            {
                $this->product->sku($product_id, $qty, $operator);

                // Insert entry for stock history
                $motif = $operator == '+' ? 'Annulation commande ' : 'Commande ';
                $this->stock->create(['product_id' => $product_id, 'amount' => $qty, 'motif' => $motif.$order->order_no, 'operator' => $operator]);
            }
        }
    }

    /*
    * Get qty for each product
    *
    * Return [21 => 1, 3 => 2, 223 => 1] (product_id => qty)
    * */
    public function getQty($order)
    {
        return $products = $order->products->groupBy('id')->map(function ($group) {
            return $group->sum(function ($item) {
                return count($item['id']);
            });
        });
    }

    /*
    * Get products id (from qty) from cart instance
    *
    * Return [55,55,54,34]
    * */
    public function getProductsCart($cart)
    {
        $ids = [];

        $cart->each(function($product) use (&$ids)
        {
            for($x = 0; $x < $product->qty; $x++)
            {
                $ids[] = ['id' => $product->id];
            }
        });

        return $ids;
    }

    /*
    * Get products id from form
    *
    * $expected = [
        [1 => ['isFree' => 1]],
        [2 => ['isFree' => null,'rabais' => 10]],
        [2 => ['isFree' => null,'rabais' => 10]],
        [3 => ['isFree' => null]]
      ];
    * */
    public function getProducts($order)
    {
        $ids = [];

        $products = new \Illuminate\Support\Collection($order['products']);

        $data['qty']     = $this->removeEmpty($order['qty']);
        $data['gratuit'] = (isset($order['gratuit']) ? $this->removeEmpty($order['gratuit']) : []);
        $data['rabais']  = (isset($order['rabais']) ?  $this->removeEmpty($order['rabais'])  : []);
        $data['price']   = (isset($order['price']) ?  $this->removeEmpty($order['price'])  : []);

        $products->map(function($product,$index) use (&$ids, $data)
        {
            for($x = 0; $x < $data['qty'][$index]; $x++)
            {
                $list['id']     = $product;
                $list['isFree'] = (isset($data['gratuit'][$index]) ? 1 : null);
                $list['rabais'] = (isset($data['rabais'][$index]) ? $data['rabais'][$index] : null);
                $list['price']  = (isset($data['price'][$index]) ? $data['price'][$index] * 100 : null);

                $ids[] = $list;
            }
        });

        return $ids;
    }

    /*
     *  Total for price and weight
     * */
    public function total($commande, $proprety = 'price')
    {
        $total    = 0;
        $products = new \Illuminate\Support\Collection($commande['products']);

        $data['qty']     = $this->removeEmpty($commande['qty']);
        $data['gratuit'] = (isset($commande['gratuit']) ? $this->removeEmpty($commande['gratuit']) : []);
        $data['rabais']  = (isset($commande['rabais']) ?  $this->removeEmpty($commande['rabais'])  : []);
        $data['price']   = (isset($commande['price']) ?  $this->removeEmpty($commande['price'])  : []);

        $products->map(function($product_id,$index) use (&$total, $data, $proprety)
        {
            $product = $this->product->find($product_id);

            for($x = 0; $x < $data['qty'][$index]; $x++)
            {
                // Calculate final price for product
                if($proprety == 'price' && !isset($data['gratuit'][$index]) && isset($data['rabais'][$index]))
                {
                    $price = (isset($data['price'][$index]) ? $data['price'][$index] * 100 : $product->$proprety);

                    $total += $price - ( $price * ($data['rabais'][$index]/100) );
                }
                elseif( ($proprety == 'price' && !isset($data['gratuit'][$index])) || $proprety != 'price')
                {
                    $price = (isset($data['price'][$index]) && ($proprety == 'price') ? $data['price'][$index] * 100 : $product->$proprety);

                    $total += $price;
                }
            }
        });

        return $total;
    }

    /*
     * Get Shipping from the weight or test if free request from backend
     **/
    public function getShipping($order)
    {
        $weight   = $this->total($order['order'], 'weight');
        $weight   = isset($order['free']) ? null : $weight;
        $shipping = $this->shipping->getShipping($weight);

        return $shipping->id;
    }

    /*
     * Update Order products with coupon
     **/
    public function updateOrder($order, $shipping_id, $coupon = null)
    {
        $data['id']          = $order->id;
        $data['created_at']  = $order->created_at->format('Y-m-d');
        $data['coupon_id']   = isset($coupon) ? $coupon->id : null;
        $data['shipping_id'] = isset($coupon) && ($coupon->type == 'priceshipping' || $coupon->type == 'shipping') ? 6 : $shipping_id;

        $products_updated = $this->updateProducts($order, $coupon);

        $total = $products_updated->map(function ($item, $key) {
            return $item['price'];
        })->sum();

        $data['amount']   = number_format($total, 0, '.', '');
        $data['products'] = $products_updated->toArray();
        
        return $data;
    }

    public function updateProducts($order, $coupon = null)
    {
        return $order->products->map(function ($product, $key) use ($coupon) {
            
            $price  = !$product->pivot->isFree ? $product->price_cents : 0;

            // search if product eligible for discount is in cart
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
}