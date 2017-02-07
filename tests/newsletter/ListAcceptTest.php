<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListAcceptTest extends BrowserKitTest
{

    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();
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
        $this->visit('build/liste')->see('Listes hors campagne');
        $this->assertViewHas('lists');
    }

    public function testListPage()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $email1 = factory(App\Droit\Newsletter\Entities\Newsletter_emails::class)->create();
        $email2 = factory(App\Droit\Newsletter\Entities\Newsletter_emails::class)->create();

        $liste->emails()->saveMany([$email1,$email2]);

        $this->visit('build/liste/'.$liste->id)->see('Listes hors campagne');
        $this->assertViewHas('lists');
        $this->assertViewHas('list');
        $this->see('One Title');
    }

    public function testSendToList()
    {
        $mock = Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        $mock->shouldReceive('send')->once();

        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create();

        $response = $this->call('POST', 'build/liste/send', ['list_id' => $liste->id, 'campagne_id' => 1]);

        $this->followRedirects()->seePageIs('build/newsletter');
    }
    
    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
