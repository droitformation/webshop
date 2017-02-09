<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExportTest extends BrowserKitTest {

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
	public function testExport()
	{
		$repo = App::make('App\Droit\Adresse\Repo\AdresseInterface');

		$infos = [
			['canton' => 6, 'profession' => 1],
			['canton' => 6, 'profession' => 2],
			['canton' => 8, 'profession' => 3],
			['canton' => 10, 'profession' => 1],
			['canton' => 10, 'profession' => 2],
			['canton' => 10, 'profession' => 1]
		];

		$make  = new \tests\factories\ObjectFactory();
		$users = $make->user($infos);

		/******** Search with all cantons **************/

		$results = $repo->searchMultiple(['cantons' => [6,8]], true);
		$cantons = $results->pluck('canton_id')->unique()->values()->all();

		$this->assertEquals([6,8], $cantons);
		$this->assertTrue(!in_array(3,$cantons));

		/********** Search with all profession ************/

		$results     = $repo->searchMultiple(['professions' => [1,2]], true);
		$professions = $results->pluck('profession_id')->unique()->values()->all();

		$this->assertEquals([1,2], $professions);
		$this->assertTrue(!in_array(3,$professions));

		/********* All results *************/

		$results     = $repo->searchMultiple(['cantons' => [6,10], 'professions' => [1]], true);
		$professions = $results->pluck('profession_id')->unique()->values()->all();
		$cantons     = $results->pluck('canton_id')->unique()->values()->all();

		$this->assertEquals(3, $results->count());
		$this->assertEquals([6,10], $cantons);
		$this->assertEquals([1], $professions);

		/********* Cross results *************/

		$results     = $repo->searchMultiple(['cantons' => [6], 'professions' => [2]],false);
		$professions = $results->pluck('profession_id')->unique()->values()->all();
		$cantons     = $results->pluck('canton_id')->unique()->values()->all();

		$this->assertEquals(1, $results->count());
		$this->assertEquals([6], $cantons);
		$this->assertEquals([2], $professions);
	}

	public function testExportAll()
	{
		$repo = App::make('App\Droit\Adresse\Repo\AdresseInterface');
		$make  = new \tests\factories\ObjectFactory();

		$specialisations = $make->items('Specialisation', 2);
		$specs = $specialisations->pluck('id')->all();

		$members = $make->items('Member', 2);
		$mem     = $members->pluck('id')->all();

		$infos = [
			['canton' => 6,  'profession' => 1],
			['canton' => 6,  'profession' => 2],
			['canton' => 8,  'profession' => 3],
			['canton' => 10, 'profession' => 1, 'specialisations' => $specs],
			['canton' => 11, 'profession' => 2, 'members' => $mem, 'specialisations' => $specs],
			['canton' => 10, 'profession' => 1]
		];

		$users = $make->user($infos);

		$results = $repo->searchMultiple(['cantons' => [10], 'specialisations' => $specs], true);
		$this->assertEquals(1, $results->count());

		$results = $repo->searchMultiple(['cantons' => [10,11], 'specialisations' => $specs], true);
		$this->assertEquals(2, $results->count());

		$results = $repo->searchMultiple(['cantons' => [11], 'specialisations' => $specs, 'members' => $mem], false);
		$this->assertEquals(1, $results->count());

		$results = $repo->searchMultiple(['cantons' => [10], 'specialisations' => $specs, 'members' => $mem], false);
		$this->assertEquals(0, $results->count());

		$results = $repo->searchMultiple(['professions' => [2] ,'cantons' => [11], 'specialisations' => [$specs[0]], 'members' => $mem], false);
		$this->assertEquals(1, $results->count());

		$results = $repo->searchMultiple(['professions' => [1] ,'cantons' => [11], 'members' => [$mem[0]]], false);
		$this->assertEquals(0, $results->count());
	}

	public function testSearchUser()
	{
		$user = factory(App\Droit\User\Entities\User::class)->create();
		$user->roles()->attach(1);
		$this->actingAs($user);

		$make = new \tests\factories\ObjectFactory();
		$adresse = $make->user();

		$this->visit('/admin/search/user')->see('Rechercher');

        $response = $this->call('POST', '/admin/search/user',['term' => $adresse->first_name]);

		$content = $response->getOriginalContent();
		$content = $content->getData();

		$result = $content['users'];

		$names = $result->pluck('name')->unique()->values()->all();

		$this->assertEquals([$adresse->first_name.' '.$adresse->last_name], $names);
	}
}
