<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {

	protected $adresse;
	protected $user;

	public function setUp()
	{
		parent::setUp();

		$user = App\Droit\User\Entities\User::find(710);

		$this->actingAs($user);
	}

	/**
	 * @return void
	 */
	public function testProfilUser()
	{
		$this->visit('/')->click('Mon compte')->seePageIs('/profil');
		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testProfilCommandesUser()
	{
		$this->visit('/profil/orders')->seePageIs('/profil/orders');
		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testProfilInscriptionsUser()
	{
		$this->visit('/profil/colloques')->seePageIs('/profil/colloques');
		$this->assertViewHas('user');
	}
}
