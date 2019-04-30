<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class CampagneErrorTest extends TestCase
{
    use RefreshDatabase,ResetTbl;
    
    protected $mock;
    protected $worker;
    protected $mailjet;
    protected $campagne;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->mock = \Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->mailjet = \Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->mailjet);

        $this->worker = \Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->worker);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testCreateCampagneFails()
    {
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create();
        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id]);

        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('createCampagne')->once()->andReturn(null);

        $response = $this->call('get','build/campagne/create/'.$campagne->id);
        $response = $this->call('POST', 'build/campagne', ['sujet' => 'Sujet', 'auteurs' => 'Cindy Leschaud', 'newsletter_id' => $newsletter->id]);
        $campagne = $campagne->fresh();

        $this->assertDatabaseHas('newsletter_campagnes', [
            'id' => $campagne->id,
            'deleted_at' => $campagne->deleted_at
        ]);

        $response->assertRedirect('build/campagne/create/'.$campagne->id);

    }
}
