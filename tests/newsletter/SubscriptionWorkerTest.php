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

        $result = $worker->activate('info@publications-droit.ch',1);

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

        $worker->unsubscribe($subscription,[$newsletter->id]);

        $user->fresh();
        $user->load('email_subscriptions');
        $subscription->fresh();

        $results = !$user->email_subscriptions->isEmpty() ? $user->email_subscriptions->pluck('subscriptions')->flatten(1) : collect([]);

        $this->assertTrue($results->isEmpty());

        $this->seeIsSoftDeletedInDatabase('newsletter_users', [
            'id'  => $subscription->id
        ]);
    }

    /**
     *
     * @return void
     */
    public function testUpdateSubscriptions()
    {
        /******************************/
        $user         = factory(App\Droit\User\Entities\User::class)->create();
        $subscriber = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $user->email]);

        $site1         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site1->id]);

        $site2         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter2   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site2->id]);

        $site3         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter3   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site3->id]);

        $site4         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter4   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site4->id]);

        $site5         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter5   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site5->id]);

        $has = [$newsletter1->id, $newsletter2->id, $newsletter3->id];
        $subscriber->subscriptions()->attach($has);

        /******************************/

        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(
            App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
            App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
            $this->mailjet
        );

        $this->app->instance('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface', $worker);

        $this->mailjet->shouldReceive('setList')->times(4);
        $this->mailjet->shouldReceive('removeContact')->times(2)->andReturn(true);
        $this->mailjet->shouldReceive('subscribeEmailToList')->times(2)->andReturn(true);

        $new      = [$newsletter1->id, $newsletter4->id, $newsletter5->id];
        $response = $this->call('PUT', 'build/subscriber/'.$subscriber->id, ['id' => $subscriber->id , 'email' => $subscriber->email, 'newsletter_id' => $new, 'activation' => 1]);

        $this->assertRedirectedTo('build/subscriber/'.$subscriber->id);

        $content = $this->followRedirects()->response->getOriginalContent();
        $content = $content->getData();

        $subscriber = $content['subscriber'];

        $effective = $subscriber->subscriptions->pluck('id')->all();

        $this->assertSame($new,$effective);
    }

    /**
     *
     * @return void
     */
    public function testUpdateRemoveAllSubscriptions()
    {
        /******************************/
        $user         = factory(App\Droit\User\Entities\User::class)->create();
        $subscriber = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $user->email]);

        $site1         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site1->id]);

        $site2         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter2   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site2->id]);

        $site3         = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter3   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site3->id]);

        $has = [$newsletter1->id, $newsletter2->id, $newsletter3->id];
        $subscriber->subscriptions()->attach($has);

        /******************************/

        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(
            App::make('App\Droit\Newsletter\Repo\NewsletterInterface'),
            App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'),
            $this->mailjet
        );

        $this->app->instance('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface', $worker);

        $this->mailjet->shouldReceive('setList')->times(3);
        $this->mailjet->shouldReceive('removeContact')->times(3)->andReturn(true);

        $new      = [];
        $response = $this->call('PUT', 'build/subscriber/'.$subscriber->id, ['id' => $subscriber->id , 'email' => $subscriber->email, 'newsletter_id' => $new, 'activation' => 1]);

        $this->assertRedirectedTo('build/subscriber');

        $subscriber->fresh();

        $effective = $subscriber->subscriptions->pluck('id')->all();

        $this->assertSame($new,$effective);
    }
}
