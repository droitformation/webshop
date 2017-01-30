<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;

class CouponControllerTest extends BrowserKitTest {

    use DatabaseTransactions;
    
    protected $coupon;
    protected $product;

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
    
	/**
	 * @return void
	 */
	public function testCouponList()
	{
        $this->visit('admin/coupon');
        $this->assertViewHas('coupons');
	}

    /**
     * @return void
     */
    public function testCouponCreate()
    {
        $this->visit('admin/coupon')->click('addCoupon');
        $this->seePageIs('admin/coupon/create');

        $this->type('1000', 'value')
            ->select('global', 'type')
            ->type('Test', 'title')
            ->type(\Carbon\Carbon::now()->addDay()->toDateString(), 'expire_at')
            ->press('Créer un coupon');

        $this->seeInDatabase('shop_coupons', [
            'value'      => '1000',
            'type'       => 'global',
            'title'      => 'Test',
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);
    }

    public function testCouponPricCreate()
    {
        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'           => 'Test product',
            'teaser'          => 'One test product',
            'image'           => 'test.jpg',
            'description'     => 'Lorem ipsum dolor amet' ,
            'weight'          => 900,
            'sku'             => 10,
            'price'           => 1000,
        ]);

        $this->visit('admin/coupon')->click('addCoupon');
        $this->seePageIs('admin/coupon/create');

        $this->type('1000', 'value')
            ->select('price', 'type')
            ->select($product->id, 'product_id[]')
            ->type('Test', 'title')
            ->type(\Carbon\Carbon::now()->addDay()->toDateString(), 'expire_at')
            ->press('Créer un coupon');

        $this->seeInDatabase('shop_coupons', [
            'value'      => '1000',
            'type'       => 'price',
            'title'      => 'Test',
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);
    }

    /**
     * @return void
     */
    public function testCouponUpdate()
    {
        $coupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => '20',
            'type'       => 'price',
            'title'      => 'Price minus',
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);

        $this->visit('admin/coupon/'.$coupon->id);

        $this->select('global', 'type')->press('Envoyer');

        $this->seeInDatabase('shop_coupons', [
            'id'         => $coupon->id,
            'value'      => $coupon->value,
            'type'       => 'global',
            'title'      => $coupon->title,
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);
    }

    /**
     * @return void
     */
    public function testCouponDelete()
    {
        $coupon = factory(App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
            'value'      => '20',
            'type'       => 'price',
            'title'      => 'Price minus',
            'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
        ]);

        $response = $this->call('DELETE','admin/coupon/'.$coupon->id);

        $this->assertRedirectedTo('admin/coupon');

        $this->notSeeInDatabase('shop_coupons', [
            'id' => $coupon->id,
        ]);

    }
}
