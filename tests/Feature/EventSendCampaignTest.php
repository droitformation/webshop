<?php

namespace Tests\Feature;

use App\Events\CampaignSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;
use Tests\ResetTbl;
use Illuminate\Support\Facades\Queue;

class EventSendCampaignTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $mock;
    protected $worker;
    protected $mailjet;
    protected $campagne;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->mock = \Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->mailjet = \Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->mailjet);

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->worker);

        $this->campagne = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

        $this->user = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->user);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendCampaignListener()
    {
        Queue::fake();

        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id]);
        $html       = '<html><body>Test</body></html>';
        $recipients = [['Email' => 'cindy.leschaud@gmail.com', 'Name'  => ""]];

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn($html);
        $this->user->shouldReceive('getByNewsletterAndDomain')->once()->andReturn(collect([$user]));

        $event = new \App\Events\CampaignSent($campagne->newsletter_id,$campagne->id);

        // Assert a specific type of job was dispatched meeting the given truth test...

        $listener = new \App\Listeners\SendAtUnine();
        $listener->handle($event);

        Queue::assertPushed(\App\Jobs\SendCampaign::class, function ($job) use ($campagne, $recipients) {
            return $job->campagne->id === $campagne->id && count($recipients) == 1;
        });
    }
}
