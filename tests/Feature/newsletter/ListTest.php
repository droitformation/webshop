<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ListTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $subscription;
    protected $newsletter;
    protected $upload;
    protected $list;

    public function setUp()
    {
        parent::setUp();


        $this->reset_all();

        $this->subscription = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->newsletter = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

        $this->upload = \Mockery::mock('App\Droit\Service\UploadInterface');
        $this->app->instance('App\Droit\Service\UploadInterface', $this->upload);

        $this->list = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterListInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterListInterface', $this->list);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testListPage()
    {
        \DB::table('newsletter_lists')->truncate();

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);

        $this->list->shouldReceive('getAll')->once()->andReturn(collect([$liste]));
        $this->list->shouldReceive('find')->once()->andReturn($liste);

        $response = $this->get('build/liste/'.$liste->id);
        $response->assertViewHas('lists');
        $response->assertViewHas('list');

    }

    public function testSendToList()
    {
        \DB::table('newsletter_lists')->truncate();

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);

        $mock = \Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        $this->list->shouldReceive('find')->once()->andReturn($liste);
        $mock->shouldReceive('send')->once()->andReturn(['Result' => []]);

        $response = $this->call('POST', 'build/send/list', ['list_id' => $liste->id, 'campagne_id' => 1]);

        $response->assertRedirect('build/newsletter');
        $response->assertSessionHas('alert.style','success');
        $response->assertSessionHas('alert.message','Campagne envoyé à la liste! Contrôler l\'envoi via le tracking (après quelques minutes) ou sur le service externe mailjet.');
    }

    public function testSendGetTheListFails()
    {
        \DB::table('newsletter_lists')->truncate();

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);

        $mock = \Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $mock);

        // No list of emails
        $this->list->shouldReceive('find')->once()->andReturn(null);
        $response = $this->call('POST', 'build/send/list', ['list_id' => $liste->id, 'campagne_id' => 1]);

        $response->assertSessionHas('alert.style','danger');
        $response->assertSessionHas('alert.message','Les emails de la liste n\'ont pas pu être récupérés');
    }

    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
