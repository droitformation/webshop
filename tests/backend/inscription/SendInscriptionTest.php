<?php

use Laravel\BrowserKitTesting\DatabaseTransactions;
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
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $this->visit('admin/user/'.$inscription->user_id);

        $response = $this->call('POST', 'admin/inscription/send', ['id' => 0, 'model' => 'inscription', 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertRedirectedTo('admin/user/'.$inscription->user_id);

        $this->expectExceptionMessage('Aucune inscription ou groupe trouvé!');
    }
}
