<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartAboWorkerTest extends TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();

        DB::beginTransaction();
    }

    public function tearDown()
    {
        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();

        \Session::forget('noShipping');

        DB::rollBack();
        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testGetPriceWithProductAndAbo()
    {
        $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorkerInterface');

        \Cart::instance('shop');
        \Cart::instance('abonnement');

        $make = new \tests\factories\ObjectFactory();

        //make abo and user
        $abo  = $make->makeAbo();
        $user = $make->makeUser();

        // add abo for the user
        $abonnement = $make->makeAbonnement($abo,$user);
        $make->abonnementFacture($abonnement);

        $product = $make->product();

        // login user
        $this->actingAs($user);

        // Shipping => 10.00
        \Cart::instance('shop')
            ->associate('Product','App\Droit\Shop\Product\Entities')
            ->add($product->id, $product->title, 1, 50.00 , array('image' => 'youhou.jpg','weight' => 200));

        \Cart::instance('abonnement')
            ->associate('Abo','App\Droit\Abo\Entities')
            ->add($abo->id, $abo->title, 1, 150.00 , [
                'image'          => 'oho.jpg',
                'plan'           => $abo->plan_fr,
                'product_id'     => $abo->current_product->id,
                'product'        => $abo->current_product->title,
                'shipping_cents' => 15.00
            ]);

        $price    = $worker->totalCart();
        $count    = $worker->countCart();
        $shipping = $worker->totalShipping();

        $this->assertEquals(200.00, $price);
        $this->assertEquals(2, $count);
        $this->assertEquals(25.00, $shipping);

        // Make and apply a free shipping coupon
        $this->withSession(['noShipping' => 'noShipping']);

        // test the price of shipping
        $shipping = $worker->totalShipping();
        $this->assertEquals(0, $shipping);
        
    }
}
