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
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $this->list->shouldReceive('getAll')->once()->andReturn(collect([$liste]));
        $this->list->shouldReceive('find')->once()->andReturn($liste);

        $this->visit('build/liste/'.$liste->id);
        $this->assertViewHas('lists');
        $this->assertViewHas('list');
    }

    public function testSendToList()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        $this->list->shouldReceive('find')->once()->andReturn($liste);
        $mock->shouldReceive('send')->once()->andReturn(['Result' => []]);

        $response = $this->call('POST', 'build/send/list', ['list_id' => $liste->id, 'campagne_id' => 1]);

        $this->assertRedirectedTo('build/newsletter');

        $this->assertSessionHas('alert.style','success');
        $this->assertSessionHas('alert.message','Campagne envoyé à la liste! Contrôler l\'envoi via le tracking (après quelques minutes) ou sur le service externe mailjet.');
    }

    public function testSendGetTheListFails()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);

        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        // No list of emails
        $this->list->shouldReceive('find')->once()->andReturn(null);
        $response = $this->call('POST', 'build/send/list', ['list_id' => $liste->id, 'campagne_id' => 1]);

        $this->assertSessionHas('alert.style','danger');
        $this->assertSessionHas('alert.message','Les emails de la liste n\'ont pas pu être récupérés');
    }

/*    public function testSendToListFails()
    {
        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create();

        $this->list->shouldReceive('find')->once()->andReturn($liste);
        $mock->shouldReceive('send')->once()->andReturn(false);

        $response = $this->call('POST', 'build/send/list', ['list_id' => $liste->id, 'campagne_id' => 1]);

        $this->assertSessionHas('alert.style','danger');
        $this->assertSessionHas('alert.message','Problème avec l\'envoi, vérifier sur mailjet.com');
    }*/

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
        $file   = dirname(__DIR__).'/excel/test.xlsx';
        $upload = $this->prepareFileUpload($file);

        $response = $this->call('POST', 'build/liste', ['title' => 'Un titre' ,'list_id' => 1, 'campagne_id' => 1], [], ['file' => $upload]);

        $this->assertSessionHasErrors();
    }
    
    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
