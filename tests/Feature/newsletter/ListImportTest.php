<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListImportTest extends TestCase
{
    use RefreshDatabase;

    protected $worker;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\ImportWorkerInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\ImportWorkerInterface', $this->worker);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testSendToList()
    {
        \DB::table('newsletter_lists')->truncate();

        $liste      = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create();
        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create();

        $this->worker->shouldReceive('send')->once()->andReturn(false);

        $response = $this->call('POST', 'build/send/list', ['list_id' => $liste->id, 'campagne_id' => $campagne->id]);

        $response->assertSessionHas('alert.style','success');
        $response->assertSessionHas('alert.message','Campagne envoyé à la liste! Contrôler l\'envoi via le tracking (après quelques minutes) ou sur le service externe mailjet.');
    }

}
