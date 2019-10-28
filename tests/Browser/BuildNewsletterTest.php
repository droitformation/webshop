<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuildNewsletterTest extends DuskTestCase
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
     * @group campagne
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('build/newsletter');

            $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create([
                'titre'        => 'New Newsletter',
                'list_id'      => '1',
                'from_name'    => 'Cindy Leschaud',
                'from_email'   => 'cindy.leschaud@gmail.com',
            ]);

            $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
                'sujet'           => 'Sujet 123',
                'auteurs'         => 'Cindy Leschaud',
                'status'          => 'Envoyé',
                'send_at'         => \Carbon\Carbon::createFromDate(2016, 12, 22)->toDateTimeString(),
                'newsletter_id'   => $newsletter->id,
                'api_campagne_id' => 1,
                'created_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
                'updated_at'       => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
            ]);

            $browser->visit('build/newsletter/archive/'.$newsletter->id.'/2016')->pause(4000)
                ->assertSee('Archive')
                ->assertSee('Sujet 123');

            $browser->visit('build/campagne/'.$campagne->id.'/edit')->pause(3000);

            $this->assertDatabaseHas('newsletter_campagnes', [
                'id'    => $campagne->id,
                'sujet' => $campagne->sujet
            ]);

            $browser->type('sujet','Dapibus ante çunc, primiés?')->press('Éditer');
            $browser->visit('build/campagne/'.$campagne->id.'/edit');

            $this->assertDatabaseHas('newsletter_campagnes', [
                'id'    => $campagne->id,
                'sujet' => 'Dapibus ante çunc, primiés?'
            ]);

        });
    }

    /**
     * @group campagne
     */
    public function testCampagneCompose()
    {
        $this->browse(function (Browser $browser) {

            $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create();

            $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
                'sujet'           => 'Sujet',
                'auteurs'         => 'Cindy Leschaud',
                'newsletter_id'   => $newsletter->id,
            ]);

            $content1 = factory(\App\Droit\Newsletter\Entities\Newsletter_contents::class)->create([
                'type_id'  => 6, // text
                'titre'    => 'Lorem ipsum dolor amet',
                'contenu'  => 'Lorem ad quîs j\'libéro pharétra vivamus mounc!',
                'newsletter_campagne_id' => $campagne->id,
            ]);

            $content2 = factory(\App\Droit\Newsletter\Entities\Newsletter_contents::class)->create([
                'type_id'  => 6, // text
                'titre'    => 'Lorem ipsum dolor amet',
                'contenu'  => 'Convallis èiam condimentum lacinia vulputaté ïn metus litora sit vulputaté vélit, consequat liçlà.',
                'newsletter_campagne_id' => $campagne->id,
            ]);

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('build/campagne/'.$campagne->id)
                ->assertSee($content1->titre)
                ->assertSee($content2->titre);
        });

    }

    /**
     * @group campagne
     */
    public function testPastCampagneCannotBeSendForTest()
    {

        $this->browse(function (Browser $browser) {

            $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create();

            $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
                'sujet'           => 'Sujet',
                'auteurs'         => 'Cindy Leschaud',
                'status'          => 'Envoyé',
                'send_at'         => \Carbon\Carbon::createFromDate(2016, 12, 22)->toDateTimeString(),
                'newsletter_id'   => $newsletter->id,
                'api_campagne_id' => 1,
                'created_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
                'updated_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
            ]);

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('build/campagne/'.$campagne->id)
                ->assertDontSee('Envoyer un test');

        });

    }

}
