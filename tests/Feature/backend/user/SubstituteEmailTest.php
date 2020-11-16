<?php

namespace Tests\Feature\backend\user;

use Tests\TestCase;
use Tests\ResetTbl;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubstituteEmailTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testDoNotIncludeSubstitudeEmailInSondageListContact()
    {
        $worker   = new \App\Droit\Sondage\Worker\SondageWorker();
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = factory(\App\Droit\User\Entities\User::class)->create([
            'email' => '34rsw0anowewTwe@publications-droit.ch',
            'username' => 'cindy@leschaud.ch',
        ]);

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'type'    => 1,
            'email'   => '34rsw0anowewTwe@publications-droit.ch',
            'user_id' => $person->id
        ]);

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id'     => $person->id,
            'colloque_id' => $colloque->id
        ]);

        $emails = $worker->getEmails($colloque->id);

        $this->assertTrue(!in_array('34rsw0anowewTwe@publications-droit.ch',$emails));
        $this->assertTrue(in_array('cindy@leschaud.ch',$emails));

    }

    public function testExportRemoveSubstitude()
    {
        $adresse1 = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1, 'email' => '34rsw0anowewTwe@publications-droit.ch']);
        $adresse2 = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1, 'email' => 'cindy.leschaud@gmail.com']);

        $export = new \App\Exports\AdresseExport(collect([$adresse1,$adresse2]));

        $prepared = $export->prepareAdresse(collect([$adresse1,$adresse2]));

        $this->assertEquals(2,count($prepared));
        $this->assertTrue(!in_array('34rsw0anowewTwe@publications-droit.ch',\Arr::flatten($prepared)));
        $this->assertTrue(in_array('cindy.leschaud@gmail.com',\Arr::flatten($prepared)));
    }

    public function testCantSendInscriptionToSubstitudeEmail()
    {
        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $worker->sendEmail($inscription, '34rsw0anowewTwe@publications-droit.ch');
        $this->assertTrue(true);
    }
}
