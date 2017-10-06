<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsletterInscriptionTest extends BrowserKitTest
{
    protected $mock;
    protected $subscription;
    protected $worker;
    protected $subscription_worker;

    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\SendgridInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\SendgridInterface', $this->worker);

        $this->subscription = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->subscription_worker = Mockery::mock('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface', $this->subscription_worker);

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
    
    /**
     *
     * @return void
     */
    public function testSubscription()
    {
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $this->subscription_worker->shouldReceive('activate')->once()->andReturn($user);

        \Mail::shouldReceive('send')->once();

        $response = $this->call('POST', 'subscribe', ['email' => 'info@leschaud.ch', 'return_path' => '/', 'newsletter_id' => $newsletter->id , 'site_id' => 1]);

        $this->assertRedirectedTo('/');
    }

    /**
     *
     * @return void
     */
    public function testRemoveSubscription()
    {
        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();

        // 2x subscriptionss
        $user->subscriptions()->attach(2);
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('unsubscribe')->once();

        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => $newsletter->id, 'email' => 'info@leschaud.ch', 'return_path' => 'bail']);

        $this->assertRedirectedTo('/bail');
    }

    public function testRemoveAllSubscription()
    {
        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();

        // 1x subscription, remove and delete
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('unsubscribe')->once();
        
        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => $newsletter->id, 'email' => 'info@leschaud.ch']);

        $this->assertRedirectedTo('/');
    }

    /**
     *
     * @return void
     */
    public function testActivateSubscription()
    {
        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('activate')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('subscribe')->once();

        $response = $this->call('GET', 'activation/1234/'.$newsletter->id);

        $this->assertRedirectedTo($site->url);
    }

    public function testActivateFailNewsletterSubscription()
    {
        try {
            $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
            $this->subscription->shouldReceive('activate')->once()->andReturn($user);

            $response = $this->call('GET', 'activation/1234/0');

        } catch (Exception $e) {
            $this->assertType('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', $e);
        }
    }

    public function testActivateFails()
    {
        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $this->subscription->shouldReceive('activate')->once()->andReturn(false);

        $response = $this->call('GET', 'activation/edfrgth/'.$newsletter->id, [], [], ['HTTP_REFERER' => $site->url]);

        $this->assertRedirectedTo($site->url);
    }

    public function testUnsubscribeFails()
    {
        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn(null);
        
        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => $newsletter->id, 'email' => 'info@leschaud.ch', 'return_path' => 'bail']);

        $this->assertRedirectedTo('bail/unsubscribe');

    }
}
