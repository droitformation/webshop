<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;
use Illuminate\Support\Facades\Mail;

class SendInscriptionTest extends BrowserKitTest {

    use DatabaseTransactions;
    //use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    /**
     * Send Inscription from admin
     * @return void
     */
    public function testSendInscription()
    {
        Mail::fake();

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $this->visit('admin/user/'.$inscription->user_id);

        $response = $this->call('POST', 'admin/inscription/send', ['id' => $inscription->id, 'model' => 'inscription', 'email' => 'cindy.leschaud@gmail.com']);

        $this->followRedirects()->seePageIs('admin/user/'.$inscription->user_id);

        // Check that an email was sent to this email address
       // $this->seeMessageFor('cindy.leschaud@gmail.com');
       // $this->seeMessageFrom('Publications Droit');
       // $this->seeMessageWithSubject('Confirmation d\'inscription');
    }

    /**
     * Send Group inscription from admin
     * @return void
     */
    public function testSendGroupInscription()
    {
        Mail::fake();

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions;

        $group = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $this->visit('admin/user/'.$group->user_id);

        $response = $this->call('POST', 'admin/inscription/send', ['id' => $group->group_id, 'model' => 'group', 'email' => 'info@leschaud.ch']);

        $this->followRedirects()->seePageIs('admin/user/'.$group->user_id);

        // Check that an email was sent to this email address
       // $this->seeMessageFor('info@leschaud.ch');
       // $this->seeMessageFrom('Publications Droit');
       // $this->seeMessageWithSubject('Confirmation d\'inscription');
    }

    public function testSendFails()
    {
        try {
            $make     = new \tests\factories\ObjectFactory();
            $colloque = $make->makeInscriptions(1);

            $inscription = $colloque->inscriptions->first();

            $this->visit('admin/user/'.$inscription->user_id);

            $response = $this->call('POST', 'admin/inscription/send', ['id' => 0, 'model' => 'inscription', 'email' => 'cindy.leschaud@gmail.com']);

        } catch (Exception $e) {
            $this->assertType('\App\Exceptions\InscriptionExistException', $e);
        }
    }

    public function testUpdateInscriptionAfterSendingKeepConferencesAndOptions()
    {
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $occurence = factory(App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence'
        ]);

        $inscription->occurrences()->attach($occurence->id);
        $inscription->fresh();
        $inscription->load('occurrences');

        $this->assertEquals(1,$inscription->occurrences->count());

        $results = $worker->updateInscription($inscription);

        $updated = $results->first();
        $updated->load('occurrences');

        $this->assertEquals(1,$updated->occurrences->count());

    }
}
