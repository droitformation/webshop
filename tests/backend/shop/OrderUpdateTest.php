<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderUpdateTest extends BrowserKitTest {

    use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

        DB::beginTransaction();

		$user = factory(App\Droit\User\Entities\User::class)->create();
		$user->roles()->attach(1);
		$this->actingAs($user);
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
        parent::tearDown();
	}
    
    public function testOrderUpdate()
    {
        $repo = App::make('App\Droit\Shop\Order\Repo\OrderInterface');

        $make = new \tests\factories\ObjectFactory();

        $orders = $make->order(1);
        $order  = $orders->first();

        $pivots = $order->products->map(function ($product, $key) {
            return [
                'id'     => $product->pivot->product_id,
                'isFree' => $product->pivot->isFree,
                'rabais' => $product->pivot->rabais,
                'price'  => 2000,
            ];
        })->toArray();

        $data = [
            'id'          => $order->id,
            'user_id'     => $order->user_id,
            'order_no'    => $order->order_no,
            'amount'      => 4000,
            'coupon_id'   => null,
            'shipping_id' => $order->shipping_id,
            'payement_id' => 1,
            'products'    => $pivots
        ];

        $result = $repo->update($data);

        $newpivots = $result->products->map(function ($product, $key) {
            return [
                'id'     => $product->pivot->product_id,
                'isFree' => $product->pivot->isFree,
                'rabais' => $product->pivot->rabais,
                'price'  => $product->pivot->price,
            ];
        })->toArray();

        $this->assertEquals($newpivots, $pivots);
        $this->assertEquals($result->amount, 4000);

    }

    public function testReCalculateProducts()
    {
        // Dependencies
        $ordermaker = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $make  = new \tests\factories\ObjectFactory();
        $money = new \App\Droit\Shop\Product\Entities\Money;

        // Create on order
        $orders = $make->order(1);
        $order  = $orders->first();

        $pivots = $order->products->map(function ($product, $key) {
            return [
                'id'     => $product->pivot->product_id,
                'price'  => $product->price,
                'isFree' => $product->pivot->isFree,
                'rabais' => $product->pivot->rabais,
            ];
        });

        $products = $ordermaker->updateProducts($order); // no coupon

        $this->assertEquals($products, $pivots);
    }

    public function testReCalculateProductGlobalCoupon()
    {
        // Dependencies
        $ordermaker = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $make  = new \tests\factories\ObjectFactory();

        // Create on order
        $orders = $make->order(1);
        $order  = $orders->first();

        // Create coupon
        $coupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => '20', // percentage
            'type'       => 'global',
            'title'      => 'Price percent',
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);

        $order_total = $order->products->sum('price');
        $new_total   = $this->percent($order_total, ($coupon->value / 100)) ; // calculate with coupon

        $products = $ordermaker->updateProducts($order, $coupon); // coupon global percent

        $products_total = $products->map(function ($item, $key) {
            return $item['price'];
        })->sum();

        $products_total = number_format($products_total, 2, '.', '');

        $this->assertEquals($products_total, $new_total);
    }

    public function testPrepareOrderDataFromAdmin()
    {
        // Dependencies
        $ordermaker = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');
        $make  = new \tests\factories\ObjectFactory();

        // Create on order
        $orders = $make->order(1);
        $order  = $orders->first();

        $total  = $order->products->sum('price');

        $coupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => '20', // percentage
            'type'       => 'global',
            'title'      => 'Price percent',
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);

        $pivots = $order->products->map(function ($product, $key) use ($coupon) {
            return [
                'id'     => $product->pivot->product_id,
                'price'  => $this->percent($product->price, ($coupon->value / 100)),
                'isFree' => $product->pivot->isFree,
                'rabais' => $product->pivot->rabais,
            ];
        });

        $products_total = $pivots->map(function ($item, $key) {
            return $item['price'];
        })->sum();

        $data = [
            'id'          => $order->id,
            'created_at'  => $order->created_at->format('Y-m-d'),
            'coupon_id'   => $coupon->id,
            'shipping_id' => $order->shipping_id,
            'amount'      => number_format($products_total, 0, '.', ''),
            'products'    => $pivots->toArray()
        ];

        $result = $ordermaker->updateOrder($order, $order->shipping_id, $coupon);

        $this->assertEquals($data,$result);
    }

    function percent($total, $percentage)
    {
        $new = $total - ($total * $percentage);
        return number_format($new, 2, '.', '');
    }

}
