<?php

namespace App\Droit\Shop\Order\Worker;

use App\Droit\Shop\Order\Worker\OrderWorkerInterface;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Cart\Repo\CartInterface;
use App\Droit\User\Repo\UserInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use App\Jobs\CreateOrderInvoice;

class OrderWorker implements OrderWorkerInterface{

    use DispatchesJobs;

    protected $order;
    protected $cart;
    protected $worker;
    protected $user;
    protected $generator;
    protected $product;

    public function __construct(OrderInterface $order, CartWorkerInterface $worker, UserInterface $user, CartInterface $cart, PdfGeneratorInterface $generator)
    {
        $this->order     = $order;
        $this->cart      = $cart;
        $this->worker    = $worker;
        $this->user      = $user;
        $this->generator = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function make($shipping,$coupon = null, $admin = null)
    {
        $user = $this->user->find(\Auth::user()->id);

        $commande = [
            'user_id'     => $user->id,
            'order_no'    => $this->order->newOrderNumber(),
            'amount'      =>  \Cart::total() * 100,
            'coupon_id'   => ($coupon ? $coupon['id'] : null),
            'shipping_id' => $shipping->id,
            'payement_id' => 1,
            'products'    => $this->productIdFromCart()
        ];

        // Order global
        $order = $this->insertOrder($commande);

        // Create invoice for order
        $job = (new CreateOrderInvoice($order));
        $this->dispatch($job);

        return $order;

    }

    public function insertOrder($commande){

        // Order global
        $order = $this->order->create($commande);

        if(!$order)
        {
            // Save the cart
            $this->saveCart($commande);

            \Log::error('Problème lors de la commande'. serialize($commande));

            throw new \App\Exceptions\OrderCreationException('Problème lors de la commande');
        }

        return $order;
    }

    public function saveCart($commande)
    {
        // Save the cart
        $this->cart->create([
            'user_id'     => $commande['user_id'],
            'cart'        => serialize(\Cart::content()),
            'coupon_id'   => $commande['coupon_id']
        ]);
    }

    public function productIdFromCart()
    {
        foreach(\Cart::content() as $product)
        {
            if($product->qty > 1)
            {
                for($x = 0; $x < $product->qty; $x++)
                {
                    $ids[] = $product->id;
                }
            }
            else
            {
                $ids[] = $product->id;
            }
        }

        return $ids;
    }

}