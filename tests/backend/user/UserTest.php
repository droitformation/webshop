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

	/*	$this->user = Mockery::mock('App\Droit\User\Repo\UserInterface');
		$this->app->instance('App\Droit\User\Repo\UserInterface', $this->user);

		$this->adresse = Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
		$this->app->instance('App\Droit\Adresse\Repo\AdresseInterface', $this->adresse);*/

		$user = App\Droit\User\Entities\User::find(710);

		$this->actingAs($user);
	}

	/**
	 * @return void
	 */
	public function testProfilUser()
	{
		$this->visit('/')->click('Mon compte')->seePageIs('/profil');
	}

	/**
	 * @return void
	 */
	public function testAdmin()
	{
		$this->visit('/admin')->see('Admin');;
	}
}
