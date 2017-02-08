<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListTest extends BrowserKitTest
{
    protected $subscription;
    protected $newsletter;
    protected $upload;
    protected $list;

    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();

        $this->subscription = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->newsletter = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

        $this->upload = Mockery::mock('App\Droit\Service\UploadInterface');
        $this->app->instance('App\Droit\Service\UploadInterface', $this->upload);

        $this->list = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterListInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterListInterface', $this->list);

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

    public function testIndexPage()
    {
        $this->list->shouldReceive('getAll')->once();

        $this->visit('build/liste')->see('Listes hors campagne');
        $this->assertViewHas('lists');
    }

    public function testListPage()
    {
        $this->list->shouldReceive('getAll')->once();
        $this->list->shouldReceive('find')->once();

        $this->visit('build/liste/1');
        $this->assertViewHas('lists');
        $this->assertViewHas('list');
    }

    public function testSendToList()
    {
        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);
        $this->list->shouldReceive('find')->once();
        $mock->shouldReceive('send')->once();

        $response = $this->call('POST', 'build/send/liste', ['list_id' => 1, 'campagne_id' => 1]);

        $this->assertRedirectedTo('build/newsletter');
    }

    public function testStoreListe()
    {
        $file   = dirname(__DIR__).'/excel/test.xlsx';
        $upload = $this->prepareFileUpload($file);
        
        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        $email1     = new Maatwebsite\Excel\Collections\CellCollection(['email' => 'cindy.leschaud@gmail.com']);
        $email2     = new Maatwebsite\Excel\Collections\CellCollection(['email' => 'pruntrut@yahoo.fr']);
        $collection = new Maatwebsite\Excel\Collections\RowCollection([$email1,$email2]);

        $mock->shouldReceive('read')->once()->andReturn($collection);
        $this->list->shouldReceive('create')->once();
        $this->upload->shouldReceive('upload')->once()->andReturn(['name' => 'uploaded']);

        $this->list->shouldReceive('getAll')->twice()->andReturn(collect([]));

        $this->visit('/build/liste')
            ->type('Un titre','title')
            ->attach($file, 'file')
            ->press('Envoyer');

        $this->seePageIs('build/liste');
    }

    public function testStoreListeUploadFails()
    {
        try{
            $file   = dirname(__DIR__).'/excel/test.xlsx';
            $upload = $this->prepareFileUpload($file);

            $response = $this->call('POST', 'build/liste', ['title' => 'Un titre' ,'list_id' => 1, 'campagne_id' => 1], [], ['file' => $upload]);

        } catch (Exception $e) {
            $this->assertType('designpond\newsletter\Exceptions\FileUploadException', $e);
        }
    }

    public function testStoreListeFormatFails()
    {
        try{

            $file   = dirname(__DIR__).'/excel/test-notok.xlsx';
            $upload = $this->prepareFileUpload($file);

            $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
            $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

            $this->upload->shouldReceive('upload')->once()->andReturn(['name' => 'title']);
            $this->list->shouldReceive('getAll')->twice()->andReturn(collect([]));
            $collection = new Maatwebsite\Excel\Collections\RowCollection([]);
            $mock->shouldReceive('read')->once()->andReturn($collection);

            $this->visit('/build/liste')
                ->type('Un titre','title')
                ->attach($file, 'file')
                ->press('Envoyer');

        } catch (Exception $e) {
            $this->assertType('\designpond\newsletter\Exceptions\BadFormatException', $e);
        }

    }
    
    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
