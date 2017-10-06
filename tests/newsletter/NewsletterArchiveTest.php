<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsletterArchiveTest extends BrowserKitTest
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->mailjet = Mockery::mock('App\Droit\Newsletter\Worker\SendgridInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\SendgridInterface', $this->mailjet);

    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testArchiveCampagneNewsletter()
    {
        $site         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $campagne     = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
            'sujet'           => 'Sujet',
            'auteurs'         => 'Cindy Leschaud',
            'status'          => 'brouillon',
            'newsletter_id'   => $newsletter->id,
            'api_campagne_id' => 1,
            'send_at'         => null,
        ]);

        $response = $this->call('POST', 'build/campagne/archive', ['id' => $campagne->id]);

        $this->assertRedirectedTo('build/newsletter');

        $this->seeInDatabase('newsletter_campagnes', [
            'id'      => $campagne->id,
            'status'  => 'envoyé',
            'send_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

    }
}
