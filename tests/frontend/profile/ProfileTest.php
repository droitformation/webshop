<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileTest extends BrowserKitTest {

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
		Honeypot::disable();

		$user = factory(App\Droit\User\Entities\User::class)->create([
			'email'    => 'cindy.leschaud@unine.ch',
			'password' => bcrypt('cindy2')
		]);
		
		$this->assertFalse(Auth::check());

        $this->visit('login')
            ->submitForm('Envoyer', [
                'email'    => 'cindy.leschaud@unine.ch',
                'password' => 'cindy2',
            ])
            ->click('Mon compte')
            ->seePageIs(url('/pubdroit/profil'));

        $this->assertTrue(Auth::check());

		$this->assertViewHas('user');
	}

	public function testUpdateAccountProfilUser()
	{
		Honeypot::disable();

		$user = factory(App\Droit\User\Entities\User::class)->create([
			'email'    => 'cindy.leschaud@unine.ch',
			'password' => bcrypt('cindy2')
		]);

		$this->actingAs($user);

		$this->visit('/pubdroit/profil');
		$this->assertViewHas('user');

		$this->type('Cindy','first_name');
		$this->press('saveAccount');

		$this->seeInDatabase('users', [
			'id'         => $user->id,
			'first_name' => 'Cindy'
		]);
	}

	public function testUpdateAdresseLivraison()
	{
		$make     = new \tests\factories\ObjectFactory();
		$person   = $make->makeUser();

		$this->actingAs($person);

		// Second adresse
		$adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
			'civilite_id'   => 2,
			'first_name'    => $person->first_name,
			'last_name'     => $person->last_name,
			'email'         => $person->email,
			'user_id'       => $person->id,
			'livraison'     => null
		]);

		$person->load('adresses');

		$adresse_livraison = $person->adresses->where('livraison',1);

		$this->assertEquals(1, $adresse_livraison->count());

		$edit = $person->adresses->first(function ($adresse, $key) {
			return $adresse->livraison == null;
		});

		$original = $person->adresses->first(function ($adresse, $key) {
			return $adresse->livraison == 1;
		});

		$this->visit('pubdroit/profil');

		$response = $this->call('PUT','pubdroit/profil/update', [
			'id'         => $edit->id,
			'livraison'  => 1,
			'user_id'    => $edit->user_id,
			'first_name' => $edit->first_name,
			'last_name'  => $edit->last_name,
			'adresse'    => $edit->adresse,
			'npa'        => $edit->npa,
			'ville'      => $edit->ville,
		]);

		// Make sur the livraison adresse has been changed
		$this->seeInDatabase('adresses', [
			'id'        => $original->id,
			'livraison' => null
		]);

		$this->seeInDatabase('adresses', [
			'id'        => $edit->id,
			'livraison' => 1
		]);

		// Re change livraison adresse
		$response = $this->call('PUT','pubdroit/profil/update', [
			'id'         => $original->id,
			'livraison'  => 1,
			'user_id'    => $original->user_id,
			'first_name' => $original->first_name,
			'last_name'  => $original->last_name,
			'adresse'    => $original->adresse,
			'npa'        => $original->npa,
			'ville'      => $original->ville,
		]);

		// Make sur the livraison adresse has been changed
		$this->seeInDatabase('adresses', [
			'id'        => $original->id,
			'livraison' => 1
		]);

		$this->seeInDatabase('adresses', [
			'id'        => $edit->id,
			'livraison' => null
		]);

	}

	/**
	 * @return void
	 */
	public function testProfilCommandesUser()
	{
		$user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

		$this->visit('/pubdroit/profil/orders')->seePageIs('/pubdroit/profil/orders');
		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testProfilInscriptionsUser()
	{
		$user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);
        
		$this->visit('/pubdroit/profil/colloques')->seePageIs('/pubdroit/profil/colloques');
		$this->assertViewHas('user');
	}

	/**
	 * @return void
	 */
	public function testInscriptionColloque()
	{
		$this->withoutEvents();
		
		$make = new \tests\factories\ObjectFactory();
		$colloque = $make->colloque();

		$prices = $colloque->prices->pluck('id')->all();

		$user = factory(App\Droit\User\Entities\User::class)->create();
		$this->actingAs($user);

		$this->visit('/pubdroit/colloque/'.$colloque->id);
		$this->assertViewHas('colloque');

		$this->assertCount(1, $this->visit('/pubdroit/colloque/'.$colloque->id)
			->crawler->filter('input[name="price_id"][value="'.$prices[0].'"]'));

		$this->assertCount(1, $this->visit('/pubdroit/colloque/'.$colloque->id)
			->crawler->filter('input[name="user_id"][value="'.$user->id.'"]'));

		$this->assertCount(1, $this->visit('/pubdroit/colloque/'.$colloque->id)
			->crawler->filter('input[name="colloque_id"][value="'.$colloque->id.'"]'));

        $this->select($prices[0], 'price_id');

        $this->press('Envoyer');

		$this->seeInDatabase('colloque_inscriptions', [
			'colloque_id' => $colloque->id,
			'user_id'     => $user->id,
			'price_id'    => $prices[0]
		]);

        $this->seePageIs('pubdroit');
	}
}
