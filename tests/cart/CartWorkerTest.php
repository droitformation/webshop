<?php

class CartWorkerTest extends TestCase {
    
    protected $shipping;
    protected $coupon;
    protected $product;
    protected $abo;
    protected $worker;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->shipping = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');
        
        $this->coupon = Mockery::mock('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->app->instance('App\Droit\Shop\Coupon\Repo\CouponInterface', $this->coupon);

        $this->product = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product);

        $this->abo = Mockery::mock('App\Droit\Abo\Repo\AboInterface');
        $this->app->instance('App\Droit\Abo\Repo\AboInterface', $this->abo);

        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();
        
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

	/**
	 * @return void
	 */
	public function testGetWeight()
	{
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        
        \Cart::instance('shop');
        \Cart::instance('shop')->add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::instance('shop')->add(56, 'Duo', 1, '34' , array('weight' => 25));

		$result = $worker->getTotalWeight();

        $this->assertEquals(180, $result->orderWeight);
	}

    /**
     * @return void
     */
    public function testApplyCouponForProduct()
    {
        $worker  = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        
        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 11 , 'price' => 2000]);
        $coupon  = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make([
                'id'         => 1,
                'value'      => 10, // 10% coupon
                'type'       => 'product',
                'title'      => 'test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()
            ]
        );
        
        $coupon->setRelation('products', new Illuminate\Database\Eloquent\Collection([$product]));
        
        \Cart::instance('shop');
        \Cart::instance('shop')->add(11, 'Uno', 1, '20' , array('weight' => 1000)); // price 20

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);
        
        $worker->setCoupon($coupon->title)->applyCoupon();
        
        $total = \Cart::instance('shop')->total();

        // Total 20 - (20*0.1) = 18
        $this->assertEquals(18, $total);
    }

    /**
     * @return void
     */
    public function testApplyCouponForCart()
    {
        $worker  = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $coupon  = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make([
                'id'         => 1,
                'value'      => 10, // 10% coupon
                'type'       => 'global',
                'title'      => 'test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()
            ]
        );

        \Cart::instance('shop');
        \Cart::instance('shop')->add(11, 'Uno', 1, '30' , array('weight' => 1000)); // price 20

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title)->applyCoupon();

        $total = \Cart::instance('shop')->total();

        // Total 30 - (30*0.1) = 27
        $this->assertEquals(27, $total);
    }


    /**
     * @return void
     */
    public function testApplyCouponAndResetCart()
    {
        $worker  = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['id' => 11 , 'price' => 3000]);

        $coupon  = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make([
                'id'         => 1,
                'value'      => 10, // 10% coupon
                'type'       => 'global',
                'title'      => 'test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()
            ]
        );

        \Cart::instance('shop');
        \Cart::instance('shop')->add(11, 'Uno', 1, '30' , array('weight' => 1000)); // price 20

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);
        $this->product->shouldReceive('find')->once()->andReturn($product);

        $worker->setCoupon($coupon->title)->applyCoupon();

        $total = \Cart::instance('shop')->total();

        // Total 30 - (30*0.1) = 27
        $this->assertEquals(27, $total);
        
        // Reste cart prices
        $worker->resetCartPrices();

        $total = \Cart::instance('shop')->total();

        $this->assertEquals(30, $total);
    }

    /**
     * @return void
     */
    public function testGetFreeShipping()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::instance('shop')->add(56, 'Duo', 1, '34' , array('weight' => 25));

        $oneproduct  = factory(App\Droit\Shop\Product\Entities\Product::class)->make();
        $threecoupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'three')->make();

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($threecoupon);

        $worker->getTotalWeight();

        $worker->setCoupon($threecoupon->title)->applyCoupon();
        $worker->getTotalWeight()->setShipping();

        $this->assertEquals(0, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRate()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::instance('shop')->add(56, 'Duo', 1, '34' , array('weight' => 25));

        $worker->getTotalWeight();
        $worker->setShipping();

        $this->assertEquals(1000, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateBigger()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(55, 'Uno', 1, '12' , array('weight' => 500));
        \Cart::instance('shop')->add(56, 'Duo', 1, '34' , array('weight' => 800));
        \Cart::instance('shop')->add(58, 'Duo', 1, '44' , array('weight' => 800));

        $worker->getTotalWeight();
        $worker->setShipping();

        $this->assertEquals(1100, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateVeryBig()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(65, 'Uno', 1, '12' , array('weight' => 1500));
        \Cart::instance('shop')->add(76, 'Duo', 1, '34' , array('weight' => 1500));
        \Cart::instance('shop')->add(88, 'tres', 1, '44' , array('weight' => 2000));
        \Cart::instance('shop')->add(89, 'cuatro', 1, '44' , array('weight' => 5000));

        $worker->getTotalWeight();
        $worker->setShipping();

        $this->assertEquals(1400, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateVeryBigger()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(65, 'Uno', 1, '12' , array('weight' => 5500));
        \Cart::instance('shop')->add(76, 'Duo', 1, '34' , array('weight' => 5500));
        \Cart::instance('shop')->add(88, 'tres', 1, '44' , array('weight' => 4000));
        \Cart::instance('shop')->add(89, 'cuatro', 1, '44' , array('weight' => 5000));

        $worker->getTotalWeight()->setShipping();

        $this->assertEquals(1900, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateEvenBigger()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(65, 'Uno', 1, '12' , array('weight' => 5500));
        \Cart::instance('shop')->add(76, 'Duo', 1, '34' , array('weight' => 5500));
        \Cart::instance('shop')->add(88, 'tres', 1, '44' , array('weight' => 4000));
        \Cart::instance('shop')->add(89, 'cuatro', 1, '43' , array('weight' => 5000));
        \Cart::instance('shop')->add(90, 'cinquo', 1, '42' , array('weight' => 10000));

        $worker->getTotalWeight();
        $worker->setShipping();

        $this->assertEquals(2600, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testSetCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(1, 'Uno', 1, '1000' , array('weight' => 500));

        $onecoupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($onecoupon);

        $worker->setCoupon($onecoupon->title);

        $this->assertEquals($worker->hasCoupon->id, $onecoupon->id);

    }

    /**
     * @expectedException \App\Exceptions\CouponException
     */
    public function testTrySetFalseCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('shop')->add(1, 'Uno', 1, '1000' , array('weight' => 500));

        $onecoupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn(false);

        $worker->setCoupon($onecoupon->title);
    }

    /**
     * @return void
     */
    public function testSearchItem()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        $oneproduct  = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'id' => 1,
            'title' => 'Titre',
            'price' => '1200',
            'weight' => '2000'
        ]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($oneproduct->id, $oneproduct->title, 1, $oneproduct->price , array('weight' => $oneproduct->weight));

        $found = $worker->searchItem($oneproduct->id);

        $this->assertEquals($found[0], \Cart::instance('shop')->content()->first()->rowid);

    }

    /**
     * @return void
     */
    public function testCalculPriceWithCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $oneproduct  = factory(App\Droit\Shop\Product\Entities\Product::class)->make([
            'id' => 1,
            'title'  => 'Titre',
            'price'  => 1000,
            'weight' => '2000'
        ]);
        $twocoupon   = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'two')->make();
        
        \Cart::instance('shop');
        \Cart::instance('shop')->add($oneproduct->id, $oneproduct->title, 1, $oneproduct->price , array('weight' => $oneproduct->weight));

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($twocoupon);

        $worker->setCoupon($twocoupon->title)->applyCoupon();

        $price = $worker->calculPriceWithCoupon($oneproduct);

        // Product price => 10.00
        // Coupon for product value 20%

        $this->assertEquals(8.00, $price);
    }

    /**
     * @return void
     */
    public function testCalculPriceWithSecondCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        $onecoupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();
        
        \Cart::instance('shop');

        // Has to match the factory product
        \Cart::instance('shop')->add(100, 'Dos', 1, '10.00' , array('weight' => 600));

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($onecoupon);

        $worker->setCoupon($onecoupon->title)->applyCoupon();

        // Product price => 10.00
        // Coupon for product value 10%

        $this->assertEquals(9, \Cart::instance('shop')->total());

    }

    /**
     * @return void
     */
    public function testCalculPriceWithFirstAndSecondCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        $onecoupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();
        
        \Cart::instance('shop');

        // Has to match the factory product
        \Cart::instance('shop')->add(100, 'Dos', 1, '10.00' , array('weight' => 600));

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($onecoupon);

        $worker->setCoupon($onecoupon->title)->applyCoupon();

        // Product price => 10.00
        // Coupon for product value 10%

        $this->assertEquals(9, \Cart::total());

        // Add free shipping later for example via admin
        $this->withSession(['noShipping']);
        $worker->setShipping();

        $this->assertEquals(0, $worker->orderShipping->price);

    }

    /**
     * @return void
     */
    public function testGetPriceWithProductAndAbo()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('abonnement');

        // Has to match the factory product
        \Cart::instance('shop')->add(100, 'Dos', 1, '10.00', array('weight' => 600));
        \Cart::instance('abonnement')->add(2, 'Abo', 1, '100.00', array('image' => 'logo.png'));

        $price = $worker->totalCart();
        $count = $worker->countCart();

        $this->assertEquals(110.00, $price);
        $this->assertEquals(2, $count);

    }
}
