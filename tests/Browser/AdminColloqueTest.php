<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminColloqueTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $user;

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
     * @group colloque
     */
    public function testCreateAttestationForColloque()
    {
        $this->browse(function (Browser $browser) {

            $make     = new \tests\factories\ObjectFactory();
            $colloque = $make->colloque();

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('admin/attestation/colloque/'.$colloque->id);

            $browser->type('title','Une attestation');
            $browser->type('lieu','Bienne');
            $browser->type('organisateur','DesignPond');
            $browser->type('telephone','078 690 12 34');
            $browser->type('signature','Cindy Leschaud');

            $browser->driver->executeScript('$(\'.redactorSimple\').redactor(\'code.set\', \'<p>Un commentaire</p>\');');

            $browser->click('#addAttestation');

            $this->assertDatabaseHas('colloque_attestations', [
                'colloque_id'  => $colloque->id,
                'telephone'    => '078 690 12 34',
                'lieu'         => 'Bienne',
                'organisateur' => 'DesignPond',
                'title'        => 'Une attestation',
                'signature'    => 'Cindy Leschaud',
                'comment'      => '<p>Un commentaire</p>',
            ]);
        });

    }

    /**
     * @group colloque
     */
    public function testUpdateAttestationForColloque()
    {
        $this->browse(function (Browser $browser) {

            $make        = new \tests\factories\ObjectFactory();
            $colloque    = $make->colloque();
            $attestation = factory(\App\Droit\Colloque\Entities\Colloque_attestation::class)->create(['colloque_id'  => $colloque->id,]);

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('admin/attestation/'.$attestation->id);

            $browser->type('title','Une autre attestation');
            $browser->type('lieu','Neuchâtel');
            $browser->type('organisateur','DesignPond');
            $browser->type('telephone','078 690 12 34');
            $browser->type('signature','Jane Doe');
            $browser->driver->executeScript('$(\'.redactorSimple\').redactor(\'code.set\', \'<p>Un autre commentaire</p>\');');

            $browser->click('#updateAttestation');

            $this->assertDatabaseHas('colloque_attestations', [
                'colloque_id'  => $colloque->id,
                'title'        => 'Une autre attestation',
                'organisateur' => 'DesignPond',
                'lieu'         => 'Neuchâtel',
                'telephone'    => '078 690 12 34',
                'signature'    => 'Jane Doe',
                'comment'      => '<p>Un autre commentaire</p>',
            ]);
        });
    }
}
