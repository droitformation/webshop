<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsletterInscriptionTest extends BrowserKitTest
{
    protected $mock;
    protected $subscription;
    protected $worker;

    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->worker);

        $this->subscription = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

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
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn(null);
        $this->subscription->shouldReceive('create')->once()->andReturn($user);

        \Mail::shouldReceive('send')->once();

        $response = $this->call('POST', 'subscribe', ['email' => 'info@leschaud.ch', 'return_path' => '/']);

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

        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('removeContact')->once()->andReturn(true);

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
        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('removeContact')->once()->andReturn(true);
        $this->subscription->shouldReceive('delete')->once();
        
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
        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('subscribeEmailToList')->once()->andReturn(true);

        $response = $this->call('GET', 'activation/1234/'.$newsletter->id);

        $this->assertRedirectedTo($site->slug);
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

        $this->subscription->shouldReceive('activate')->once()->andReturn(null);

        $response = $this->call('GET', 'activation/edfrgth/'.$newsletter->id);

        $this->assertRedirectedTo($site->slug);
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
