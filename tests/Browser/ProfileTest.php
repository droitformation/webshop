<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends DuskTestCase
{
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pubdroit')->assertSee('Prochains événements');
        });
    }

    public function testProfilUser()
    {
        \Honeypot::disable();

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'email'    => 'cindy.leschaud@unine.ch',
            'password' => bcrypt('cindy2')
        ]);

        $this->browse(function (Browser $browser) {

            $browser->visit('login')
                ->type('email', 'cindy.leschaud@unine.ch')
                ->type('password', 'cindy2')
                ->press('Envoyer');

            $browser->assertSee('Compte');
        });
    }

    public function testUpdateAccountProfilUser()
    {
        \Honeypot::disable();

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'email'    => 'cindy.leschaud@unine.ch',
            'password' => bcrypt('cindy2')
        ]);

        $this->browse(function (Browser $browser) use ($user) {

            $browser->loginAs($user)->visit('/pubdroit/profil');
            $browser->assertSee('Profil');

            $browser->type('first_name','Tototo');
            $browser->press('#saveAccount');

            $browser->assertSee('Tototo');
        });
    }

    public function testProfilCommandesUser()
    {
        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();
        $orders   = $make->order(3, $person->id);

        $this->browse(function (Browser $browser) use ($person) {

            $browser->loginAs($person)->visit('/pubdroit/profil/orders');
            $browser->assertSee('Commandes');

            $browser->assertSee('Commande n°');
        });
    }

/*    public function testInscriptionColloque()
    {
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $this->browse(function (Browser $browser) use ($person,$colloque) {

            $prices = $colloque->prices->pluck('id')->all();

            $browser->loginAs($person)->visit('/pubdroit/colloque/'.$colloque->id);

            $browser->value('input[name="price_id[]"]', $prices[0]);
            $browser->value('input[name="user_id"]', $person->id);
            $browser->value('input[name="colloque_id"]', $colloque->id);
            $browser->press('ENVOYER');

            $browser->visit('/pubdroit/profil/colloques');
            $browser->assertSee($colloque->titre);

        });
    }*/
}
