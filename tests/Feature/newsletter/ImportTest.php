<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    protected $subscription;
    protected $worker;
    protected $newsletter;
    protected $import;
    protected $campagne;
    protected $camp;
    protected $upload;
    protected $excel;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->worker);

        $this->subscription = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->newsletter = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

        $this->campagne = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

        $this->camp = \Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->camp);

        $this->upload = \Mockery::mock('App\Droit\Service\UploadInterface');
        $this->app->instance('App\Droit\Service\UploadInterface', $this->upload);

        $this->excel = \Mockery::mock('Maatwebsite\Excel\Excel');
        $this->app->instance('Maatwebsite\Excel\Excel', $this->excel);

        $this->import = new \App\Droit\Newsletter\Worker\ImportWorker(
            $this->worker,
            $this->subscription,
            $this->newsletter,
            $this->excel,
            $this->campagne,
            $this->camp,
            $this->upload
        );
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testImport()
    {
        $file    = dirname(__DIR__).'/excel/test.xlsx';

        $email1     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'cindy.leschaud@gmail.com']);
        $email2     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'pruntrut@yahoo.fr']);
        $collection = new \Maatwebsite\Excel\Collections\RowCollection([$email1,$email2]);

        $this->excel->shouldReceive('load->get')->andReturn($collection);

        $results = $this->import->read($file);
        // Maatwebsite\Excel\Collections\RowCollection
        $this->assertInstanceOf('Maatwebsite\Excel\Collections\RowCollection',$results);
    }

    /**
     * @expectedException \App\Exceptions\BadFormatException
     */
    public function testReadFails()
    {
        $file = dirname(__DIR__).'/excel/test-notok.xlsx';

        $this->excel->shouldReceive('load->get')->andReturn(collect([]));

        $results = $this->import->read($file);
    }

    /**
     *
     * @return void
     */
    public function testSubscribeExist()
    {
        $file = dirname(__DIR__).'/excel/test.xlsx';
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->make();

        $email1     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'cindy.leschaud@gmail.com']);
        $email2     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'pruntrut@yahoo.fr']);
        $collection = new \Maatwebsite\Excel\Collections\RowCollection([$email1,$email2]);

        $this->excel->shouldReceive('load->get')->andReturn($collection);

        $this->subscription->shouldReceive('findByEmail')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();

        $results = $this->import->read($file);

        $this->import->subscribe($results);

        $this->assertTrue(true);

    }

    /**
     *
     * @return void
     */
    public function testSubscribeDontExist()
    {
        $file = dirname(__DIR__).'/excel/test.xlsx';
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->make();

        $email1     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'cindy.leschaud@gmail.com']);
        $email2     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'pruntrut@yahoo.fr']);
        $collection = new \Maatwebsite\Excel\Collections\RowCollection([$email1,$email2]);

        $this->excel->shouldReceive('load->get')->andReturn($collection);

        $this->subscription->shouldReceive('findByEmail')->twice()->andReturn(null);
        $this->subscription->shouldReceive('create')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();

        $results = $this->import->read($file);
        $this->import->subscribe($results);

        $this->assertTrue(true);
    }

    /**
     *
     * @return void
     */
    public function testSyncToMailjet()
    {
        $newsletter = \factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

        $this->newsletter->shouldReceive('find')->once()->andReturn($newsletter);
        $this->worker->shouldReceive('setList')->with(1)->once()->andReturn(true);

        $dataID     = new \stdClass();
        $dataID->ID = 1;

        $this->worker->shouldReceive('uploadCSVContactslistData')->once()->andReturn($dataID);
        $this->worker->shouldReceive('importCSVContactslistData')->once();

        $this->import->sync('test.xlsx',1);

        $this->assertTrue(true);
    }


    /**
     *
     * @return void
     */
    public function testImportWithNewsletterId()
    {
        $file       = dirname(__DIR__).'/excel/test.xlsx';
        $upload     = $this->prepareFileUpload($file);
        $file       = ['name' => 'test.xlsx'];

        $dataID     = new \stdClass();
        $dataID->ID = 1;

        $user       = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->make();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

        $email1     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'cindy.leschaud@gmail.com']);
        $email2     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'pruntrut@yahoo.fr']);
        $collection = new \Maatwebsite\Excel\Collections\RowCollection([$email1,$email2]);

        $this->excel->shouldReceive('load->get')->andReturn($collection);
        $this->excel->shouldReceive('load->store');

        $this->upload->shouldReceive('upload')->once()->andReturn($file);
        $this->subscription->shouldReceive('findByEmail')->twice()->andReturn(null);
        $this->subscription->shouldReceive('create')->twice()->andReturn($user);
        $this->subscription->shouldReceive('subscribe')->twice();
        $this->newsletter->shouldReceive('find')->once()->andReturn($newsletter);

        $this->worker->shouldReceive('setList')->with(1)->once()->andReturn(true);
        $this->worker->shouldReceive('uploadCSVContactslistData')->once()->andReturn($dataID);
        $this->worker->shouldReceive('importCSVContactslistData')->once();

        $results = $this->import->import(['title' => 'Titre', 'newsletter_id' => 3],$upload);

        $this->assertTrue(true);
    }

    /**
     *
     * @return void
     */
    public function testImportWithoutNewsletterId()
    {
        $file       = dirname(__DIR__).'/excel/test.xlsx';
        $upload     = $this->prepareFileUpload($file);
        $file       = ['name' => 'test.xlsx'];

        $email1     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'cindy.leschaud@gmail.com']);
        $email2     = new \Maatwebsite\Excel\Collections\CellCollection(['email' => 'pruntrut@yahoo.fr']);
        $collection = new \Maatwebsite\Excel\Collections\RowCollection([$email1,$email2]);

        $this->excel->shouldReceive('load->get')->andReturn($collection);
        $this->upload->shouldReceive('upload')->once()->andReturn($file);

        $results = $this->import->import(['title' => 'Titre'],$upload);

        $this->assertTrue(true);
    }

    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }

    public function testSendListEmail()
    {
        \Queue::fake();

        // Prepare list of emails
        $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();
        $html = '<html><head></head><body></body></html>';

        $this->camp->shouldReceive('html')->once()->andReturn($html);
        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create();
        $emails = factory(\App\Droit\Newsletter\Entities\Newsletter_emails::class, 210)->make();

        foreach ($emails as $email){
            $liste->emails()->save($email);
        }

        $chunks = $liste->emails->chunk(100);
        $chunk1  = $chunks->shift();
        $chunk2  = $chunks->shift();
        $chunk3  = $chunks->shift();

        $this->assertEquals(10, count($chunk3));

        // Send job of emails by chunk, 100 at the time 210/100 => rounded to 3 times

        $this->import->send(1,$liste);

        \Queue::assertPushed(\App\Jobs\SendBulkEmail::class, function ($job) use ($chunk1) {
            return count($job->emails) === count($chunk1);
        });

        \Queue::assertPushed(\App\Jobs\SendBulkEmail::class, function ($job) use ($chunk2) {
            return count($job->emails) === count($chunk2);
        });

        \Queue::assertPushed(\App\Jobs\SendBulkEmail::class, function ($job) use ($chunk3) {
            return count($job->emails) === count($chunk3);
        });
    }
}
