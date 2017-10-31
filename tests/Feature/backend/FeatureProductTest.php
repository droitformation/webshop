<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testProductsList()
    {
        $response = $this->get('admin/products');
        $response->assertViewHas('products');
    }

    public function testProductsSearchTerm()
    {
        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

        // filter to get all send orders
        $response = $this->call('POST', url('admin/products'), ['term' => $product->title]);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['products'];

        $this->assertEquals(1, $result->count());
    }

    public function testProductsSearchSort()
    {
        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

        $author = factory(\App\Droit\Shop\Author\Entities\Author::class)->create();

        $product1->authors()->attach($author->id);

        // filter to get all send orders
        $response = $this->call('POST', url('admin/products'), ['sort' => ['author_id' => $author->id]]);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['products'];

        $this->assertEquals(1, $result->count());
    }

    public function testLinkToAboOk()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
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

        // filter to get all send orders
        $response = $this->put('admin/product/'.$product->id, ['id' => $product->id, 'abo_id[]' => $abo->id]);

        $product->fresh();

        $this->assertEquals(1, $product->abos->count());

    }

    /**
     * @expectedException \App\Exceptions\ProductMissingInfoException
     */
    public function testLinkToAboFails()
    {
        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
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

    public function testExternalProductNotInNewOrderList()
    {
        $product   = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['url' => 'http://www.google.ch']);

        $response = $this->get('/admin/order/create');

        $content  = $response->getOriginalContent();
        $content  = $content->getData();
        $products = $content['products'];

        $this->assertFalse($products->contains('id',$product->id));
    }

    public function testProductDeleteAttribute()
    {
        $product   = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();
        $attribute = factory(\App\Droit\Shop\Attribute\Entities\Attribute::class)->create();

        $product->attributs()->attach($attribute->id, ['value' => 'new']);

        $response = $this->delete( url('admin/productattribut/'.$attribute->id), ['product_id' => $product->id]);

        $product->load('attributs');

        $this->assertFalse($product->attributs->contains('id',$attribute->id));
    }

}
