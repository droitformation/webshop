<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class NewsletterInscriptionTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $mock;
    protected $subscription;
    protected $worker;
    protected $subscription_worker;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->mock = \Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->worker);

        $this->subscription = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->subscription_worker = \Mockery::mock('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface', $this->subscription_worker);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testSubscription()
    {
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $this->subscription_worker->shouldReceive('activate')->once()->andReturn($user);

        \Mail::shouldReceive('send')->once();

        $response = $this->call('POST', 'subscribe', ['email' => 'info@leschaud.ch', 'return_path' => '/pubdroit', 'newsletter_id' => $newsletter->id , 'site_id' => 1]);

        $response->assertRedirect('/pubdroit');
    }

    /**
     *
     * @return void
     */
    public function testRemoveSubscription()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();

        // 2x subscriptionss
        $user->subscriptions()->attach(2);
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('unsubscribe')->once();

        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => $newsletter->id, 'email' => 'info@leschaud.ch', 'return_path' => 'bail']);

        $response->assertRedirect('/bail');
    }

    public function testRemoveAllSubscription()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();

        // 1x subscription, remove and delete
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('unsubscribe')->once();

        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => $newsletter->id, 'email' => 'info@leschaud.ch']);

        $response->assertRedirect('/');
    }

    /**
     *
     * @return void
     */
    public function testActivateSubscription()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('activate')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('subscribe')->once();

        $response = $this->call('GET', 'activation/1234/'.$newsletter->id);

        $response->assertRedirect($site->url);
    }

    public function testActivateFailNewsletterSubscription()
    {
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $this->subscription->shouldReceive('activate')->once()->andReturn($user);

        $response = $this->call('GET', 'activation/1234/0');

        $response->assertStatus(404);
    }

    public function testActivateFails()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $this->subscription->shouldReceive('activate')->once()->andReturn(false);

        $response = $this->call('GET', 'activation/edfrgth/'.$newsletter->id);

        $response->assertRedirect($site->url);
    }

    public function testUnsubscribeFails()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach($newsletter->id);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn(null);

        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => $newsletter->id, 'email' => 'info@leschaud.ch', 'return_path' => 'bail']);

        $response->assertRedirect('bail/unsubscribe');

    }
    
}
