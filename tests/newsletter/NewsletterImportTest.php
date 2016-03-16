<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsletterImportTest extends TestCase
{
    protected $mock;
    protected $subscription;
    protected $worker;
    protected $newsletter;

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

        $this->newsletter = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->newsletter);

        $model = new \App\Droit\User\Entities\User();

        $user = $model->find(710);

        $this->actingAs($user);

    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     * @return void
     */
    public function testImport()
    {
        $worker  = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $file    = storage_path('excel/test.xlsx');

        $results = $worker->read($file);

        // Maatwebsite\Excel\Collections\RowCollection
        $this->assertInstanceOf('Maatwebsite\Excel\Collections\RowCollection',$results);
    }

    /**
     *
     * @return void
     */
    public function testSubscribeExist()
    {
        $worker  = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $file    = storage_path('excel/test.xlsx');

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();

        $this->subscription->shouldReceive('findByEmail')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();

        $results = $worker->read($file);
        $worker->subscribe($results);
    }

    /**
     *
     * @return void
     */
    public function testSubscribeDontExist()
    {
        $worker  = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $file    = storage_path('excel/test.xlsx');

        $user = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();

        $this->subscription->shouldReceive('findByEmail')->twice()->andReturn(null);
        $this->subscription->shouldReceive('create')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();

        $results = $worker->read($file);
        $worker->subscribe($results);
    }


    /**
     *
     * @return void
     */
    public function testSyncToMailjet()
    {
        $worker     = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

        $this->newsletter->shouldReceive('find')->once()->andReturn($newsletter);
        $this->subscription->shouldReceive('create')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();

        $results = $worker->read($file);
        $worker->subscribe($results);
    }
}
