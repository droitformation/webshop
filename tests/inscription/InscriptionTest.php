<?php

class InscriptionTest extends TestCase {

    protected $mock;
    protected $groupe;
    protected $interface;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->mock);

        $this->groupe = Mockery::mock('App\Droit\Inscription\Repo\GroupeInterface');
        $this->app->instance('App\Droit\Inscription\Repo\GroupeInterface', $this->groupe);

        $user = App\Droit\User\Entities\User::find(1);

        $this->actingAs($user);
    }

    public function tearDown()
    {
         \Mockery::close();
/*
        \Eloquent::unguard();
        \DB::table('colloque_inscriptions')->delete();
        \DB::table('colloque_inscriptions_groupes')->delete();
        \DB::table('colloque_option_users')->delete();
*/
    }

    /**
	 *
	 * @return void
	 */
	public function testRegisterNewInscription()
	{
        $this->WithoutEvents();

        $input = ['type' => 'simple', 'colloque_id' => 71, 'user_id' => 1, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = new \App\Droit\Inscription\Entities\Inscription();

        $this->mock->shouldReceive('getByUser')->once();
        $this->mock->shouldReceive('create')->once()->with($input)->andReturn($inscription);

        $response = $this->call('POST', '/admin/inscription', $input);

        $this->assertRedirectedTo('/admin/inscription/colloque/71');

	}

    /**
     *
     * @return void
     */
    public function testRegisterMultipleNewInscription()
    {

        $this->WithoutEvents();

        $input = ['type' => 'multiple', 'colloque_id' => 71, 'user_id' => 1, 'participant' => ['Jane Doe', 'John Doa'], 'price_id' => [290, 290] ];

        $inscription = new \App\Droit\Inscription\Entities\Inscription();
        $group       = new \App\Droit\Inscription\Entities\Groupe();
        $group->id   = 1;

        $this->groupe->shouldReceive('create')->andReturn($group);
        $this->mock->shouldReceive('create')->times(2)->andReturn($inscription);

        $response = $this->call('POST', '/admin/inscription',$input);

        $this->assertRedirectedTo('/admin/inscription/colloque/71');

    }

    public function testLastInscriptions()
    {
        $inscription1 = factory(\App\Droit\Inscription\Entities\Inscription::class)->make();
        $inscription2 = factory(\App\Droit\Inscription\Entities\Inscription::class)->make();

        $inscriptions = new Illuminate\Support\Collection(
            array(  $inscription1, $inscription2 )
        );

        $this->mock->shouldReceive('getAll')->once()->andReturn($inscriptions);

        $response = $this->call('GET', 'admin/inscription');

        $this->assertViewHas('inscriptions');
    }

}
