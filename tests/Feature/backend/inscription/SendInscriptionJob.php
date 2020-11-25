<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\ResetTbl;

class SendInscriptionJob extends TestCase
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

    public function testSendInscription()
    {
        \Mail::fake();

        $worker      = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make        = new \tests\factories\ObjectFactory();
        $colloque    = $make->makeInscriptions(1);
        $inscription = $colloque->inscriptions->first();

        $worker->sendEmail($inscription, 'cindy.leschaud@gmail.com');

        \Mail::assertSent(\App\Mail\SendRegisterConfirmation::class);
    /*    \Mail::assertSent(\App\Mail\SendRegisterConfirmation::class, function($mail) {
            return count($mail->attachments) > 3;
        });*/

    }
}
