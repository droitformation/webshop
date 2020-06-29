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

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testSendCampaignEvent(){

        \Event::fake();

        $this->user = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->user);

        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id]);
        $html       = '<html><body>Test</body></html>';
        $recipients = [['Email' => 'cindy.leschaud@gmail.com', 'Name'  => ""]];

        $result = ['success' => true];

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->campagne->shouldReceive('update');

        $this->worker->shouldReceive('html')->once()->andReturn($html);
        $this->user->shouldReceive('getByNewsletterAndDomain')->andReturn(collect([$user]));

        $this->mailjet->shouldReceive('setList');
        $this->mailjet->shouldReceive('updateSubject')->andReturn($result);
        $this->mailjet->shouldReceive('setHtml')->andReturn($result);
        $this->mailjet->shouldReceive('sendCampagne')->andReturn($result);

        $response = $this->call('POST', 'build/send/campagne', ['id' => $campagne->id]);

        \Event::assertDispatched(\App\Events\CampaignSent::class, function ($e) use ($newsletter,$campagne) {
            return $e->newsletter_id === $newsletter->id && $e->campaign_id === $campagne->id;
        });

    }

    public function testSendCampaignListener()
    {
        Queue::fake();

        $this->user = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->user);

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

        $listener = new \App\Listeners\SendAtUnine();
        $listener->handle($event);

        Queue::assertPushed(\App\Jobs\SendCampaign::class, function ($job) use ($campagne, $recipients) {
            return $job->campagne->id === $campagne->id && count($recipients) == 1;
        });
    }

    public function testGetUnineUsersByNewsletter()
    {
        $newsletter1 = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $newsletter2 = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2]);

        $user1 = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'user1@unine.ch']);
        $user1->subscriptions()->attach([$newsletter1->id]); // only 1

        $user2 = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'user2@unine.ch']);
        $user2->subscriptions()->attach([$newsletter1->id,$newsletter2->id]); // 1 and 2

        $user3 = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'user3@unine.ch']);
        $user3->subscriptions()->attach([$newsletter2->id]); // only 2

        $user4 = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'user4@gmail.com']);
        $user4->subscriptions()->attach([$newsletter1->id,$newsletter2->id]); // only 2

        $repo = \app::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');

        $results1 = $repo->getByNewsletterAndDomain($newsletter1->id, '@unine.ch');

        $this->assertEquals(2,$results1->count());
        $this->assertEquals(['user1@unine.ch','user2@unine.ch'],$results1->pluck('email')->all());

        $results2 = $repo->getByNewsletterAndDomain($newsletter2->id, '@unine.ch');

        $this->assertEquals(2,$results2->count());
        $this->assertEquals(['user2@unine.ch','user3@unine.ch'],$results2->pluck('email')->all());

    }
}
