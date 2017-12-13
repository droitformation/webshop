<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnalyseTest extends BrowserKitTest {

	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		$user = factory(App\Droit\User\Entities\User::class)->create();

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

	public function testAnalyseCreationForm()
	{
		$this->visit('admin/analyses/1');
		$this->assertViewHas('analyses');

		$this->click('addAnalyse');

		$this->seePageIs(url('admin/analyse/create/1'));
	}

	public function testCreateNewAnalyse()
	{
		$this->visit('admin/arret/create/1');

		//Create a categorie
		$categorie = factory(App\Droit\Categorie\Entities\Categorie::class)->create();

		// Create an arret
		$arret   = factory(App\Droit\Arret\Entities\Arret::class)->create();
		$arret->categories()->attach([$categorie->id]);

		// Create an analyse
		$author  = factory(App\Droit\Author\Entities\Author::class)->create();

		$data = [
			'site_id'    => 1,
			'title'      => 'Un titre',
			'pub_date'   => \Carbon\Carbon::now(),
			'abstract'   => 'lorem ipsum dolor amet',
			'arrets'     => [$arret->id],
			'author_id'  => [$author->id]
		];

		$response = $this->call('POST', '/admin/analyse', $data);

		$this->seeInDatabase('analyses', [
			'title'     => 'Un titre',
			'abstract'  => 'lorem ipsum dolor amet',
			'site_id'    => 1,
		]);

		$content = $this->followRedirects()->response->getOriginalContent();
		$content = $content->getData();
		$analyse = $content['analyse'];

		$this->assertEquals(1, $analyse->arrets->count());
		$this->assertEquals(1, $analyse->authors->count());
	}

	public function testUpdateAnalyse()
	{
		$analyse = factory(App\Droit\Analyse\Entities\Analyse::class)->create();

		$author1  = factory(App\Droit\Author\Entities\Author::class)->create();
		$author2  = factory(App\Droit\Author\Entities\Author::class)->create();

		$analyse->authors()->attach([$author1->id]);

		$this->assertEquals(1, $analyse->authors->count());

		$this->visit('admin/analyse/'.$analyse->id);

		$response = $this->call('PUT', '/admin/analyse/'.$analyse->id, ['id' => $analyse->id, 'site_id' => 2, 'author_id' => [$author1->id, $author2->id]]);

		$this->seeInDatabase('analyses', [
			'id'      => $analyse->id,
			'site_id' => 2
		]);

		$content = $this->followRedirects()->response->getOriginalContent();
		$content = $content->getData();
		$analyse = $content['analyse'];
		
		$this->assertEquals(2, $analyse->authors->count());
		
	}

	public function testDeleteAnalyse()
	{
		$analyse = factory(App\Droit\Analyse\Entities\Analyse::class)->create();

		$this->visit('admin/analyses/1');

		$response = $this->call('DELETE','admin/analyse/'.$analyse->id);

		$this->notSeeInDatabase('analyses', [
			'id' => $analyse->id,
			'deleted_at' => null
		]);
	}
}
