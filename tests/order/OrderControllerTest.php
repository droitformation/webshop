<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderControllerTest extends TestCase {

    use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
        parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testFilterSendOrderss()
	{

		// Prepare and create some orders
		$make   = new \tests\factories\ObjectFactory();
		
		$pending = $make->order(5);
		$send    = $make->order(3);

		// set 3 with date sent
		$make->updateOrder($send, ['column' => 'send_at', 'date' => '2016-09-10']);

        $this->visit(url('admin/orders'))->see('Commandes');

		// filter to get all send orders
		$response = $this->call('POST', url('admin/orders'), ['start' => '2016-09-01', 'end' => '2016-10-30', 'send' => 'send']);

		$content = $response->getOriginalContent();
		$content = $content->getData();

		$result = $content['orders'];

		$this->assertEquals(3, $result->count());

		// filter to get non sent orders
        $response = $this->call('POST', url('admin/orders'), ['start' => '2016-09-01', 'end' => '2016-10-30', 'send' => 'pending']);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals(5, $result->count());
	}

	/**
	 * @return void
	 */
	public function testFilterPayedOrderss()
	{
		// create some orders
		$make   = new \tests\factories\ObjectFactory();

		$payed   = $make->order(5);
		$pending = $make->order(3);

		// set 5 to payed status
		$make->updateOrder($payed, ['column' => 'payed_at', 'date' => '2016-09-10']);

		$this->visit(url('admin/orders'))->see('Commandes');

		$response = $this->call('POST', url('admin/orders'), ['start' => '2016-09-01', 'end' => '2016-10-30', 'status' => 'payed']);

		$content = $response->getOriginalContent();
		$content = $content->getData();

		$result = $content['orders'];

		$this->assertEquals(5, $result->count());

		$response = $this->call('POST', url('admin/orders'), ['start' => '2016-09-01', 'end' => '2016-10-30', 'status' => 'pending']);

		$content = $response->getOriginalContent();
		$content = $content->getData();

		$result = $content['orders'];

		$this->assertEquals(3, $result->count());
	}


	public function testUpdateOrderWithCoupon()
	{
		$admin = factory(App\Droit\User\Entities\User::class,'admin')->create();
		$admin->roles()->attach(1);
		$this->actingAs($admin);

		$make  = new \tests\factories\ObjectFactory();
		$user = $make->user();

		$ordermaker = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');

		// Prepare and create some orders
		$make = new \tests\factories\ObjectFactory();

		$products     = $make->product(2);
		$inital_total = $products->sum('price');

		$order = factory(App\Droit\Shop\Order\Entities\Order::class)->create([
			'user_id'     => $user->id,
			'order_no'    => '2016-00020003',
			'amount'      => $inital_total,
			'coupon_id'   => null,
			'shipping_id' => 1,
			'payement_id' => 1,
		]);

		$coupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'priceshipping')->create([
			'value' => 10, // -10
			'title' => 'PHP',
		]);

		$order->products()->saveMany($products);
		$coupon->products()->saveMany($products);

		$total = $products->map(function ($product, $key) use ($coupon) {
			return ($product->price_normal - $coupon->value) * 100;
		})->sum();

		$this->visit(url('admin/order/'.$order->id))->see($order->order_no);

		$data = [
			'id' => $order->id,
			'shipping_id' => 1,
			'coupon_id'   => $coupon->id
		];

		$this->call('PUT', url('admin/order/'.$order->id), $data);

		$this->visit(url('admin/order/'.$order->id))->see($order->order_no);

		$content = $this->response->getOriginalContent();
		$content = $content->getData();
		$result  = $content['order'];

		$this->assertEquals(6, $result->shipping_id);
		$this->assertEquals($total, $result->amount);

	}
}
