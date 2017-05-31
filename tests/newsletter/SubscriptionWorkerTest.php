<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionWorkerTest extends BrowserKitTest
{
    protected $newsletter;
    protected $subscription;
    protected $mailjet;

    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mailjet = Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->mailjet);

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);
        
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testSubscriptionWorker()
    {
        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(
            App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
            App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
            $this->mailjet
        );

        $result = $worker->make('info@publications-droit.ch',1);

        $this->assertTrue($result->subscriptions->contains('id',1));
    }

    public function testSubscriberExist()
    {
        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(
            App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
            App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
            $this->mailjet
        );

        $subscriber = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'info@publications-droit.ch']);
        $subscriber->subscriptions()->attach(1);

        $result = $worker->subscribe('info@publications-droit.ch',1);

        $this->assertFalse($result);
    }

    public function testUnsubscribe()
    {
        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(
            App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
            App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
            $this->mailjet
        );

        $user         = factory(App\Droit\User\Entities\User::class)->create();
        $site         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $subscription = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $user->email]);

        $subscription->subscriptions()->attach($newsletter->id);

        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('removeContact')->once()->andReturn(true);

        $subscriptions = !$user->email_subscriptions->isEmpty() ? $user->email_subscriptions->pluck('subscriptions')->flatten(1) : collect([]);
        $this->assertTrue($subscriptions->contains('id',$newsletter->id));

        $worker->unsubscribe($subscription->email,$newsletter->id);

        $user->fresh();
        $user->load('email_subscriptions');
        $subscription->fresh();

        $results = !$user->email_subscriptions->isEmpty() ? $user->email_subscriptions->pluck('subscriptions')->flatten(1) : collect([]);

        $this->assertTrue($results->isEmpty());
        //$this->assertTrue($subscription->trashed());
    }
}
