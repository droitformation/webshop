<?php

namespace Tests\Feature\backend\inscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;
use Tests\TestFlashMessages;

class FeatureInscriptionTest extends TestCase
{
    use RefreshDatabase,ResetTbl,TestFlashMessages;

    protected $mock;
    protected $groupe;
    protected $worker;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->mock = \Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->mock);

        $this->groupe = \Mockery::mock('App\Droit\Inscription\Repo\GroupeInterface');
        $this->app->instance('App\Droit\Inscription\Repo\GroupeInterface', $this->groupe);

        $this->worker = \Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testRegisterNewInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $user     = $make->makeUser([]);

        $input = [
            'type'           => 'simple',
            'colloque_id'    => $colloque->id,
            'user_id'        => $user->id,
            'inscription_no' => '71-2015/1',
            'price_id'       => 'price_id:'.$colloque->prices->first()->id,
        ];

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create();

        $this->worker->shouldReceive('register')->once()->andReturn($inscription);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('POST', '/admin/inscription', $input);

        $response->assertRedirect('/admin/inscription/colloque/'.$colloque->id);
    }

    /**
     *
     * @return void
     */
    public function testRegisterMultipleNewInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();
        $prices   = $colloque->prices->pluck('id')->all();

        $input = [
            'colloque_id' => $colloque->id ,
            'user_id'     => $person->id,
            'type'        => 'multiple',
            'participant' => [
                'Test Droitformation',
                'John McDuck'
            ],
            'email' => [
                'droitformation.web@gmail.com',
                'john.mcduck@hotmail.com'
            ],
            'price_id'     => [
                "price_link_id:".$prices[0],
                "price_link_id:".$prices[0]
            ],
            'colloques' => [
                [$colloque->id],
                [$colloque->id]
            ]
        ];

        $group = factory(\App\Droit\Inscription\Entities\Groupe::class)->make();

        $this->worker->shouldReceive('register')->once()->andReturn($group);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('POST', '/admin/inscription',$input);
        $response->assertRedirect('/admin/inscription/colloque/'.$colloque->id);

    }

    /**
     * Inscription update from admin
     * @return void
     */
    public function testUpdateInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $user     = $make->makeUser([]);
        $inscription = $make->makeInscriptionForUser($user, \Carbon\Carbon::today()->toDateTimeString());

        $input = [
            'id' => $inscription->id,
            'colloque_id' => $inscription->colloque_id,
            'user_id' => $inscription->user_id,
            'inscription_no' => $inscription->inscription_no,
            'price_id' => 'price_id:'.$inscription->price_id,
        ];

        $this->mock->shouldReceive('update')->once()->andReturn($inscription);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('PUT', 'admin/inscription/'.$inscription->id, $input);

        $this->assertCount(1, $this->flashMessagesForMessage('L\'inscription a été mise à jour'));
    }
}
