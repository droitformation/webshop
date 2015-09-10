<?php

class InscriptionTest extends TestCase {

    protected $user;
    protected $interface;

    public function setUp()
    {
        parent::setUp();

       // $this->mock      = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->interface = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->user      = \App::make('App\Droit\User\Repo\UserInterface');
    }

    public function tearDown()
    {
       // Mockery::close();
        \Eloquent::unguard();
        \DB::table('colloque_inscriptions')->delete();
        \DB::table('colloque_inscriptions_groupes')->delete();
        \DB::table('colloque_option_users')->delete();

    }

    /**
	 *
	 * @return void
	 */
	public function testRegisterNewInscription()
	{
        $user = $this->user->find(1);

        $this->actingAs($user);

        $response = $this->call('POST', '/admin/inscription', ['type' => 'simple', 'colloque_id' => 71, 'user_id' => 1, 'inscription_no' => '71-2015/1', 'price_id' => 290]);

        $this->assertRedirectedTo('/admin/inscription/colloque/71');

        $response = $this->call('POST', '/admin/inscription', ['type' => 'multiple', 'colloque_id' => 71, 'participant' => ['Jane Doe'], 'price_id' => [290] ]);

        $this->assertRedirectedTo('/admin/inscription/colloque/71');

	}

}
