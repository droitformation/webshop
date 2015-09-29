<?php

class CartWorkerTest extends TestCase {

    protected $mock;
    protected $product_mock;
    protected $worker;
    protected $coupon;
    protected $product;
    protected $shipping;
    protected $onecoupon;
    protected $twocoupon;
    protected $oneproduct;
    protected $threecoupon;

    public function setUp()
    {
        parent::setUp();

        $this->shipping = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');

        $this->mock = Mockery::mock('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->app->instance('App\Droit\Shop\Coupon\Repo\CouponInterface', $this->mock);

        $this->product_mock = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product_mock);

        $this->worker = new App\Droit\Shop\Cart\Worker\CartWorker(
            $this->product_mock, $this->shipping, $this->mock
        );

        $this->oneproduct  = factory(App\Droit\Shop\Product\Entities\Product::class)->make();
        $this->onecoupon   = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();
        $this->twocoupon   = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'two')->make();
        $this->threecoupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'three')->make();
    }

    public function tearDown()
    {
        \Cart::instance('newInstance')->destroy();
    }

	/**
	 * @return void
	 */
	public function testGetShipping()
	{
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

		$result = $this->worker->getTotalWeight();

        $this->assertEquals(180, $result->orderWeight);
	}

    /**
     * @return void
     */
    public function testGetFreeShipping()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

        $this->mock->shouldReceive('findByTitle')->once()->andReturn($this->threecoupon);
        $this->product_mock->shouldReceive('find')->once()->andReturn($this->oneproduct);

        $this->worker->getTotalWeight();

        $this->worker->setCoupon($this->threecoupon->title)->applyCoupon();
        $this->worker->getTotalWeight()->setShipping();

        $this->assertEquals(0, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRate()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1000, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateBigger()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 500));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 800));
        \Cart::add(58, 'Duo', 1, '44' , array('weight' => 800));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1100, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateVeryBig()
    {
        \Cart::instance('newInstance');

        \Cart::add(65, 'Uno', 1, '12' , array('weight' => 1500));
        \Cart::add(76, 'Duo', 1, '34' , array('weight' => 1500));
        \Cart::add(88, 'tres', 1, '44' , array('weight' => 2000));
        \Cart::add(89, 'cuatro', 1, '44' , array('weight' => 5000));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1400, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateVeryBigger()
    {
        \Cart::instance('newInstance');

        \Cart::add(65, 'Uno', 1, '12' , array('weight' => 5500));
        \Cart::add(76, 'Duo', 1, '34' , array('weight' => 5500));
        \Cart::add(88, 'tres', 1, '44' , array('weight' => 4000));
        \Cart::add(89, 'cuatro', 1, '44' , array('weight' => 5000));

        $this->worker->getTotalWeight()->setShipping();

        $this->assertEquals(1900, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateEvenBigger()
    {
        \Cart::instance('newInstance');

        \Cart::add(65, 'Uno', 1, '12' , array('weight' => 5500));
        \Cart::add(76, 'Duo', 1, '34' , array('weight' => 5500));
        \Cart::add(88, 'tres', 1, '44' , array('weight' => 4000));
        \Cart::add(89, 'cuatro', 1, '43' , array('weight' => 5000));
        \Cart::add(90, 'cinquo', 1, '42' , array('weight' => 10000));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(2600, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testSetCoupon()
    {
        \Cart::instance('newInstance');

        \Cart::add(1, 'Uno', 1, '1000' , array('weight' => 500));

        $this->mock->shouldReceive('findByTitle')->once()->andReturn($this->onecoupon);

        $this->worker->setCoupon($this->onecoupon->title);

        $this->assertEquals($this->worker->hasCoupon->id, $this->onecoupon->id);

    }

    /**
     * @return void
     */
    public function testSearchItem()
    {
        \Cart::instance('newInstance');

        \Cart::add($this->oneproduct->id, $this->oneproduct->title, 1, $this->oneproduct->price , array('weight' => $this->oneproduct->weight));

        $found = $this->worker->searchItem($this->oneproduct->id);

        $this->assertEquals($found[0], \Cart::content()->first()->rowid);

    }

    /**
     * @return void
     */
    public function testCalculPriceWithCoupon()
    {
        \Cart::instance('newInstance');

        \Cart::add($this->oneproduct->id, $this->oneproduct->title, 1, $this->oneproduct->price , array('weight' => $this->oneproduct->weight));

        $this->mock->shouldReceive('findByTitle')->once()->andReturn($this->twocoupon);
        $this->product_mock->shouldReceive('find')->once()->andReturn($this->oneproduct);

        $this->worker->setCoupon($this->twocoupon->title)->applyCoupon();

        $price = $this->worker->calculPriceWithCoupon($this->oneproduct->id);

        // Product price => 10.00
        // Coupon for product value 20%

        $this->assertEquals(8.00, $price);
    }

    /**
     * @return void
     */
    public function testCalculPriceWithSecondCoupon()
    {
        \Cart::instance('newInstance');

        // Has to match the factory product
        \Cart::add(100, 'Dos', 1, '10.00' , array('weight' => 600));

        $this->mock->shouldReceive('findByTitle')->once()->andReturn($this->onecoupon);
        $this->product_mock->shouldReceive('find')->twice()->andReturn($this->oneproduct);

        $this->worker->setCoupon($this->onecoupon->title)->applyCoupon();

        // Product price => 10.00
        // Coupon for product value 10%

        $this->assertEquals(9, \Cart::total());

    }

    /**
     * @return void
     */
    public function testCalculPriceWithFirstAndSecondCoupon()
    {
        \Cart::instance('newInstance');

        // Has to match the factory product
        \Cart::add(100, 'Dos', 1, '10.00' , array('weight' => 600));

        $this->mock->shouldReceive('findByTitle')->once()->andReturn($this->onecoupon);
        $this->product_mock->shouldReceive('find')->twice()->andReturn($this->oneproduct);

        $this->worker->setCoupon($this->onecoupon->title)->applyCoupon();

        // Product price => 10.00
        // Coupon for product value 10%

        $this->assertEquals(9, \Cart::total());

        // Add free shipping later for example via admin

        $this->withSession(['noShipping']);
        $this->worker->setShipping();

        $this->assertEquals(0, $this->worker->orderShipping->price);

    }
}
