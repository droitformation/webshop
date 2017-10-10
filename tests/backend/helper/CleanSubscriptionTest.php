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

        $this->assertEquals([$newsletter1->id], $user->subscriptions->pluck('id')->all());
    }

/*    public function testCleanTheEmailsIsNotInTheListAndHasNoSubscriptionShouldBeDeleted()
    {
        $site        = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter1 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'cindy.leschaud@gmail.com']);

        $clean = \App::make('App\Droit\Generate\Clean\CleanSubscriber');

        $emails = collect([['email' => 'info@leschaud.ch']]);

        $this->import->shouldReceive('read')->andReturn($emails);

        $clean->chargeEmailsFrom('myfile')->cleanSubscribersFor($newsletter1->id);

        $user->fresh();
        echo '<pre>';
        print_r($user->deleted_at);
        echo '</pre>';exit();
        $this->assertTrue($user->trashed());
    }*/

}
