<?php

namespace App\Droit\Shop\Order\Worker;

use App\Droit\Shop\Product\Repo\ProductInterface;

class Order{

    protected $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
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
     * Reset qty of products when canceling order
     * */
    public function resetQty($order,$operator)
    {
        $products = $this->getQty($order);

        if(!empty($products))
        {
            foreach($products as $product_id => $qty)
            {
                $this->product->sku($product_id, $qty, $operator);
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
    * Get products id (from qty)
    *
    * Return [55,55,54,34]
    * */
    public function getProductsId($cart)
    {
        $ids = [];

        $cart->each(function($product) use (&$ids)
        {
            for($x = 0; $x < $product->qty; $x++)
            {
                $ids[] = $product->id;
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

        $products->map(function($product_id,$index) use (&$total, $data, $proprety)
        {
            $product = $this->product->find($product_id);

            for($x = 0; $x < $data['qty'][$index]; $x++)
            {
                if($proprety == 'price' && !isset($data['gratuit'][$index]) && isset($data['rabais'][$index]))
                {
                    $total += $product->$proprety - ( $product->$proprety * ($data['rabais'][$index]/100) );
                }
                elseif( ($proprety == 'price' && !isset($data['gratuit'][$index])) || $proprety != 'price')
                {
                    $total += $product->$proprety;
                }
            }
        });

        return $total;
    }

}