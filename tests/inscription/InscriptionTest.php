<?php

class InscriptionTest extends TestCase {

    protected $mock;
    protected $interface;

    public function setUp()
    {
        parent::setUp();

        $this->refreshApplication();

        $this->mock = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->mock);

        $user = App\Droit\User\Entities\User::find(1);

        $this->actingAs($user);
    }

    public function tearDown()
    {
         Mockery::close();
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
        $this->expectsEvents(App\Events\InscriptionWasCreated::class);

        $input = ['type' => 'simple', 'colloque_id' => 71, 'user_id' => 1, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $this->mock->shouldReceive('create')->once()->with($input);

        $response = $this->call('POST', '/admin/inscription', $input);

        echo '<pre>';
        print_r($response->getContent());
        echo '</pre>';exit;

        $this->assertRedirectedTo('/admin/inscription/colloque/71');

/*        $response = $this->call('POST', '/admin/inscription', ['type' => 'multiple', 'colloque_id' => 71, 'participant' => ['Jane Doe', 'John Doa'], 'price_id' => [290, 290] ]);

        $this->assertRedirectedTo('/admin/inscription/colloque/71');*/

	}

}
