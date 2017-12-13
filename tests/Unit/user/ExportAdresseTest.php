<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ExportAdresseTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testExport()
    {
        $repo = \App::make('App\Droit\Adresse\Repo\AdresseInterface');

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
        $repo = \App::make('App\Droit\Adresse\Repo\AdresseInterface');
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
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $make = new \tests\factories\ObjectFactory();
        $adresse = $make->user();

        $repo = \App::make('App\Droit\Adresse\Repo\AdresseInterface');

        $adresses = $repo->search($adresse->first_name);

        $results = $adresses->groupBy(function ($adresse, $key) {
            return $adresse->user_id > 0 &&  isset($adresse->user) ? 'users' : 'adresses';
        })->map(function ($items, $key) {
            return $items->map(function ($item, $i) use ($key) {
                return $key == 'users' ? $item->user : $item;
            })->unique('id');
        });

        $users    = isset($results['users']) ? $results['users'] : collect([]);
        $adresses = isset($results['adresses']) ? $results['adresses'] : collect([]);

        $names = $users->pluck('name')->unique()->values()->all();

        $this->assertTrue(in_array($adresse->first_name.' '.$adresse->last_name,$names));
    }

    public function testSearchUserDeleted()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $user->delete(); // delete user

        $repo = \App::make('App\Droit\Adresse\Repo\AdresseInterface');

        $adresses = $repo->search($user->first_name);

        $results = $adresses->groupBy(function ($adresse, $key) {
            return $adresse->user_id > 0 &&  isset($adresse->user) ? 'users' : 'adresses';
        })->map(function ($items, $key) {
            return $items->map(function ($item, $i) use ($key) {
                return $key == 'users' ? $item->user : $item;
            })->unique('id');
        });

        $users    = isset($results['users']) ? $results['users'] : collect([]);
        $adresses = isset($results['adresses']) ? $results['adresses'] : collect([]);

        // There should be no adresse in results because the user has been deleted and the adresse shouldn't apprear
        $this->assertTrue($users->isEmpty());
        $this->assertTrue($adresses->isEmpty());
    }

    public function testExportWithoutDeletedUsersAdresse()
    {
        $repo = \App::make('App\Droit\Adresse\Repo\AdresseInterface');
        $make = new \tests\factories\ObjectFactory();

        $specs   = $make->items('Specialisation', 2)->pluck('id')->all();
        $members = $make->items('Member', 2)->pluck('id')->all();

        $infos = [
            ['canton' => 10, 'profession' => 1, 'members' => $members, 'specialisations' => $specs],
            ['canton' => 10, 'profession' => 1, 'members' => $members, 'specialisations' => $specs],
            ['canton' => 10, 'profession' => 1, 'members' => $members, 'specialisations' => $specs], // will be deleted
        ];

        $users = $make->user($infos);

        $last    = $users->pop();
        $adresse = $last->adresses->first();

        $last->delete(); // delete one user

        $results = $repo->searchMultiple(['cantons' => [10], 'specialisations' => $specs, 'members' => $members], false);
        $this->assertEquals(2, $results->count());

    }

    public function testPrepareAdresse()
    {
        $exporter = new \App\Droit\Generate\Export\ExportAdresse();

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->make([
            'civilite_id'   => 2,
            'first_name'    => 'Cindy',
            'last_name'     => 'Leschaud',
            'email'         => 'cindy.leschaud@gmail.com',
            'company'       => 'DesignPond',
            'profession_id' => 1,
            'telephone'     => '032 690 00 23',
            'mobile'        => '032 690 00 23',
            'fax'           => null,
            'adresse'       => 'Ruelle de l\'hôtel de ville 3',
            'npa'           => '2520',
            'ville'         => 'La Neuveville',
            'canton_id'     => 6,
            'pays_id'       => 208,
        ]);

        $adresses = collect([$adresse]);

        $converted = $exporter->prepareAdresse($adresses);
        $converted = $converted->toArray();

        $expect = [
            'Madame', 'Cindy','Leschaud', 'cindy.leschaud@gmail.com', 'Avocat', 'DesignPond', '032 690 00 23', '032 690 00 23',
            'Ruelle de l\'hôtel de ville 3', '', '', '2520', 'La Neuveville', 'Berne (BE)', 'Suisse',
        ];

        $this->assertEquals($expect, $converted[0]);

    }
}
