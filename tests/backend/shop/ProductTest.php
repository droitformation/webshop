<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;

class ProductTest extends BrowserKitTest {

    use DatabaseTransactions;

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
	public function testProductsList()
	{
		$this->visit('admin/products');
		$this->assertViewHas('products');
	}

    public function testProductsSearchTerm()
    {
        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->create();

        $this->visit('admin/products');

        // filter to get all send orders
        $response = $this->call('POST', url('admin/products'), ['term' => $product->title]);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['products'];

        $this->assertEquals(1, $result->count());
    }

    public function testProductsSearchSort()
    {
        $product1 = factory(App\Droit\Shop\Product\Entities\Product::class)->create();
        $product2 = factory(App\Droit\Shop\Product\Entities\Product::class)->create();

        $author = factory(App\Droit\Author\Entities\Author::class)->create();

        $product1->authors()->attach($author->id);

        $this->visit('admin/products');

        // filter to get all send orders
        $response = $this->call('POST', url('admin/products'), ['sort' => ['author_id' => $author->id]]);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['products'];

        $this->assertEquals(1, $result->count());
    }

    public function testCreateProduct()
	{
		$this->visit('admin/products')->click('addProduct');
		$this->seePageIs('admin/product/create');

        $this->type('Un livre', 'title')
            ->type('Dapibus ante suscipurcusit çunc, primiés?', 'teaser')
            ->type('Dapibus ante suscipurcusit, primiés?', 'description')
            ->attach(public_path('images/avatar.jpg'), 'file')
            ->type('300', 'weight')
            ->type('30.00', 'price')
            ->type('10', 'sku')
            ->press('Créer le produit');

        $this->seeInDatabase('shop_products', [
            'title'       => 'Un livre',
            'teaser'      => 'Dapibus ante suscipurcusit çunc, primiés?',
            'description' => 'Dapibus ante suscipurcusit, primiés?',
            'image'       => 'avatar.jpg',
            'weight'      => '300',
            'price'       => '3000',
            'sku'         => '10',
            'rang'        => '0',
        ]);
	}

    public function testUpdateProduct()
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

        $this->visit('admin/product/'.$product->id);

        $this->type('Un livre', 'title')
            ->type('Dapibus ante suscipurcusit çunc, primiés?', 'teaser')
            ->type('300', 'weight')
            ->type('30.00', 'price')
            ->press('Envoyer');

        $this->seeInDatabase('shop_products', [
            'title'       => 'Un livre',
            'teaser'      => 'Dapibus ante suscipurcusit çunc, primiés?',
            'description' => $product->description,
            'image'       => $product->image,
            'weight'      => '300',
            'price'       => '3000',
            'sku'         => '10',
            'rang'        => '0',
        ]);
    }

    public function testDeleteProduct()
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

        // Make order and attach product
        $make  = new \tests\factories\ObjectFactory();
        $order = $make->order(1);
        $order = $order->first();
        $order->products()->attach($product->id);

        $this->visit('admin/product/'.$product->id);
        // we schould see the delete button
        $this->dontSee('Supprimer');

        // Detach the product, we schould see the delete button
        $order->products()->detach($product->id);
        $this->visit('admin/product/'.$product->id);
        $this->see('Supprimer');

        $this->press('deleteProduct');

        $this->notSeeInDatabase('shop_products', [
            'id' => $product->id,
            'deleted_at' => null
        ]);
    }
    
    public function testLinkToAboOk()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'        => 'Test product',
            'teaser'       => 'One test product',
            'image'        => 'test.jpg',
            'description'  => 'Lorem ipsum dolor amet' ,
            'weight'       => 900,
            'sku'          => 10,
            'price'        => 1000,
            'hidden'       => 0,
        ]);

        $make->addAttributesAbo($product);

        $this->visit('admin/product/'.$product->id);
        $this->select($abo->id, 'abo_id[]');
        $this->press('Envoyer');

        $content = $this->response->getOriginalContent();
        $content = $content->getData();
        $product = $content['product'];

        $this->assertEquals(1, $product->abos->count());

    }

    /**
     * @expectedException \App\Exceptions\ProductMissingInfoException
     */
    public function testLinkToAboFails()
    {
        $product = factory(App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'        => 'Test product',
            'teaser'       => 'One test product',
            'image'        => 'test.jpg',
            'description'  => 'Lorem ipsum dolor amet' ,
            'weight'       => 900,
            'sku'          => 10,
            'price'        => 1000,
            'hidden'       => 0,
        ]);

        $validator = new \App\Droit\Shop\Product\Worker\ProductValidation($product);
        $validator->activate();

        $this->expectExceptionMessage('Le livre doit avoir une référence ainsi que l\'édition comme attributs pour devenir un abonnement');
    }

    public function testProductAddAttribute()
    {
        $product   = factory(App\Droit\Shop\Product\Entities\Product::class)->create();
        $attribute = factory(App\Droit\Shop\Attribute\Entities\Attribute::class)->create();

        $this->visit('admin/product/'.$product->id);
        
        $this->type('new', 'value')
           ->select($attribute->id, 'attribute_id')
           ->press('addAttribute');

        $product->load('attributs');
        
        $this->assertTrue($product->attributs->contains('id',$attribute->id));
    }

    public function testProductDeleteAttribute()
    {
        $product   = factory(App\Droit\Shop\Product\Entities\Product::class)->create();
        $attribute = factory(App\Droit\Shop\Attribute\Entities\Attribute::class)->create();

        $product->attributs()->attach($attribute->id, ['value' => 'new']);

        $this->visit('admin/product/'.$product->id);
        $this->press('deleteAttribute_'.$attribute->id);

        $product->load('attributs');

        $this->assertFalse($product->attributs->contains('id',$attribute->id));
    }

}
