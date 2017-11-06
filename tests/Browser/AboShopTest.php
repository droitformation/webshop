<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboShopTest extends DuskTestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
    }

    /**
     * Buy an abo in the shop
     * @group abos
     */
    public function testShopForAnAbo()
    {
        $this->browse(function (Browser $browser) {

            \Mail::fake();
            \Queue::fake();

            $make     = new \tests\factories\ObjectFactory();
            $person   = $make->makeUser();
            $abo      = $make->makeAbo();

            $browser->logout();

            $browser->visit('/pubdroit')->assertSee($abo->title)->click('#addAbo_'.$abo->id);
            $browser->visit('/pubdroit/checkout/cart')->assertSee('Login');

            // Login
            $browser->type('email', $person->email)->type('password', 'secret')->pause(1100)->press('Envoyer');

            $browser->visit('/pubdroit/checkout/cart')
                ->assertSee('Panier')
                ->assertSee('Demande d\'abonnement '.$abo->title);

            $browser->click('#btn-next-checkout')->assertSee('Livraison');

            $browser->click('#btn-next-confirm')
                ->assertSee('Résumé de votre commande')
                ->assertSee('Demande d\'abonnement '.$abo->title);

            $browser->check('#termsAndConditions')->click('#btn-invoice');

            // See if the reponse is in the database
            $this->assertDatabaseHas('abo_users', [
                'user_id' => $person->id,
                'abo_id'  => $abo->id
            ]);

            // Abos is in the profil
            $browser->visit('pubdroit/profil/abos')->assertSee($abo->title);

            // add abo again
            $browser->visit('/pubdroit')->assertSee($abo->title)->click('#addAbo_'.$abo->id);

            $id = $abo->id;
            $inCart = \Cart::instance('abonnement')->search(function ($cartItem, $rowId) use ($id) {
                return $cartItem->id == $id;
            });

            $this->assertTrue($inCart->isEmpty());

        });
    }
}
