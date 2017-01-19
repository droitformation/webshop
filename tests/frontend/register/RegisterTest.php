<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase {
    
    protected $worker;

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        //$this->worker = Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        //$this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

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
    public function testCanRegister()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque();
        $user     = $make->makeUser();

        $this->actingAs($user);
        $date = \Carbon\Carbon::now()->subDays(32)->toDateString();

        $inscription1 = $make->makeInscriptionForUser($user, $date);
        $inscription2 = $make->makeInscriptionForUser($user, $date);

        $this->visit('/pubdroit/colloque/'.$colloque->id);
        $this->assertViewHas('colloque');

        $this->see($colloque->title);
    }

    public function testCantRegisterHasRappel()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque();
        $user     = $make->makeUser();

        $this->actingAs($user);
        $date = \Carbon\Carbon::now()->subDays(32)->toDateString();

        $inscription1 = $make->makeInscriptionForUser($user, $date);
        $inscription2 = $make->makeInscriptionForUser($user, $date);

        $inscription1->rappels()->create([
            'user_id'     => $user->id,
            'colloque_id' => $inscription1->colloque_id,
        ]);

        $inscription2->rappels()->create([
            'user_id'      => $user->id,
            'colloque_id'  => $inscription2->colloque_id,
        ]);

        $this->visit('/pubdroit/colloque/'.$colloque->id);
        $this->assertViewHas('colloque');

        $this->see('Vous avez des payements en attente, veuillez contacter le secrétariat: droit.formation@unine.ch');

    }
}
