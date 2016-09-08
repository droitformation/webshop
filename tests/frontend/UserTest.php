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
		$make = new \tests\factories\ObjectFactory();
		$colloque = $make->colloque();

		$prices = $colloque->prices->pluck('id')->all();

		$user = factory(App\Droit\User\Entities\User::class,'admin')->create();
		$this->actingAs($user);

		$this->visit('/colloque/'.$colloque->id);
		$this->assertViewHas('colloque');

		$this->assertCount(1, $this->visit('/colloque/'.$colloque->id)
			->crawler->filter('input[name="price_id"][value="'.$prices[0].'"]'));

		$this->assertCount(1, $this->visit('/colloque/'.$colloque->id)
			->crawler->filter('input[name="user_id"][value="'.$user->id.'"]'));

		$this->assertCount(1, $this->visit('/colloque/'.$colloque->id)
			->crawler->filter('input[name="colloque_id"][value="'.$colloque->id.'"]'));


        $this->select($prices[0], 'price_id');

        $this->press('Envoyer');

		$this->seeInDatabase('colloque_inscriptions', [
			'colloque_id' => $colloque->id,
			'user_id'     => $user->id,
			'price_id'    => 1
		]);

        $this->seePageIs('/');
	}
}
