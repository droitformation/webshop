<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {

    use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

        DB::beginTransaction();
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
        parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testProfilUser()
	{
		$user = factory(App\Droit\User\Entities\User::class,'admin')->create();
		
		$this->assertFalse(Auth::check());

        $this->visit( route('login') )
            ->submitForm('Envoyer', [
                'email'    => 'cindy.leschaud@unine.ch',
                'password' => 'cindy2',
            ])
            ->click('Mon compte')
            ->seePageIs(url('/profil'));

        $this->assertTrue(Auth::check());

		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testProfilCommandesUser()
	{
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $this->actingAs($user);

		$this->visit('/profil/orders')->seePageIs('/profil/orders');
		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testProfilInscriptionsUser()
	{
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $this->actingAs($user);
        
		$this->visit('/profil/colloques')->seePageIs('/profil/colloques');
		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testInscriptionColloque()
	{
		$user = factory(App\Droit\User\Entities\User::class,'admin')->create();
		$this->actingAs($user);

		$this->visit('/colloque/1');
		$this->assertViewHas('colloque');

		$this->select(1, 'price_id')->press('Envoyer');

		$this->seeInDatabase('colloque_inscriptions', [
			'colloque_id' => 1,
			'user_id'     => $user->id,
			'price_id' => 1
		]);

        $this->seePageIs('/');

	}
}
