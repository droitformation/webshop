<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class InscriptionTest extends BrowserKitTest {

    protected $mock;
    protected $colloque;
    protected $groupe;
    protected $interface;
    protected $worker;

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $this->mock = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->mock);

        $this->groupe = Mockery::mock('App\Droit\Inscription\Repo\GroupeInterface');
        $this->app->instance('App\Droit\Inscription\Repo\GroupeInterface', $this->groupe);

        $this->worker = Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    /**
     *
     * @return void
     */
    public function testRegisterNewInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $input = ['type' => 'simple', 'colloque_id' => $colloque->id, 'user_id' => 1, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make();
   
        $this->worker->shouldReceive('register')->once()->andReturn($inscription);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('POST', '/admin/inscription', $input);

        $this->assertRedirectedTo('/admin/inscription/colloque/'.$colloque->id);
    }

    /**
     *
     * @return void
     */
    public function testRegisterMultipleNewInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $input = ['type' => 'multiple', 'colloque_id' => $colloque->id, 'user_id' => 1, 'participant' => ['Jane Doe', 'John Doa'], 'price_id' => [290, 290] ];

        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make();
        
        $this->worker->shouldReceive('register')->once()->andReturn($group);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('POST', '/admin/inscription',$input);

        $this->assertRedirectedTo('/admin/inscription/colloque/'.$colloque->id);

    }

    /**
     * Inscription update from admin
     * @return void
     */
    public function testUpdateInscription()
    {
        $user = factory(App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $input = ['id' => 3, 'colloque_id' => 39, 'user_id' => $user->id, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make($input);

        $this->mock->shouldReceive('update')->once()->andReturn($inscription);
        $this->worker->shouldReceive('makeDocuments')->once();

        $this->visit('/admin/user/'.$user->id);

        $response = $this->call('PUT', 'admin/inscription/3', $input);

        $this->assertRedirectedTo('/admin/user/'.$user->id);
    }
}
