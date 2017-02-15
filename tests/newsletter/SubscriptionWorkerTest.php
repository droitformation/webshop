<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionWorkerTest extends BrowserKitTest
{
    protected $subscription;
    protected $worker;
    protected $newsletter;

    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->worker);

        $this->newsletter = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

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
        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'));

        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'));
        $result = $worker->make('info@publications-droit.ch',1);

        $this->assertTrue($result->subscriptions->contains('id',1));
    }

    public function testSubscriberExist()
    {
        $worker = new App\Droit\Newsletter\Worker\SubscriptionWorker(App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface'));

        $subscriber = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'info@publications-droit.ch']);
        $subscriber->subscriptions()->attach(1);

        $result = $worker->subscribe('info@publications-droit.ch',1);

        $this->assertFalse($result);
    }
}
