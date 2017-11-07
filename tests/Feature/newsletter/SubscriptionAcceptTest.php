<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class SubscriptionAcceptTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $worker;
    protected $newsletter;
    protected $subscription;
    protected $subscription_worker;
    protected $mailjet;

    public function setUp()
    {
        parent::setUp();


        $this->reset_all();

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->worker);

        $this->subscription = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->newsletter = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

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

    public function testAddSubscriptionFromAdmin()
    {
        /******************************/
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->make(['id' => 1]);

        $subscription1 = factory(\App\Droit\Newsletter\Entities\Newsletter_subscriptions::class)->make(['newsletter_id' => 1]);
        $subscription3 = factory(\App\Droit\Newsletter\Entities\Newsletter_subscriptions::class)->make(['newsletter_id' => 2]);

        $user->subscriptions = new \Illuminate\Support\Collection([$subscription1,$subscription3]);
        /******************************/

        $this->subscription->shouldReceive('create')->once()->andReturn($user);
        $this->subscription_worker->shouldReceive('subscribe')->once();

        $response = $this->call('POST', 'build/subscriber', ['email' => $user->email, 'newsletter_id' => [3]]);

        $response->assertRedirect('build/subscriber');

    }

    public function testRemoveAndDeleteSubscription()
    {
        /******************************/
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->make(['id' => 1, 'email' => 'cindy.leschaud@gmail.com']);

        $newsletter1 = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make(['id' => 1, 'list_id' => 1]);
        $newsletter2 = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make(['id' => 2,'list_id' => 2]);

        $user->subscriptions = new \Illuminate\Support\Collection([$newsletter1,$newsletter2]);

        $newsletters = new \Illuminate\Support\Collection([$newsletter1,$newsletter2]);
        /******************************/

        $this->subscription->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->newsletter->shouldReceive('getAll')->andReturn($newsletters);
        $this->subscription_worker->shouldReceive('unsubscribe')->once();

        $response = $this->call('DELETE', 'build/subscriber/'.$user->id, ['email' => $user->email]);

        $response->assertRedirect('build/subscriber');
    }

    /**
     *
     * @return void
     */
    public function testUpdateSubscriptions()
    {
        /******************************/
        $user         = factory(\App\Droit\User\Entities\User::class)->create();
        $subscriber   = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $user->email]);

        $site1         = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter1   = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site1->id]);

        $site2         = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter2   = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site2->id]);

        $site3         = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter3   = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site3->id]);

        $site4         = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter4   = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site4->id]);

        $site5         = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter5   = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site5->id]);

        $has = [$newsletter1->id, $newsletter2->id, $newsletter3->id];
        $subscriber->subscriptions()->attach($has);

        /******************************/

        $this->subscription->shouldReceive('update')->once()->andReturn($subscriber);
        $this->subscription_worker->shouldReceive('subscribe')->once();
        $this->subscription_worker->shouldReceive('unsubscribe')->once();

        /*
            $new = [1,4,5];
            $has = [1,2,3];

            $added   = [4,5];
            $removed = [2,3];
        */

        $new = [$newsletter1->id, $newsletter4->id, $newsletter5->id];
        $response = $this->call('PUT', 'build/subscriber/'.$subscriber->id, ['id' => $subscriber->id , 'email' => $subscriber->email, 'newsletter_id' => $new, 'activation' => 1]);

        $response->assertRedirect('build/subscriber/'.$subscriber->id);
    }
}
