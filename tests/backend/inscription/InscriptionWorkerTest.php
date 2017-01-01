<?php

class InscriptionWorkerTest extends TestCase {

    protected $inscription;
    protected $colloque;
    protected $adresse;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $this->inscription = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->inscription);

        $this->colloque = Mockery::mock('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->app->instance('App\Droit\Colloque\Repo\ColloqueInterface', $this->colloque);

        $this->adresse = Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
        $this->app->instance('App\Droit\Adresse\Repo\AdresseInterface', $this->adresse);

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

    public function testUpdateInscription()
    {
        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
            'id'          => '10',
            'user_id'     => '1',
            'colloque_id' => '12'
        ]);

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);
        $colloque->annexe = ['bon','facture','bv'];

        $inscription->colloque = $colloque;
        $inscription->user     = \App\Droit\User\Entities\User::find(1);

        $this->inscription->shouldReceive('update')->once();

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->updateInscription($inscription);

    }

}
