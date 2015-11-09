<?php

class AdresseTest extends TestCase {

    protected $adresse;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Mockery::mock('App\Droit\User\Repo\UserInterface');
        $this->app->instance('App\Droit\User\Repo\UserInterface', $this->user);

        $this->adresse = Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
        $this->app->instance('App\Droit\Adresse\Repo\AdresseInterface', $this->adresse);

        $user = App\Droit\User\Entities\User::find(1);
        $this->be($user);
    }

    public function tearDown()
    {
        Mockery::close();
    }

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testConvertAdresseToUser()
	{
        $input = ['password' => 'cindy2'];

        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->make(['id' => 2]);

        $user = factory(App\Droit\User\Entities\User::class)->make(['id' => 2]);
        $user = Mockery::mock($user);

        $this->adresse->shouldReceive('find')->once()->andReturn($adresse);
        $this->user->shouldReceive('create')->once()->andReturn($user);
        $this->adresse->shouldReceive('update')->once();

        $response = $this->call('POST', 'admin/adresse/convert', $input);

        $this->assertRedirectedTo('admin/user/2');

	}

}
