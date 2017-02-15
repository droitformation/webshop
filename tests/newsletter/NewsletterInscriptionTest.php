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
       /* $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn(null);
        $this->subscription->shouldReceive('create')->once()->andReturn($user);

        \Mail::shouldReceive('send')->once();

        $response = $this->call('POST', 'subscribe', ['email' => 'info@leschaud.ch', 'return_path' => '/']);

        $this->assertRedirectedTo('/');*/
    }

    /**
     *
     * @return void
     */
    public function testRemoveSubscription()
    {
       /* $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        //$this->subscription->shouldReceive('delete')->once();
        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('removeContact')->once()->andReturn(true);

        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => 1, 'email' => 'info@leschaud.ch']);

        $this->assertRedirectedTo('/');*/
    }

    public function testRemoveAllSubscription()
    {
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('removeContact')->once()->andReturn(true);
        $this->subscription->shouldReceive('delete')->once();
        
        $response = $this->call('POST', 'unsubscribe', ['newsletter_id' => 1, 'email' => 'info@leschaud.ch']);

        $this->assertRedirectedTo('/');
    }

    /**
     *
     * @return void
     */
    public function testActivateSubscription()
    {
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create();
        $user->subscriptions()->attach([1]);

        $this->subscription->shouldReceive('activate')->once()->andReturn($user);
        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('subscribeEmailToList')->once()->andReturn(true);

        $response = $this->call('GET', 'activation/1234/1');

        $this->assertRedirectedTo('/');

    }

}
