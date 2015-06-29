<?php

class CartWorkerTest extends TestCase {

    protected $worker;
    protected $coupon;
    protected $product;
    protected $onecoupon;
    protected $twocoupon;
    protected $oneproduct;

    public function setUp()
    {
        parent::setUp();

        $this->worker  = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');
        $this->coupon  = \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->product = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');

        $tomorrow = \Carbon\Carbon::now()->addDay();

        $this->oneproduct =  $this->product->create([
            'title'           => 'Test product',
            'teaser'          => 'test',
            'image'           => 'test.jpg',
            'description'     => 'test' ,
            'weight'          => 900,
            'sku'             => 1,
            'price'           => 1000,
            'is_downloadable' => 0,
            'hidden'          => 0,
        ]);
        
        $this->onecoupon  =  $this->coupon->create(['value' => '10', 'title' => 'test', 'product_id' => null, 'expire_at' => $tomorrow ]);
        $this->twocoupon  =  $this->coupon->create(['value' => '20', 'title' => 'second', 'product_id' => $this->oneproduct->id, 'expire_at' => $tomorrow ]);

    }

    public function tearDown()
    {
        $this->coupon->delete($this->onecoupon->id);
        $this->coupon->delete($this->twocoupon->id);

        $product = new App\Droit\Shop\Product\Entities\Product();

        $product->find($this->oneproduct->id)->forceDelete();

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

        $this->worker->getTotalWeight();

        $this->worker->noShipping()->setShipping();

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
    public function testApplyCoupon()
    {
        \Cart::instance('newInstance');

        \Cart::add($this->oneproduct->id, $this->oneproduct->title, 1, $this->oneproduct->price , array('weight' => $this->oneproduct->weight));
        \Cart::add(1, 'un titre', 1, '2', array('weight' => '300'));

        $this->worker->setCoupon($this->twocoupon->title)->applyCoupon();

        $this->assertEquals(10, \Cart::total());
    }

    /**
     * @return void
     */
    public function testCalculPriceWithCoupon()
    {
        \Cart::instance('newInstance');

        \Cart::add($this->oneproduct->id, $this->oneproduct->title, 1, $this->oneproduct->price , array('weight' => $this->oneproduct->weight));

        $price = $this->worker->setCoupon($this->twocoupon->title)->calculPriceWithCoupon();

        $this->assertEquals(8.00, $price);
    }

    /**
     * @return void
     */
    public function testCalculPriceWithSecondCoupon()
    {
        \Cart::instance('newInstance');

        \Cart::add(1, 'Uno', 1, '10.00' , array('weight' => 500));
        \Cart::add(2, 'Dos', 1, '15.00' , array('weight' => 600));

        $this->worker->setCoupon($this->onecoupon->title)->applyCoupon();

        $this->assertEquals(22.5, \Cart::total());

    }
}
