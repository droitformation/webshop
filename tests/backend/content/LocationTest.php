<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;

class LocationTest extends BrowserKitTest {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
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
	public function testLocationList()
	{
        $this->visit('admin/location')->see('Lieux');
        $this->assertViewHas('locations');
	}

     public function testLocationCreate()
     {
          $this->visit('admin/location')->click('addLocation');
          $this->seePageIs('admin/location/create');
  
          $this->type('Un lieux', 'name')->type('<p>Une adresse</p>', 'adresse')->press('Envoyer');
  
          $this->seeInDatabase('locations', [
              'name'    => 'Un lieux',
              'adresse' => '<p>Une adresse</p>',
          ]);
      }

    public function testUpdateLocation()
    {
        $location = factory(App\Droit\Location\Entities\Location::class)->create();

        $this->visit('admin/location/'.$location->id)->see($location->name);

        $this->type('Un autre lieux', 'name')->type('<p>Une adresse</p>', 'adresse')->press('Envoyer');

        $this->seeInDatabase('locations', [
            'name'    => 'Un autre lieux',
            'adresse' => $location->adresse,
        ]);
    }

    public function testDeleteLocation()
    {
        $location = factory(App\Droit\Location\Entities\Location::class)->create();

        $this->visit('admin/location/'.$location->id)->see($location->name);

        $response = $this->call('DELETE','admin/location/'.$location->id);

        $this->notSeeInDatabase('locations', [
            'id' => $location->id,
        ]);
    }
}
