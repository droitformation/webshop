<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CouponTest extends TestCase {

    use WithoutMiddleware;

    protected $coupon;
    protected $product;

    public function setUp()
    {
        parent::setUp();

        $this->product = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->product);

        $this->coupon = Mockery::mock('App\Droit\Shop\Coupon\Repo\CouponInterface');
        $this->app->instance('App\Droit\Shop\Coupon\Repo\CouponInterface', $this->coupon);

        $this->helper = Mockery::mock('App\Droit\Helper\Helper');

        $user = App\Droit\User\Entities\User::find(710);

        $this->actingAs($user);

    }

	/**
	 * @return void
	 */
	public function testCouponList()
	{
        $coupon1 = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();
        $coupon2 = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'two')->make();

        $coupons = new \Illuminate\Support\Collection([$coupon1,$coupon2]);

        $this->coupon->shouldReceive('getAll')->once()->andReturn($coupons);

        $this->visit('admin/coupon');
        $this->assertViewHas('coupons');
	}

    /**
     * @return void
     */
    public function testCouponShow()
    {
        $coupon  = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();
        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->make();

        $products = new \Illuminate\Support\Collection([$product]);

        $this->coupon->shouldReceive('find')->once()->andReturn($coupon);
        $this->product->shouldReceive('getAll')->once()->andReturn($products);

        $this->visit('admin/coupon/100');
        $this->assertViewHas('coupon');
        $this->assertViewHas('products');
    }

    /**
     * @return void
     */
    public function testCouponCreate()
    {
        $product  = factory(App\Droit\Shop\Product\Entities\Product::class)->make();
        $products = new \Illuminate\Support\Collection([$product]);

        $this->product->shouldReceive('getAll')->once()->andReturn($products);

        $this->visit('admin/coupon/create');
        $this->assertViewHas('products');
    }

    /**
     * @return void
     */
    public function testCouponStore()
    {
        $coupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class,'one')->make();

        $this->coupon->shouldReceive('create')->once()->andReturn($coupon);

        $response = $this->call('POST', 'admin/coupon', ['value' => '20', 'type' => 'general', 'title' => 'second', 'expire_at' => \Carbon\Carbon::now()->addDay(2)->toDateString()]);

        $this->assertRedirectedTo('admin/coupon');
    }

    /**
     * @return void
     */
    public function testCouponDelete()
    {
        $this->coupon->shouldReceive('delete')->once();

        $response = $this->call('DELETE','admin/coupon/100', [] ,['id' => '100']);

        $this->assertRedirectedTo('admin/coupon');

    }
}
