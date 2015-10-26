<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InscriptionTest extends TestCase
{
    protected $mock;
    protected $subscription;
    protected $worker;

    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\MailjetInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetInterface', $this->worker);

        $this->subscription = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     * @return void
     */
    public function testSubscription()
    {
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();
        $user->subscriptions = factory(App\Droit\Newsletter\Entities\Newsletter_subscriptions::class)->make();

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn(null);
        $this->subscription->shouldReceive('create')->once()->andReturn($user);

        Mail::shouldReceive('send')->once();

        $response = $this->call('POST', 'subscribe', ['email' => 'info@leschaud.ch']);

        $this->assertRedirectedTo('/');

    }

    /**
     *
     * @return void
     */
    public function testRemoveSubscription()
    {

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();
        $user->subscriptions = factory(App\Droit\Newsletter\Entities\Newsletter_subscriptions::class)->make();

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->subscription->shouldReceive('delete')->once();
        $this->worker->shouldReceive('removeContact')->once()->andReturn(true);

        $response = $this->call('POST', 'unsubscribe', ['email' => 'info@leschaud.ch']);

        $this->assertRedirectedTo('/');

    }

    /**
     *
     * @return void
     */
    public function testActivateSubscription()
    {

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();
        $user->subscriptions = factory(App\Droit\Newsletter\Entities\Newsletter_subscriptions::class)->make();

        $this->subscription->shouldReceive('activate')->once()->andReturn($user);
        $this->worker->shouldReceive('subscribeEmailToList')->once()->andReturn(true);

        $response = $this->call('GET', 'activation/1234');

        $this->assertRedirectedTo('/');

    }

}
