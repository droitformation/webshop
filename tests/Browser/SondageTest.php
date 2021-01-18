<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SondageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    /**
     * Answer a test sondage
     * We can go back because it's a test sondage
     * @group avis
     */
    public function testAnswerSondageIsTest()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Create a sondage for the colloque
        $sondage = factory(\App\Droit\Sondage\Entities\Sondage::class)->create([
            'colloque_id' => $colloque->id,
            'valid_at'    => \Carbon\Carbon::now()->addDay(5),
        ]);

        // Create and attach a questioin to sondage
        $question = factory(\App\Droit\Sondage\Entities\Avis::class)->create(['type' => 'checkbox','question' => 'One question' ,'choices' => null]);
        $sondage->avis()->attach($question->id, ['rang' => 1]);

        // Make the token with the infos
        $token = base64_encode(json_encode([
            'sondage_id' => $sondage->id,
            'email'      => 'droitformation.web@gmail.com',
            'isTest'     => 1,
        ]));

        $this->withSession(['sondage' => $sondage, 'email' => 'droitformation.web@gmail.com', 'isTest' => 1]);

        $this->browse(function (Browser $browser) use ($token,$sondage,$question) {
            $browser->visit('reponse/create/'.$token)->assertSee('Ceci est un sondage test');
            $browser->check('input[name="reponses['.$question->id.']"]', 'Ceci est une réponse');
            $browser->press('Envoyer le sondage');
            $browser->assertSee('Merci pour votre participation au sondage!');

            // See if the reponse is in the database
            $this->assertDatabaseHas('sondage_reponses', [
                'sondage_id' => $sondage->id,
                'email'      => 'droitformation.web@gmail.com',
                'isTest'     => 1
            ]);

            $browser->visit('reponse/create/'.$token)
                ->assertSee('Ceci est un sondage test');

        });
    }

    /**
     * Answer a normal sondage but get warning if back because we have already an answer in the db
     * @group avis
     */
    public function testAnswerSondageNormal()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Create a sondage for the colloque
        $sondage = factory(\App\Droit\Sondage\Entities\Sondage::class)->create([
            'colloque_id' => $colloque->id,
            'valid_at'    => \Carbon\Carbon::now()->addDay(5),
        ]);

        // Create and attach a questioin to sondage
        $question = factory(\App\Droit\Sondage\Entities\Avis::class)->create(['type' => 'checkbox','question' => 'One question' ,'choices' => null]);
        $sondage->avis()->attach($question->id, ['rang' => 1]);

        // Make the token with the infos
        $token = base64_encode(json_encode([
            'sondage_id' => $sondage->id,
            'email'      => 'droitformation.web@gmail.com',
            'isTest'     => null,
        ]));

        $this->withSession(['sondage' => $sondage, 'email' => 'droitformation.web@gmail.com', 'isTest' => 1]);

        $this->browse(function (Browser $browser) use ($token,$sondage,$question) {
            $browser->visit('reponse/create/'.$token);
            $browser->check('input[name="reponses['.$question->id.']"]', 'Ceci est une réponse');
            $browser->press('Envoyer le sondage');
            $browser->assertSee('Merci pour votre participation au sondage!');

            // See if the reponse is in the database
            $this->assertDatabaseHas('sondage_reponses', [
                'sondage_id' => $sondage->id,
                'email'      => 'droitformation.web@gmail.com',
                'isTest'     => null
            ]);

            $browser->visit('reponse/create/'.$token);
            // Return see the sondage, but it's already done so redirect to reponse page with message
            $browser->visit('reponse/create/'.$token)
                ->assertSee('Vous avez déjà répondu au sondage!');

        });
    }

}
