<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureSondageTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Create a marketing Sondage in admin
     * See if it's in the list
     */
    public function testCreateSondageMarketing()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $response = $this->withSession(['colloques' => collect([$colloque])]);

        $response = $response->get('admin/sondage/create');
        $response->assertStatus(200);

        $response = $this->post('admin/sondage', [
            'title' => 'Ceci est un titre',
            'description' => 'Ceci est une description',
            'marketing'   => 1,
            'valid_at'    => \Carbon\Carbon::now()->addDay(5)
        ]);

        $response->isRedirect(url('admin/sondage'));

        $response = $this->get('admin/sondage');
        $response->assertSee('Ceci est une description');
    }

    /**
     * Create new avis in admin
     */
    public function testCreateQuestion()
    {
        $response = $this->get('admin/avis/create');
        $response->assertStatus(200);

        $response = $this->post('admin/avis', [
            'type'     => 'question',
            'question' => 'Une nouvelle question',
        ]);

        // See if the reponse is in the database
        $this->assertDatabaseHas('sondage_avis', [
            'type'    => 'question',
            'question' => 'Une nouvelle question'
        ]);
    }

    /**
     * Create a Sondage
     * Go to confirmation page
     * Send sondage to list id provided
     * See if the send job is being pushed to queue
     */
    public function testSendToList()
    {
        \Queue::fake();

        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Create a sondage for the colloque
        $sondage = factory(\App\Droit\Sondage\Entities\Sondage::class)->create([
            'colloque_id' => $colloque->id,
            'valid_at'    => \Carbon\Carbon::now()->addDay(5),
        ]);

        // Create and attach a question to sondage
        $question = factory(\App\Droit\Sondage\Entities\Avis::class)->create(['type' => 'text','question' => 'One question' ,'choices' => null]);
        $sondage->avis()->attach($question->id, ['rang' => 1]);

        // Create list
        $list   = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One liste', 'colloque_id' => $colloque->id]);
        $email1 = factory(\App\Droit\Newsletter\Entities\Newsletter_emails::class)->create(['email' => 'contact@domain.com']);
        $email2 = factory(\App\Droit\Newsletter\Entities\Newsletter_emails::class)->create(['email' => 'info@domain.com']);

        $list->emails()->save($email1);
        $list->emails()->save($email2);

        $response = $this->get('admin/sondage');
        $response->assertStatus(200);
        $response->assertViewHas('sondages');

        $response = $this->get('admin/sondage/confirmation/'.$sondage->id);

        $response->assertViewHas('listes')->assertSeeText($colloque->titre);

        $response = $this->post('admin/sondage/send', ['list_id' => $list->id, 'sondage_id' => $sondage->id]);

        \Queue::assertPushed(\App\Jobs\SendSondage::class, function ($job) use ($sondage,$email1) {
            return $job->sondage->id === $sondage->id && $email1->email === $job->data['email'];
        });

        \Queue::assertPushed(\App\Jobs\SendSondage::class, function ($job) use ($sondage,$email2) {
            return $job->sondage->id === $sondage->id && $email2->email === $job->data['email'];
        });
    }

    /**
     * Create Sondage
     * Send to the email provided
     */
    public function testSendTestEmail()
    {
        \Queue::fake();

        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Create a sondage for the colloque
        $sondage = factory(\App\Droit\Sondage\Entities\Sondage::class)->create([
            'colloque_id' => $colloque->id,
            'valid_at'    => \Carbon\Carbon::now()->addDay(5),
        ]);

        $email = 'info@domain.ch';

        // Create and attach a question to sondage
        $question = factory(\App\Droit\Sondage\Entities\Avis::class)->create(['type' => 'text','question' => 'One question' ,'choices' => null]);
        $sondage->avis()->attach($question->id, ['rang' => 1]);

        // filter to get all send orders
        $response = $this->post('admin/sondage/send', ['sondage_id' => $sondage->id, 'email' => $email]);

        $response->assertRedirect('admin/sondage');

        \Queue::assertPushed(\App\Jobs\SendSondage::class, function ($job) use ($sondage,$email) {
            return $job->sondage->id === $sondage->id && $email === $job->data['email'];
        });
    }

    /**
     * Create Sondage without avis
     * Send to the email provided but we are redirected with a warning
     */
    public function testMissingAvis()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Create a sondage for the colloque
        $sondage = factory(\App\Droit\Sondage\Entities\Sondage::class)->create([
            'colloque_id' => $colloque->id,
            'valid_at'    => \Carbon\Carbon::now()->addDay(5),
        ]);

        $email = 'info@domain.ch';

        $response = $this->post('admin/sondage/send', ['sondage_id' => $sondage->id, 'email' => $email]);

        $this->assertSame('warning', $this->app->session->get('alert.style'));
        $this->assertSame('Aucun sondage trouvé ou aucune question dans ce sondage!', $this->app->session->get('alert.message'));
    }
}
