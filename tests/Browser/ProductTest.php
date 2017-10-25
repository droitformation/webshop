<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProductTest extends DuskTestCase
{
    use RefreshDatabase;

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

            $browser->logout()->visit('login')
                ->type('email', $user->email)
                ->type('password', 'jane2')
                ->pause(1100)
                ->press('Envoyer');

            $browser->visit('/admin')
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

            $browser->logout()->visit('login')
                ->type('email', $user->email)
                ->type('password', 'jane2')
                ->pause(1100)
                ->press('Envoyer');

            $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
                'title'           => 'Test product',
                'teaser'          => 'One test product',
                'image'           => 'test.jpg',
                'description'     => 'Lorem ipsum dolor amet' ,
                'weight'          => 900,
                'sku'             => 10,
                'price'           => 1000,
            ]);

            $browser->visit('admin/product/'.$product->id);
            $browser->pause(300);
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

            $browser->logout()->visit('login')->type('email', $user->email)->type('password', 'jane2')->pause(1100)
                ->press('Envoyer');

            $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

            // Make order and attach product
            $make  = new \tests\factories\ObjectFactory();
            $order = $make->order(1);
            $order = $order->first();
            $order->products()->attach($product->id);

            $browser->visit('admin/product/'.$product->id);

            // we should see the delete button
            $browser->assertDontSee('Supprimer');

            // Detach the product, we schould see the delete button
            $order->products()->detach($product->id);

            $browser->visit('admin/product/'.$product->id);

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
}
