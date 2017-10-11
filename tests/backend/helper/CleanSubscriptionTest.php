<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;


class CleanSubscriptionTest extends BrowserKitTest {

    use DatabaseTransactions;

    protected $import;

    public function setUp()
    {
        parent::setUp();

        $this->import = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $this->import);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCleanTheEmailsHasEverything()
    {
        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $newsletter2 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $user->subscriptions()->attach([$newsletter1->id,$newsletter2->id]);

        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');

        $emails = collect([['email' => 'cindy.leschaud@gmail.com'],['email' => 'info@leschaud.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        // has both nothing changes
        $clean->chargeEmailsFrom('myfile')->cleanSubscribersFor($newsletter1->id);

        $user->load('subscriptions');

        $this->assertEquals([$newsletter1->id,$newsletter2->id], $user->subscriptions->pluck('id')->all());
	}

    public function testCleanHasNone()
    {
        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');

        $emails = collect([['email' => 'cindy.leschaud@gmail.com'],['email' => 'info@leschaud.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        $clean->chargeEmailsFrom('myfile')->cleanSubscribersFor($newsletter1->id);

        $user->fresh();
        $user->load('subscriptions');

        $this->assertEquals([$newsletter1->id], $user->subscriptions->pluck('id')->all());
    }

    public function testCleanHasOneButNotTheNewOne()
    {
        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $newsletter2 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $user->subscriptions()->attach([$newsletter1->id]);
        $user->fresh();
        $user->load('subscriptions');

        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');

        $emails = collect([['email' => 'cindy.leschaud@gmail.com'],['email' => 'info@leschaud.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        $clean->chargeEmailsFrom('myfile')->cleanSubscribersFor($newsletter2->id);

        $user->fresh();
        $user->load('subscriptions');

        $this->assertEquals([$newsletter1->id,$newsletter2->id], $user->subscriptions->pluck('id')->all());
    }

    public function testCleanTheEmailsIsNotInTheList()
    {
        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $newsletter2 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $user->subscriptions()->attach([$newsletter1->id]);

        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');

        $emails = collect([['email' => 'info@leschaud.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        $clean->chargeEmailsFrom('myfile')->cleanSubscribersFor($newsletter2->id);

        $user->load('subscriptions');

        // Nothing changes he keeps his subscription to newsletter1 because we pass another : newsletter2
        $this->assertEquals([$newsletter1->id], $user->subscriptions->pluck('id')->all());
    }

    public function testUserIsTrashedButNeedsToBeInTheList()
    {
        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');

        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        // Make and delete the user
        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create([
            'email'      => 'cindy.leschaud@gmail.com',
            'deleted_at' => \Carbon\Carbon::today()->toDateTimeString()
        ]);

        $emails = collect([['email' => 'cindy.leschaud@gmail.com']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        // The user should be restored and have a subscription for $newsletter1
        $clean->chargeEmailsFrom('myfile')->cleanSubscribersFor($newsletter1->id);

        $this->assertFalse($user->fresh()->trashed());
        $this->assertEquals([$newsletter1->id], $user->subscriptions->pluck('id')->all());
    }

    public function testAddAllUsers()
    {
        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');
        $repo  = \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');

        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $emails = collect([['email' => 'cindy.leschaud@gmail.com'],['email' => 'cindy.leschaud@unine.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        // The user should be restored and have a subscription for $newsletter1
        $clean->chargeEmailsFrom('myfile')->addSubscriberFor($newsletter->id);

        $all = $repo->getAll();

        $this->assertEquals(2,$all->count());

        $first  = $all->shift();
        $second = $all->shift();

        $this->assertEquals([$newsletter->id], $first->subscriptions->pluck('id')->all());
        $this->assertEquals([$newsletter->id], $second->subscriptions->pluck('id')->all());
    }

    public function testAddUsersAndSome()
    {
        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');
        $repo  = \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');

        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $newsletter2 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $user->subscriptions()->attach([$newsletter2->id]);

        $emails = collect([['email' => 'info@leschaud.ch'],['email' => 'cindy.leschaud@unine.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        // The user should be restored and have a subscription for $newsletter1
        $clean->chargeEmailsFrom('myfile')->addSubscriberFor($newsletter->id);

        $all = $repo->getAll();

        $this->assertEquals(3,$all->count());

        $filtered = $all->reject(function ($item, $key) use ($user) {
            return $item->id == $user->id;
        });

        $first  = $filtered->shift();
        $second = $filtered->shift();

        $this->assertEquals([$newsletter2->id], $user->subscriptions->pluck('id')->all());
        $this->assertEquals([$newsletter->id], $first->subscriptions->pluck('id')->all());
        $this->assertEquals([$newsletter->id], $second->subscriptions->pluck('id')->all());
    }

    public function testAddUsersAndSomeAllNewSubscription()
    {
        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');
        $repo  = \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');

        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter  = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $newsletter2 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $user->subscriptions()->attach([$newsletter2->id]);

        $emails = collect([['email' => 'info@leschaud.ch'],['email' => 'cindy.leschaud@unine.ch'],['email' => 'cindy.leschaud@gmail.com']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        // The user should be restored and have a subscription for $newsletter1
        $clean->chargeEmailsFrom('myfile')->addSubscriberFor($newsletter->id);

        $all = $repo->getAll();

        $this->assertEquals(3,$all->count());

        $filtered = $all->reject(function ($item, $key) use ($user) {
            return $item->id == $user->id;
        });

        $first  = $filtered->shift();
        $second = $filtered->shift();

        $this->assertEquals([$newsletter2->id,$newsletter->id], $user->subscriptions->pluck('id')->all());
        $this->assertEquals([$newsletter->id], $first->subscriptions->pluck('id')->all());
        $this->assertEquals([$newsletter->id], $second->subscriptions->pluck('id')->all());
    }
}
