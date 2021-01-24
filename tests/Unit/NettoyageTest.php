<?php namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class NettoyageTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $import;
    protected $worker;

    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->import = \Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $this->import);

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface', $this->worker);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testResultPurgeInvalid()
    {

        $email1 = \App\Droit\Newsletter\Entities\Newsletter_users::create(['email' => 'droitformation.web@gmail.com']);
        $email2 = \App\Droit\Newsletter\Entities\Newsletter_users::create(['email' => 'info@domaine.com']);

        $results = collect([[$email1->email, $email2->email]]);

        $upload = $this->prepareFileUpload(dirname(__DIR__).'/excel/test.xlsx');

        $this->import->shouldReceive('setFile')->with($upload)->andReturn($this->import);
        $this->import->shouldReceive('uploadAndRead')->andReturn($results);
        $this->worker->shouldReceive('exist')->andReturn($email1);
        $this->worker->shouldReceive('exist')->andReturn($email2);
        $this->worker->shouldReceive('unsubscribe')->andReturn(true);

        // got to page purge
        $this->get('build/purge');
        $response = $this->call('POST', 'build/purge', ['newsletter_id' => [1,2]],[],['file' => $upload]);

        // redirect back
        $response->assertRedirect('build/purge');
    }

    function prepareFileUpload($path,$name = 'test.xlsx')
    {
        return new \Illuminate\Http\UploadedFile($path, $name, \File::mimeType($path),null,true);
    }

}
