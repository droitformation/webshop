<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PubdroitTest extends DuskTestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->artisan("db:seed");
    }

    /**
     * Go to Homepage and see title Prochains événements
     * @group pubdroit
     */
    public function testSeeHomePage()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->browse(function (Browser $browser) {
            $browser->visit('/pubdroit')->assertSee('Prochains événements');
        });
    }

    /**
     * Go to Hpubdroit and browse colloques and shop
     * First colloque go and login
     * Second colloque can't register date is passed
     * @group pubdroit
     */
    public function testBrowseTheShopAndColloques()
    {
        $this->browse(function (Browser $browser) {

            $make = new \tests\factories\ObjectFactory();

            $colloque1 = $make->colloque();
            $colloque2 = $make->colloque();

            $date = \Carbon\Carbon::now()->subDays(2)->toDateString();

            $colloque2->registration_at = $date;
            $colloque2->save();

            $product1  = $make->makeProduct([]);
            $product2  = $make->makeProduct([]);

            $person   = $make->makeUser();

            $browser->visit('/pubdroit')->assertSee('Prochains événements');
            $browser->assertSee($product1->title);
            $browser->assertSee($product2->title);

            $browser->visit('/pubdroit/colloque')->assertSee('Colloques');
            $browser->assertSee($colloque1->titre);
            $browser->assertSee($colloque2->titre);

            // Not logged in see login form
            $browser->visit('/pubdroit/colloque/'.$colloque1->id)
                ->assertSee($colloque1->titre)
                ->assertSee('Je n\'ai pas encore de compte');

            // Login we don't see login form
            $browser->logout()->loginAs($person)->visit('/pubdroit/colloque/'.$colloque1->id)
                ->assertDontSee('Je n\'ai pas encore de compte');

            // Registred date is passed
            $browser->visit('/pubdroit/colloque/'.$colloque2->id)
                ->assertSee('Les inscriptions sont terminées');

            // The user is already registered
            $inscription = $make->makeInscriptionForUser($person,$date);

            $browser->visit('/pubdroit/colloque/'.$inscription->colloque->id)
                ->assertSee('Vous êtes déjà inscrit à ce colloque.');
        });
    }

    /**
     * Buy a book
     * @group pubdroit
     */
    public function testShopForAProduct()
    {
        $this->browse(function (Browser $browser) {

            \Mail::fake();
            \Queue::fake();

            $make     = new \tests\factories\ObjectFactory();
            $colloque = $make->colloque();
            $product  = $make->makeProduct([]);
            $person   = $make->makeUser();

            $browser->logout();

            $browser->visit('/pubdroit')->assertSee('Prochains événements');
            $browser->visit('/pubdroit/product/'.$product->id)->assertSee($product->title)->click('#btn_add_cart');
            $browser->assertSee('1 article '.$product->price_cents.' CHF');

            $browser->visit('/pubdroit/checkout/cart');
            $browser->assertSee('Login');

            $browser->type('email', $person->email)
                ->type('password', 'secret')
                ->pause(1100)
                ->press('Envoyer');

            $browser->visit('/pubdroit/checkout/cart')
                ->assertSee('Panier')
                ->assertSee($product->title);

            $browser->click('#btn-next-checkout')->assertSee('Livraison');
            $browser->click('#btn-next-confirm')->assertSee('Résumé de votre commande');
            $browser->check('#termsAndConditions')->click('#btn-invoice');

            // See if the reponse is in the database
            $this->assertDatabaseHas('shop_orders', [
                'user_id' => $person->id
            ]);

            $browser->visit('pubdroit/profil/orders')
                ->waitFor('.text-info-order')
                ->click('.text-info-order');

            $browser->pause('200');

            $browser->assertSee($product->title);

        });
    }

    /**
     * Buy a book without adresse
     * @group buy2
     */
    public function testShopForAProductWithoutAnAddress()
    {
        $this->browse(function (Browser $browser) {

            $make     = new \tests\factories\ObjectFactory();
            $colloque = $make->colloque();
            $product  = $make->makeProduct([]);
            $person   = $make->makeUser();

            $person->adresses()->delete();
            $person->fresh();

            $browser->logout();

            $browser->visit('/pubdroit')->assertSee('Prochains événements');
            $browser->visit('/pubdroit/product/'.$product->id)->assertSee($product->title)->click('#btn_add_cart');
            $browser->assertSee('1 article '.$product->price_cents.' CHF');

            $browser->visit('/pubdroit/checkout/cart');
            $browser->assertSee('Login');

            $browser->type('email', $person->email)
                ->type('password', 'secret')
                ->pause(1100)
                ->press('Envoyer');

            $browser->pause(4000);// pause for alert
            $browser->assertSee('Ajouter une adresse de livraison');
            $browser->click('#btn-addAdress')->waitForText('Nouvelle adresse');

            $browser->type('first_name', 'Jane')
                ->type('last_name', 'Doe')
                ->type('company', 'DoeCompany')
                ->type('npa', '1233')
                ->type('adresse', 'Rue du test 123')
                ->type('ville', 'Bienne')
                ->select('civilite_id', 2)
                ->select('canton_id', 2)
                ->click('#btn-save-adresse');

            $browser->visit('/pubdroit/checkout/cart')
                ->assertSee('Panier')
                ->assertSee($product->title);

            $browser->click('#btn-next-checkout')->assertSee('Livraison');
            $browser->click('#btn-next-confirm')->assertSee('Résumé de votre commande');

        });
    }
}
