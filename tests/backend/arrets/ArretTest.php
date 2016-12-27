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

	public function testArretCreationForm()
	{
		$this->visit(url('admin/arrets/1'));
		$this->assertViewHas('arrets');

		$this->click('addArret');

		$this->seePageIs(url('admin/arret/create/1'));
	}

	public function testCreateNewArret()
	{
		$this->visit(url('admin/arret/create/1'));

		// Create an analyse
		$author  = factory(App\Droit\Author\Entities\Author::class)->create();
		$analyse = factory(App\Droit\Analyse\Entities\Analyse::class)->create();

		//Create a categorie
		$categorie = factory(App\Droit\Categorie\Entities\Categorie::class)->create();
		
		$analyse->authors()->attach([$author->id]);

		$data = [
			'site_id'    => 1,
			'reference'  => 'reference 123',
			'pub_date'   => \Carbon\Carbon::now(),
			'abstract'   => 'lorem ipsum dolor amet',
			'pub_text'   => 'amet dolor ipsum lorem',
			'dumois'     => 1,
			'categories' => [$categorie->id],
			'analyses'   => [$analyse->id]
		];

		$response = $this->call('POST', '/admin/arret', $data);

		$this->seeInDatabase('arrets', [
			'reference' => 'reference 123',
			'abstract'  => 'lorem ipsum dolor amet',
			'dumois'    => 1
		]);

		$content = $this->followRedirects()->response->getOriginalContent();
		$content = $content->getData();
		$arret   = $content['arret'];

		$this->assertEquals(1, $arret->categories->count());
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

	public function testDeleteArret()
	{
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

		$arret = factory(App\Droit\Arret\Entities\Arret::class)->create();

		$this->visit(url('admin/arrets/1'));

        $response = $this->call('DELETE','admin/arret/'.$arret->id, [] ,['id' => $arret->id]);

		$this->notSeeInDatabase('arrets', [
			'id' => $arret->id,
            'deleted_at' => null
        ]);
	}
}
