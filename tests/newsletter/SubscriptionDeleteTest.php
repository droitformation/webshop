<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionDeleteTest extends BrowserKitTest
{
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
    
    /**
     *
     * @return void
     */
    public function testRemoveAndDeleteSubscription()
    {
        /******************************/
        $user       = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => 'info@domain.com']);
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);

        $newsletters = collect([$newsletter]);

        $user->subscriptions = $newsletters;
        /******************************/

        $this->newsletter->shouldReceive('getAll')->andReturn($newsletters);
        $this->worker->shouldReceive('setList')->once();
        $this->worker->shouldReceive('removeContact')->once()->andReturn(true);

        $response = $this->call('DELETE', 'build/subscriber/'.$user->id, ['email' => $user->email]);

        $this->assertRedirectedTo('build/subscriber');

        $user = $user->fresh();
        $this->assertTrue($user->trashed());
    }
    
}
