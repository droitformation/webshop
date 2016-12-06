<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArretTest extends TestCase {

	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		$user = factory(App\Droit\User\Entities\User::class,'admin')->create();

		$user->roles()->attach(1);
		$this->actingAs($user);

		DB::beginTransaction();
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
		parent::tearDown();
	}

	public function testArretCreation()
	{
		$this->visit(url('admin/arrets/1'));
		$this->assertViewHas('arrets');

		$this->click('addArret');

		$this->seePageIs(url('admin/arret/create/1'));
	}

	public function testCreateNewArret()
	{
		$this->visit(url('admin/arret/create/1'));

		$data = [
			'site_id'    => 1,
			'reference'  => 'reference 123',
			'pub_date'   => \Carbon\Carbon::now(),
			'abstract'   => 'lorem ipsum dolor amet',
			'pub_text'   => 'amet dolor ipsum lorem',
			'categories' => []
		];

		$response = $this->call('POST', '/admin/arret', $data);

		$this->seeInDatabase('arrets', [
			'reference' => 'reference 123',
			'abstract'  => 'lorem ipsum dolor amet',
			'dumois'    => 0
		]);
	}

	public function testUpdateArret()
	{
		$arret = factory(App\Droit\Arret\Entities\Arret::class)->create();

		$this->visit(url('admin/arret/'.$arret->id));

		$response = $this->call('PUT', '/admin/arret/'.$arret->id, ['id' => $arret->id, 'dumois' => 1]);

		$this->seeInDatabase('arrets', [
			'id'     => $arret->id,
			'dumois' => 1
		]);
	}
}
