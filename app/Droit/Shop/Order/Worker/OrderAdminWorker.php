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

class OrderAdminWorker implements OrderAdminWorkerInterface{

    protected $order;
    protected $cart;
    protected $worker;
    protected $user;
    protected $generator;
    protected $product;
    protected $shipping;

    public function __construct(OrderInterface $order, CartWorkerInterface $worker, UserInterface $user, CartInterface $cart, PdfGeneratorInterface $generator, ProductInterface $product, ShippingInterface $shipping)
    {
        $this->order     = $order;
        $this->cart      = $cart;
        $this->worker    = $worker;
        $this->user      = $user;
        $this->product   = $product;
        $this->shipping  = $shipping;
        $this->generator = $generator;
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
        $weight   = (isset($commande['order']['free']) ? null : $weight);
        $shipping = $this->shipping->getShipping($weight);

        $data['shipping_id'] = $shipping->id;
        $data['products']    = $this->productIdFromForm($commande['order']);
        $data['amount']      = $this->total($commande['order']);
        $data['order_no']    = $this->order->newOrderNumber();

        $tva = array_filter($commande['order']['tva']);

        if(!empty($tva))
        {
            $data['tva'] = $tva;
        }

        return $data;
    }

    public function make($data)
    {
        $prepared = $this->prepare($data);

        $commande = [
            'user_id'     => $prepared['user_id'],
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

        // Create invoice for order
        $this->generator->factureOrder($order->id);

        return $order;
    }

    public function total($commande, $proprety = 'price')
    {
        $total    = 0;
        $qty      = array_filter($commande['qty']);

        $gratuit  = (isset($commande['gratuit']) ? array_filter($commande['gratuit']) : []);
        $rabais   = (isset($commande['rabais']) ? array_filter($commande['rabais']) : []);

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

    public function productIdFromForm($commande)
    {
        $qty     = $commande['qty'];
        $gratuit = (isset($commande['gratuit']) ? array_filter($commande['gratuit']) : []);

        foreach($commande['products'] as $index => $product)
        {
            $product = (isset($gratuit[$index]) ? [$product => ['isFree' => 1]] : [$product => ['isFree' => null]]);

            if($qty[$index] > 1)
            {
                for($x = 0; $x < $qty[$index]; $x++)
                {
                    $ids[] = $product;
                }
            }
            else
            {
                $ids[] = $product;
            }
        }

        return $ids;
    }

    public function insertOrder($commande){

        // Order global
        $order = $this->order->create($commande);

        if(!$order)
        {
            throw new \App\Exceptions\OrderCreationException('Problème lors de la commande');
        }

        return $order;
    }

}