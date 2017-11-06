<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends DuskTestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
    }

    /**
     * Register as new user
     * @group register
     */
    public function testRegisterUser()
    {
        $this->browse(function (Browser $browser) {

            $browser->logout()->visit('register')
                ->type('email', 'jane.doe@unine.ch')
                ->type('password', 'secret123')
                ->type('password_confirmation', 'secret123')
                ->type('first_name', 'Jane')
                ->type('last_name', 'Doe')
                ->type('company', 'DoeCompany')
                ->type('npa', '1233')
                ->type('adresse', 'Rue du test 123')
                ->type('ville', 'Bienne')
                ->select('civilite_id', 2)
                ->select('canton_id', 2);

            $browser->pause(3100); // Wait because of the honeypot :|
            $browser->press('Envoyer');

            $browser->visit('/pubdroit/profil');
            $browser->assertSee('Jane');
            $browser->assertSee('Doe');

        });
    }

    /**
     * Login as user
     * Go to profil and see user name
     * @group profil
     */
    public function testProfilUser()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'email'    => 'cindy.leschaud@unine.ch',
            'password' => bcrypt('cindy2')
        ]);

        $make     = new \tests\factories\ObjectFactory();
        $adresse  = $make->adresse($user);

        $this->browse(function (Browser $browser) use ($adresse,$user) {

            $browser->logout()->visit('login')
                ->type('email', 'cindy.leschaud@unine.ch')
                ->type('password', 'cindy2')
                ->pause(1100)
                ->press('Envoyer');

            $browser->visit('/pubdroit/profil');
            $browser->assertSee($user->first_name);
            $browser->assertSee($user->last_name);
        });
    }

    /**
     * Login, go to profile
     * Update first name
     *
     * @group profil
     */
    public function testUpdateAccountProfilUser()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'email'    => 'cindy.leschaud@unine.ch',
            'password' => bcrypt('cindy2')
        ]);

        $this->browse(function (Browser $browser) use ($user) {

            $browser->logout()->loginAs($user)->visit('/pubdroit/profil');
            $browser->assertSee('Profil');

            $browser->type('first_name','Tototo');
            $browser->press('#saveAccount');

            $browser->assertSee('Tototo');
        });
    }

    /**
     * Login go to user orders pages and see 1 order n°
     * @group buy
     */
    public function testProfilCommandesUser()
    {
        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $orders   = $make->order(3, $person->id);
        $product  = $make->makeProduct([]);

        $this->browse(function (Browser $browser) use ($person,$orders,$product) {

            $browser->logout()->loginAs($person)->visit('/pubdroit/profil/orders');
            $browser->assertSee('Commandes');
            $browser->assertSee($orders->first()->order_no);

        });
    }

    /**
     * Login as user, browse in shop and buy 1 product
     * See product title in user orders page
     * @group buy
     */
    public function testPassNewOrder()
    {
        \Mail::fake();
        \Queue::fake();

        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $product  = $make->makeProduct([]);

        $this->browse(function (Browser $browser) use ($person,$product) {

            $browser->logout()->loginAs($person);
            $browser->visit('pubdroit/product/'.$product->id)->click('#btn_add_cart');

            $browser->visit('pubdroit/checkout/resume')
                ->check('#termsAndConditions')
                ->click('#btn-invoice');

            $browser->visit('pubdroit/profil/orders')
                ->waitFor('.text-info-order')
                ->click('.text-info-order');

            $browser->pause('200');

            $browser->assertSee($product->title);

        });
    }

    /**
     * Login as user, browse to colloques
     * Register for 1 colloque
     * Go to user inscriptions page and see colloque title
     * @group buy
     */
    public function testInscriptionColloque()
    {
        \Mail::fake();
        \Queue::fake();

        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $this->browse(function (Browser $browser) use ($person,$colloque) {

            $prices = $colloque->prices->pluck('id')->all();
            $browser->logout()->loginAs($person)->visit('/pubdroit/colloque/'.$colloque->id);

            $selector = '#label_price_'.$prices[0];
            $browser->click($selector);
            $browser->press('ENVOYER');

            $browser->visit('/pubdroit/profil/colloques');
            $browser->assertSee($colloque->titre);

        });
    }
}
