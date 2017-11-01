<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCouponTest extends DuskTestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @group coupon
     */
    public function testCouponCreate()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('admin/coupon')->click('#addCoupon');

            $browser->type('value','1000')
                ->select('type','global')
                ->type('title','Test')
                ->type('expire_at',\Carbon\Carbon::now()->addDay()->toDateString())
                ->press('Créer un coupon');

            $this->assertDatabaseHas('shop_coupons', [
                'value'      => '1000',
                'type'       => 'global',
                'title'      => 'Test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
            ]);

        });
    }

    /**
     * @group coupon
     */
    public function testCouponPriceCreate()
    {
        $this->browse(function (Browser $browser) {

            $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
                'title'           => 'Test product',
                'teaser'          => 'One test product',
                'image'           => 'test.jpg',
                'description'     => 'Lorem ipsum dolor amet' ,
                'weight'          => 900,
                'sku'             => 10,
                'price'           => 1000,
            ]);

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('admin/coupon')->click('#addCoupon');

            $browser->type( 'value','1000')
                ->select('type','price')
                ->click('.ms-elem-selectable')
                ->type('title','Test')
                ->type('expire_at',\Carbon\Carbon::now()->addDay()->toDateString());

            //$product_id = $product->id;
            //$browser->driver->executeScript('$(\'#multi-select\').multiSelect(\'select\', '.$product_id.');');

            $browser->press('Créer un coupon');

            $this->assertDatabaseHas('shop_coupons', [
                'value'      => '1000',
                'type'       => 'price',
                'title'      => 'Test',
                'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
            ]);

        });
    }

    /**
     * @group coupon
     */
    public function testCouponUpdate()
    {
        $this->browse(function (Browser $browser) {

            $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
                'value'      => '20',
                'type'       => 'price',
                'title'      => 'Price minus',
                'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
            ]);

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('admin/coupon/'.$coupon->id);
            $browser->select('type','global')->press('Envoyer');

            $this->assertDatabaseHas('shop_coupons', [
                'id'         => $coupon->id,
                'value'      => $coupon->value,
                'type'       => 'global',
                'title'      => $coupon->title,
                'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
            ]);

        });
    }

    /**
     * @group coupon
     */
    public function testCouponDelete()
    {
        $this->browse(function (Browser $browser) {

            $coupon = factory(\App\Droit\Shop\Coupon\Entities\Coupon::class)->create([
                'value'      => '20',
                'type'       => 'price',
                'title'      => 'Price minus',
                'expire_at'  => \Carbon\Carbon::now()->addDay()->toDateString()
            ]);

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('admin/coupon');
            $browser->click('#deleteCoupon_'.$coupon->id);
           // $browser->driver->switchTo()->alert()->accept();
            $browser->visit('admin/coupon');

            $this->assertDatabaseMissing('shop_coupons', ['id' => $coupon->id,]);

        });
    }
}
