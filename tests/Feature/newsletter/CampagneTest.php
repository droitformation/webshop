<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class CampagneTest extends TestCase
{
    use RefreshDatabase,ResetTbl;
    
    protected $mock;
    protected $worker;
    protected $mailjet;
    protected $campagne;

    public function setUp(): void
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

        $this->campagne = \Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     *
     * @return void
     */
    public function testNewsletterCreationPage()
    {
        $this->mailjet->shouldReceive('getAllLists')->once()->andReturn([]);

        $this->get('build/newsletter/create');
        $this->assertTrue(true);
    }

    /**
     *
     * @return void
     */
    public function testSendCampagne()
    {
        setMaj('2020-01-19','hub');

        $response = $this->call('GET', 'hub/maj');
        $response->assertStatus(200);

        $this->assertEquals('2020-01-19',$response->json('date'));

        $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->campagne->shouldReceive('update')->once();
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

        $this->mailjet->shouldReceive('updateSubject')->once()->andReturn(true);
        $this->mailjet->shouldReceive('setHtml')->once()->andReturn(true);
        $this->mailjet->shouldReceive('setList')->once()->andReturn(true);
        $this->mailjet->shouldReceive('sendCampagne')->once()->andReturn(['success' => true]);

        $response = $this->call('POST', 'build/send/campagne', ['id' => '1']);

        $response->assertRedirect('build/newsletter');

        $response = $this->call('GET', 'hub/maj');
        $today = \Carbon\Carbon::now()->toDateString();

        $this->assertEquals($today,$response->json('date'));
    }

    public function testSendCampagneFailedHtml()
    {
        $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();
        // some code that is supposed to throw ExceptionOne
        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

        $this->mailjet->shouldReceive('setList')->once()->andReturn(true);
        $this->mailjet->shouldReceive('updateSubject')->once()->andReturn(true);
        $this->mailjet->shouldReceive('setHtml')->once()->andReturn(false);

        $response = $this->call('POST', 'build/send/campagne', ['id' => '1']);

        $response->assertStatus(500);

        //$this->expectException('\App\Exceptions\CampagneSendException');
    }

    public function testSendCampagneFailed()
    {
        $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $result = [
            'success' => false,
            'info'    => ['ErrorMessage' => '','StatusCode' => '']
        ];

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

        $this->mailjet->shouldReceive('setList')->once()->andReturn(true);
        $this->mailjet->shouldReceive('updateSubject')->once()->andReturn(true);
        $this->mailjet->shouldReceive('setHtml')->once()->andReturn(true);
        $this->mailjet->shouldReceive('sendCampagne')->once()->andReturn($result);

        $response = $this->call('POST', 'build/send/campagne', ['id' => '1']);

        $response->assertStatus(302);
        //$this->expectException('\App\Exceptions\CampagneSendException');
    }

    /**
     *
     * @return void
     */
    public function testSendTestCampagne()
    {
        $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
        $this->mailjet->shouldReceive('sendBulk')->once()->andReturn(['Sent' => []]);

        $response = $this->call('POST', 'build/send/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);

        $response->assertRedirect('build/campagne/'.$campagne->id);

    }

    public function testSendTestFailed()
    {
        $campagne = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $result = [];

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
        $this->mailjet->shouldReceive('sendBulk')->once()->andReturn($result);

        $response = $this->call('POST', 'build/send/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);
        $response->assertStatus(500);

        //$this->expectException('\App\Exceptions\TestSendException');
    }

    public function testCreateCampagne()
    {
        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make();

        $campagne->newsletter = $newsletter;

        $this->campagne->shouldReceive('create')->once()->andReturn($campagne);
        $this->campagne->shouldReceive('update')->once()->andReturn($campagne);
        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('createCampagne')->once()->andReturn(1);

        $response = $this->call('POST', 'build/campagne', ['sujet' => 'Sujet', 'auteurs' => 'Cindy Leschaud', 'newsletter_id' => '3']);

        $response->assertRedirect('build/campagne/compose/'.$campagne->id);
    }
}
