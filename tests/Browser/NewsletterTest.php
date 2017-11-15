<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NewsletterTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @group newsletter
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {

            $site       = factory(\App\Droit\Site\Entities\Site::class)->create();

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('pubdroit');
            // Honeypot
            $browser->driver->executeScript('$(\'#honey_pot\').val(\'something\');');
            $browser->type('email','cindy.leschaud@gmail.com')
                ->press('Inscription');

            $browser->assertSee('404');

            $browser->visit('pubdroit')->type('email','cindy.leschaud@gmail.com')->press('Inscription');
            // Ok
            $browser->assertSee('Veuillez confirmer votre adresse email en cliquant le lien qui vous a été envoyé par email');

        });
    }
}