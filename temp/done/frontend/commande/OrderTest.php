<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends BrowserKitTest {

    use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

        DB::beginTransaction();
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
        parent::tearDown();
	}

	public function testOrderInShop()
	{
		// Login
		$user = factory(App\Droit\User\Entities\User::class)->create();
		$this->actingAs($user);

		// Make a product
		$make    = new \tests\factories\ObjectFactory();
		$product = $make->product();

		// Visite the page of the product and add in cart
		$this->visit('pubdroit/product/'.$product->id)
			->see($product->title)
			->press('Ajouter au panier');

		// Test if the product is in the cart
		$id =  $product->id;
		$inCart = \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
			return $cartItem->id == (int)$id;
		});

		$this->assertTrue(!$inCart->isEmpty());

		$this->visit('pubdroit/checkout/cart')->see($product->title);
	}

	public function testAddAndEmptyBasket()
	{
		// Login
		$user = factory(App\Droit\User\Entities\User::class)->create();
		$this->actingAs($user);

		// Make a product
		$make    = new \tests\factories\ObjectFactory();
		$product = $make->product();

		// Visite the page of the product and add in cart
		$this->visit('pubdroit/product/'.$product->id)
			->see($product->title)
			->press('Ajouter au panier');

		// Test if the product is in the cart
		$id =  $product->id;
		$inCart = \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
			return $cartItem->id == (int)$id;
		});

		$this->assertTrue(!$inCart->isEmpty());

		$this->visit('pubdroit/checkout/cart')->see($product->title);

        // Change qty
        $this->call('POST', 'pubdroit/cart/quantityProduct', ['rowId' => $inCart->first()->rowId, 'qty' => 2]);

        $inCart = \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == (int)$id;
        });

        $this->assertEquals(2, $inCart->first()->qty);

        // Remove Product from basket
        $this->call('POST', 'pubdroit/cart/removeProduct', ['rowId' => $inCart->first()->rowId]);

		$this->assertTrue(\Cart::instance('shop')->content()->isEmpty());
	}
}
