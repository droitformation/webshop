<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase {
    
    protected $worker;

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $this->worker = Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
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
     * Inscription from frontend
     * @return void
     */
    public function testRegisterInscription()
    {
        $this->WithoutEvents();
        $this->withoutJobs();

        $make  = new \tests\factories\ObjectFactory();
        $user  = $make->user();
        $this->actingAs($user);

        $colloque = $make->colloque();

        $input = ['type' => 'simple', 'colloque_id' => $colloque->id, 'user_id' => $user->id, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make();

        $this->worker->shouldReceive('register')->once()->andReturn($inscription);

        $response = $this->call('POST', 'pubdroit/registration', $input);

        $this->assertRedirectedTo('pubdroit');
    }
}
