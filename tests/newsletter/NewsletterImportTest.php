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
    protected $upload;

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

        $this->newsletter = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

        $this->upload = Mockery::mock('App\Droit\Service\UploadInterface');
        $this->app->instance('App\Droit\Service\UploadInterface', $this->upload);

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
        $worker       = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $newsletter   = factory(App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

        $this->newsletter->shouldReceive('find')->once()->andReturn($newsletter);
        $this->worker->shouldReceive('setList')->with(1)->once()->andReturn(true);

        $dataID     = new stdClass();
        $dataID->ID = 1;

        $this->worker->shouldReceive('uploadCSVContactslistData')->once()->andReturn($dataID);
        $this->worker->shouldReceive('importCSVContactslistData')->once();

        $worker->sync('test.xlsx',1);

    }

    public function testImportListNewsletter()
    {
        $file   = storage_path('excel/test.xlsx');
        $upload = $this->prepareFileUpload($file);

        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        $mock->shouldReceive('import')->once();

        $response = $this->call('POST', 'admin/import', ['title' => 'Titre', 'newsletter_id' => 3], [], ['file' => $upload]);

        $this->assertRedirectedTo('admin/import');
    }

    /**
     *
     * @return void
     */
    public function testImportWithNewsletterId()
    {
        $worker = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');

        $file   = storage_path('excel/test.xlsx');
        $upload = $this->prepareFileUpload($file);
        $file   = ['name' => 'test.xlsx'];
        $dataID     = new stdClass();
        $dataID->ID = 1;

        $user       = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

        $mock = \Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');

        $this->upload->shouldReceive('upload')->once()->andReturn($file);
        $this->subscription->shouldReceive('findByEmail')->twice()->andReturn(null);
        $this->subscription->shouldReceive('create')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();
        $this->newsletter->shouldReceive('find')->once()->andReturn($newsletter);
        $this->worker->shouldReceive('setList')->with(1)->once()->andReturn(true);
        $this->worker->shouldReceive('uploadCSVContactslistData')->once()->andReturn($dataID);
        $this->worker->shouldReceive('importCSVContactslistData')->once();

        $results = $worker->import(['title' => 'Titre', 'newsletter_id' => 3],$upload);
    }

    /**
     *
     * @return void
     */
    public function testImportWithoutNewsletterId()
    {
        $worker = App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');

        $file   = storage_path('excel/test.xlsx');
        $upload = $this->prepareFileUpload($file);
        $file   = ['name' => 'test.xlsx'];
        $dataID     = new stdClass();
        $dataID->ID = 1;

        $user       = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->make();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

        $mock = \Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');

        $this->upload->shouldReceive('upload')->once()->andReturn($file);

        $results = $worker->import(['title' => 'Titre'],$upload);
    }

    // Put this function in a helpers.php or a class (can be called statically) or anywhere you like
    function prepareFileUpload($path)
    {
        TestCase::assertFileExists($path);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $path);

        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, $mime, null, null, true);
    }
}
