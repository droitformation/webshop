<?php

namespace Tests\Unit\shop;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class CartWorkerTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $shipping;
    protected $coupon;
    protected $product;
    protected $abo;

    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->shipping = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');

        $this->coupon = \Mockery::mock('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->app->instance('App\Droit\Shop\Coupon\Repo\CouponInterface', $this->coupon);

        $this->product = \Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product);

        $this->abo = \Mockery::mock('App\Droit\Abo\Repo\AboInterface');
        $this->app->instance('App\Droit\Abo\Repo\AboInterface', $this->abo);

        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();

        \Session::forget('noShipping');

        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testGetWeight()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 2000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 2000]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 155))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 25))->associate('App\Droit\Shop\Product\Entities\Product');

        $result = $worker->getTotalWeight();

        $this->assertEquals(180, $result->orderWeight);
    }

    /**
     * @return void
     */
    public function testApplyCouponForProduct()
    {
        $worker  = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 2000]);
        $coupon  = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create();

        $coupon->products()->attach($product);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, 'Uno', 1, '20' , array('weight' => 1000))
            ->associate('App\Droit\Shop\Product\Entities\Product'); // price 20

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

        $coupon  = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
                'value'      => 10, // 10% coupon
                'type'       => 'global',
                'title'      => 'test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()
            ]
        );

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 2000]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, 'Uno', 1, '30' , array('weight' => 1000))->associate('App\Droit\Shop\Product\Entities\Product');

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
        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3000]);

        $coupon  = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->make([
                'value'      => 10, // 10% coupon
                'type'       => 'global',
                'title'      => 'test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()
            ]
        );

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, 'Uno', 1, '30' , array('weight' => 1000))->associate('App\Droit\Shop\Product\Entities\Product');

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

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1200]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3400]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 155))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 25))->associate('App\Droit\Shop\Product\Entities\Product');

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => '0',
            'type'       => 'shipping',
            'title'      => 'freeshipping',
            'expire_at'  => \Carbon\Carbon::now()->addDay()
        ]);

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->getTotalWeight();

        $worker->setCoupon($coupon->title)->applyCoupon();
        $worker->getTotalWeight()->setShipping();

        $this->assertEquals(0, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRate()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1200]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3400]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 155))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 25))->associate('App\Droit\Shop\Product\Entities\Product');

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

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1200]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3400]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4400]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 800))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product3->id, 'Duo', 1, '44' , array('weight' => 800))->associate('App\Droit\Shop\Product\Entities\Product');

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

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1200]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3400]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4400]);
        $product4 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4400]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 1500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 1500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product3->id, 'tres', 1, '44' , array('weight' => 2000))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product4->id, 'cuatro', 1, '44' , array('weight' => 5000))->associate('App\Droit\Shop\Product\Entities\Product');

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

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1200]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3400]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4400]);
        $product4 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4400]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 5500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 5500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product3->id, 'tres', 1, '44' , array('weight' => 4000))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product4->id, 'cuatro', 1, '44' , array('weight' => 5000))->associate('App\Droit\Shop\Product\Entities\Product');

        $worker->getTotalWeight()->setShipping();

        $this->assertEquals(1900, $worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateEvenBigger()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');


        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1200]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 3400]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4400]);
        $product4 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4300]);
        $product5 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 4200]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '12' , array('weight' => 5500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product2->id, 'Duo', 1, '34' , array('weight' => 5500))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product3->id, 'tres', 1, '44' , array('weight' => 4000))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product4->id, 'cuatro', 1, '43' , array('weight' => 5000))->associate('App\Droit\Shop\Product\Entities\Product');
        \Cart::instance('shop')->add($product5->id, 'cinquo', 1, '42' , array('weight' => 10000))->associate('App\Droit\Shop\Product\Entities\Product');

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

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '1000' , array('weight' => 500))->associate('App\Droit\Shop\Product\Entities\Product');

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create();

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title);

        $this->assertEquals($worker->hasCoupon->id, $coupon->id);

    }

    /**
     * @expectedException \App\Exceptions\CouponException
     */
    public function testTrySetFalseCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['price' => 1000]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product1->id, 'Uno', 1, '1000' , array('weight' => 500))->associate('App\Droit\Shop\Product\Entities\Product');

        $coupon  = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->make([
                'value'      => 10, // 10% coupon
                'type'       => 'global',
                'title'      => 'test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()
            ]
        );

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn(false);

        $worker->setCoupon($coupon->title);

        $errors = $this->app['session.store']->all();

        $this->assertEquals('Ce rabais n\'est pas valide', $errors['wrongCoupon']);
    }

    /**
     * @return void
     */
    public function testSearchItem()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        $product  = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'  => 'Titre',
            'price'  => '1200',
            'weight' => '2000'
        ]);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, $product->title, 1, $product->price, ['weight' => $product->weight])
            ->associate('App\Droit\Shop\Product\Entities\Product');

        $found = $worker->searchItem($product->id);

        $this->assertEquals($found->first()->rowId, \Cart::instance('shop')->content()->first()->rowId);

    }

    /**
     * @return void
     */
    public function testCalculPriceWithCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'  => 'Titre',
            'price'  => 1000,
            'weight' => '2000'
        ]);

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'     => '20',
            'type'      => 'product',
            'title'     => 'second',
            'expire_at' => \Carbon\Carbon::now()->addDay()
        ]);

        $coupon->products()->attach($product->id);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, $product->title, 1, $product->price, ['weight' => $product->weight]);

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title)->applyCoupon();

        $price = $worker->calculPriceWithCoupon($product,'percent');

        // Product price => 10.00
        // Coupon for product value 20%

        $this->assertEquals(8.00, $price);
    }

    /**
     * @return void
     */
    public function testCalculPriceWithPriceCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product  = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'  => 'Titre',
            'price'  => 5000,
            'weight' => '2000'
        ]);

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => '20',
            'type'       => 'price',
            'title'      => 'Price minus',
            'expire_at' => \Carbon\Carbon::now()->addDay()
        ]);

        $coupon->products()->attach($product->id);

        \Cart::instance('shop')->add($product->id, $product->title, 1, $product->price_cents, ['weight' => $product->weight]);

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title)->applyCoupon();
        $worker->calculPriceWithCoupon($coupon,'minus');

        // Product price => 50.00
        // Coupon for product value -20

        $this->assertEquals(30, \Cart::instance('shop')->total());

    }

    public function testCalculPriceWithPriceShippingCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'  => 'Titre',
            'price'  => 5000,
            'weight' => '2000'
        ]);

        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => null,
            'type'       => 'priceshipping',
            'title'      => 'Price shipping',
            'expire_at'  => \Carbon\Carbon::now()->addDay()
        ]);

        \Cart::instance('shop')->add($product->id, $product->title, 1, $product->price_cents, ['weight' => $product->weight]);

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title)->applyCoupon();

        $this->withSession(['noShipping' => 'noShipping']);
        $this->assertEquals(50.00, $worker->totalCartWithShipping());
    }

    /**
     * @return void
     */
    public function testCalculPriceWithSecondCoupon()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');
        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => 10, // 10% coupon
            'type'       => 'product',
            'title'      => 'test',
            'expire_at'  => \Carbon\Carbon::now()->addDay()
        ]);

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'  => 'Titre',
            'price'  => 1000,
            'weight' => '2000'
        ]);

        $coupon->products()->attach($product->id);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, 'Dos', 1, '10.00', ['weight' => 600]);

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title)->applyCoupon();

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
        $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => 10, // 10% coupon
            'type'       => 'product',
            'title'      => 'test',
            'expire_at'  => \Carbon\Carbon::now()->addDay()
        ]);

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'  => 'Titre',
            'price'  => 1000,
            'weight' => '2000'
        ]);

        $coupon->products()->attach($product->id);

        \Cart::instance('shop');
        \Cart::instance('shop')->add($product->id, 'Dos', 1, '10.00', ['weight' => 600]);

        $this->coupon->shouldReceive('findByTitle')->once()->andReturn($coupon);

        $worker->setCoupon($coupon->title)->applyCoupon();

        // Product price => 10.00
        // Coupon for product value 10%
        $this->assertEquals(9, \Cart::total());

        // Add free shipping later for example via admin
        $this->withSession(['noShipping']);
        $worker->setShipping();

        $this->assertEquals(0, $worker->orderShipping->price);
    }
}
