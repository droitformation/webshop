<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProductTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Create a product in admin
     * @group product
     */
    public function testCreateProductInAdmin()
    {
        $this->browse(function (Browser $browser) {

            $make     = new \tests\factories\ObjectFactory();
            $colloque = $make->colloque();

            $user = factory(\App\Droit\User\Entities\User::class)->create([
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email'      => 'jane.doe@gmail.com',
                'password'   => bcrypt('jane2')
            ]);

            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('/admin')
                ->visit('admin/products')
                ->click('#addProduct');

            $browser->type('title','Un livre')->type('teaser','Dapibus ante suscipurcusit çunc, primiés?');

            $browser->driver->executeScript('$(\'.redactor\').redactor(\'code.set\', \'Dapibus ante suscipurcusit, primiés?\');');

            $browser->attach('file',public_path('images/avatar.jpg'))
                ->type('weight','300')
                ->type('price','30.00')
                ->type('sku','10')
                ->press('Créer le produit');

            $this->assertDatabaseHas('shop_products', [
                'title'       => 'Un livre',
                'teaser'      => 'Dapibus ante suscipurcusit çunc, primiés?',
                'description' => '<p>Dapibus ante suscipurcusit, primiés?</p>',
                'image'       => 'avatar.jpg',
                'weight'      => '300',
                'price'       => '3000',
                'sku'         => '10',
                'rang'        => '0',
            ]);
        });
    }

    /**
     * Update a product in admin
     * @group product
     */
    public function testUpdateProductInAdmin()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create([
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email'      => 'jane.doe@gmail.com',
                'password'   => bcrypt('jane2')
            ]);

            $user->roles()->attach(1);

            $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
                'title'           => 'Test product',
                'teaser'          => 'One test product',
                'image'           => 'test.jpg',
                'description'     => 'Lorem ipsum dolor amet' ,
                'weight'          => 900,
                'sku'             => 10,
                'price'           => 1000,
            ]);

            $browser->loginAs($user)->visit('admin/product/'.$product->id);
            $browser->pause(1000);
            $browser->type('title','Un livre2')->type('teaser','Dapibus ante çunc, primiés?');
            $browser->driver->executeScript('$(\'.redactor\').redactor(\'code.set\', \'Dapibus suscipurcusit, primiés?\');');
            $browser->attach('file',public_path('images/avatar.jpg'))
                ->type('weight','300')
                ->type('price','40.00');

            $browser->press('Envoyer');

            $this->assertDatabaseHas('shop_products', [
                'title'       => 'Un livre2',
                'teaser'      => 'Dapibus ante çunc, primiés?',
                'description' => '<p>Dapibus suscipurcusit, primiés?</p>',
                'weight'      => '300',
                'price'       => '4000',
                'rang'        => '0',
            ]);
        });
    }

    /**
     * Update a product in admin
     * @group product
     */
    public function testDeleteProductInAdminIfNotInAnyOrders()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create([
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email'      => 'jane.doe@gmail.com',
                'password'   => bcrypt('jane2')
            ]);

            $user->roles()->attach(1);

            $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

            // Make order and attach product
            $make  = new \tests\factories\ObjectFactory();
            $order = $make->order(1);
            $order = $order->first();
            $order->products()->attach($product->id);

            $browser->loginAs($user)->visit('admin/product/'.$product->id)->pause(100);

            // we should see the delete button
            $browser->assertDontSee('Supprimer');

            // Detach the product, we schould see the delete button
            $order->products()->detach($product->id);

            $browser->visit('admin/product/'.$product->id)->pause(100);

            $browser->assertSee('Supprimer');
            $browser->click('#deleteProduct');

            $browser->driver->switchTo()->alert()->accept();
            $browser->visit('admin/products');

            $this->assertDatabaseMissing('shop_products', [
                'id' => $product->id,
                'deleted_at' => null
            ]);

        });
    }

    /**
     * Add attribute
     * @group product_attribut
     */
    public function testProductAddAttribute()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $make      = new \tests\factories\ObjectFactory();
            $product   = $make->makeProduct([]);
            $attribute = factory(\App\Droit\Shop\Attribute\Entities\Attribute::class)->create();

            $browser->loginAs($user)->visit('admin/product/'.$product->id);

            $browser->type('value','new')
                ->select('attribute_id',$attribute->id)
                ->press('#addAttribute');

            $product->load('attributs');

            $this->assertTrue($product->attributs->contains('id',$attribute->id));
        });
    }

    /**
     * Remove attribute
     * @group product_attribut
     */
    public function testProductDeleteAttribute()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $make      = new \tests\factories\ObjectFactory();

            $product   = $make->makeProduct([]);
            $attribute = factory(\App\Droit\Shop\Attribute\Entities\Attribute::class)->create();
            $product->attributs()->attach($attribute->id, ['value' => 'NewAttribute']);

            $browser->loginAs($user)->visit('admin/product/'.$product->id);
            $browser->assertSee('NewAttribute');
            $element = '#deleteAttribute_'.$attribute->id;
            //$browser->driver->executeScript("$('html, body').animate({ scrollTop: $('$element').offset().top }, 0);");
            $browser->pause(2000);
            $browser->press('#deleteAttribute_'.$attribute->id);
            $browser->driver->switchTo()->alert()->accept();

            $browser->visit('admin/product/'.$product->id);
            $product->fresh();
            $product->load('attributs');

            $this->assertFalse($product->attributs->contains('id',$attribute->id));
        });
    }

    /**
     * Remove Label
     * @group product_label
     */
    public function testProductRemoveLabels()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $product   = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();
            $categorie = factory(\App\Droit\Shop\Categorie\Entities\Categorie::class)->create();
            $author    = factory(\App\Droit\Shop\Author\Entities\Author::class)->create();
            $domain    = factory(\App\Droit\Domain\Entities\Domain::class)->create();

            $product->categories()->attach($categorie->id);
            $product->authors()->attach($author->id);
            $product->domains()->attach($domain->id);

            $browser->loginAs($user)->visit('admin/product/'.$product->id);

            $browser->assertSee($categorie->title);
            $browser->assertSee($author->name);
            $browser->assertSee($domain->title);

            $browser->element('#deleteCategories_'.$categorie->id)->getLocationOnScreenOnceScrolledIntoView();
            $browser->press('#deleteCategories_'.$categorie->id);
            $browser->driver->switchTo()->alert()->accept();
            $browser->visit('admin/product/'.$product->id);

            $browser->pause(400);
            $browser->element('#deleteAuthors_'.$author->id)->getLocationOnScreenOnceScrolledIntoView();
            $browser->press('#deleteAuthors_'.$author->id);
            $browser->driver->switchTo()->alert()->accept();
            $browser->visit('admin/product/'.$product->id);

            $browser->pause(400);
            $browser->element('#deleteDomains_'.$domain->id)->getLocationOnScreenOnceScrolledIntoView();
            $browser->press('#deleteDomains_'.$domain->id);
            $browser->driver->switchTo()->alert()->accept();
            $browser->visit('admin/product/'.$product->id);

            $product->fresh();
            $product->load('categories','authors','domains');

            $this->assertTrue(!$product->categories->contains('id',$categorie->id));
            $this->assertTrue(!$product->authors->contains('id',$author->id));
            $this->assertTrue(!$product->domains->contains('id',$domain->id));

        });
    }
}
