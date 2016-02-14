<?php

namespace App\Droit\Shop\Order\Worker;

use App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Cart\Repo\CartInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

class OrderAdminWorker implements OrderAdminWorkerInterface{

    protected $order;
    protected $cart;
    protected $worker;
    protected $user;
    protected $generator;
    protected $product;
    protected $shipping;
    protected $adresse;

    public function __construct(OrderInterface $order, CartWorkerInterface $worker, UserInterface $user, CartInterface $cart, PdfGeneratorInterface $generator, ProductInterface $product, ShippingInterface $shipping, AdresseInterface $adresse)
    {
        $this->order     = $order;
        $this->cart      = $cart;
        $this->worker    = $worker;
        $this->user      = $user;
        $this->product   = $product;
        $this->shipping  = $shipping;
        $this->generator = $generator;
        $this->adresse   = $adresse;
    }

    public function prepare($commande)
    {
        if(!isset($commande['user_id']))
        {
            $adresse = $this->adresse->create($commande['adresse']);
            $data['adresse_id'] = $adresse->id;
        }
        else
        {
            $data['user_id'] = $commande['user_id'];
        }

        // Find shipping
        $weight   = $this->total($commande['order'], 'weight');
        $weight   = (isset($commande['free']) ? null : $weight);
        $shipping = $this->shipping->getShipping($weight);

        $data['shipping_id'] = $shipping->id;
        $data['products']    = $this->productIdFromForm($commande['order']);
        $data['amount']      = $this->total($commande['order']);
        $data['order_no']    = $this->order->newOrderNumber();

        $tva = array_filter($commande['tva']);

        if(!empty($tva))
        {
            $data['tva'] = $tva;
        }

        $message = array_filter($commande['message']);

        if(!empty($message))
        {
            $data['message'] = $message;
        }

        return $data;
    }

    public function make($data)
    {
        $prepared = $this->prepare($data);

        $commande = [
            'user_id'     => (isset($prepared['user_id']) ? $prepared['user_id'] : null),
            'adresse_id'  => (isset($prepared['adresse_id']) ? $prepared['adresse_id'] : null),
            'order_no'    => $prepared['order_no'],
            'amount'      => $prepared['amount'],
            'coupon_id'   => null,
            'shipping_id' => $prepared['shipping_id'],
            'payement_id' => 1,
            'products'    => $prepared['products'],
            'admin'       => true
        ];

        // Order global
        $order = $this->insertOrder($commande);

        if(isset($prepared['tva']))
        {
            $this->generator->setTva($prepared['tva']);
        }

        if(isset($prepared['message']))
        {
            foreach($prepared['message'] as $type => $message)
            {
                $this->generator->setMsg($message,$type);
            }
        }

        // Create invoice for order
        $this->generator->factureOrder($order->id);

        return $order;
    }

    public function total($commande, $proprety = 'price')
    {
        $total    = 0;
        $qty      = array_filter($commande['qty']);

        $gratuit  = (isset($commande['gratuit']) ? $this->removeEmpty($commande['gratuit']) : []);
        $rabais   = (isset($commande['rabais']) ?  $this->removeEmpty($commande['rabais']) : []);

        foreach($commande['products'] as $index => $product_id)
        {
            $product = $this->product->find($product_id);

            for($x = 0; $x < $qty[$index]; $x++)
            {
                if($proprety == 'price')
                {
                    if(!isset($gratuit[$index]))
                    {
                        if(isset($rabais[$index]))
                        {
                            $total += $product->$proprety - ( $product->$proprety * ($rabais[$index]/100) );
                        }
                        else
                        {
                            $total += $product->$proprety;
                        }
                    }
                }
                else
                {
                    $total += $product->$proprety;
                }
            }
        }

        return $total;
    }

    public function removeEmpty($items)
    {
        foreach($items as $key => $value)
        {
            if(is_null($value) || $value == '')
                unset($items[$key]);
        }

        return $items;
    }

    public function productIdFromForm($commande)
    {
        $qty     = $commande['qty'];
        $gratuit = (isset($commande['gratuit']) ? array_filter($commande['gratuit']) : []);
        $rabais  = (isset($commande['rabais']) ?  $this->removeEmpty($commande['rabais']) : []);

        foreach($commande['products'] as $index => $product)
        {
            $product_new = (isset($gratuit[$index]) ? [$product => ['isFree' => 1]] : [$product => ['isFree' => null]]);

            if(isset($gratuit[$index]))
            {
                $product_new[$product]['isFree'] = (isset($gratuit[$index]) ? 1 : null);
            }

            if(isset($rabais[$index]))
            {
                $product_new[$product]['rabais'] = (isset($rabais[$index]) ? $rabais[$index] : null);
            }

            if($qty[$index] > 1)
            {
                for($x = 0; $x < $qty[$index]; $x++)
                {
                    $ids[] = $product_new;
                }
            }
            else
            {
                $ids[] = $product_new;
            }
        }

        return $ids;
    }

    public function insertOrder($commande){

        // Order global
        $order = $this->order->create($commande);
        // Adjust Qty
        $this->worker->resetQty($order,'-');

        if(!$order)
        {
            throw new \App\Exceptions\OrderCreationException('Problème lors de la commande');
        }

        return $order;
    }

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

    public function resetQty($order,$operator)
    {
        $products = $this->getCountProducts($order);

        if(!empty($products))
        {
            foreach($products as $product_id => $qty)
            {
                $product = $this->product->find($product_id);

                switch($operator)
                {
                    case "+":
                        $result = $product->sku + $qty;
                        break;

                    case "-";
                        $result = $product->sku - $qty;
                        break;
                }

                $product->sku = $result;
                $product->save();
            }
        }
    }

}