<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FrontendProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->artisan("db:seed");

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
     * Inscription from frontend
     * @return void
     */
    public function testCanRegister()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque();
        $user     = $make->makeUser();

        $this->actingAs($user);
        $date = \Carbon\Carbon::now()->subDays(32)->toDateString();

        $inscription1 = $make->makeInscriptionForUser($user, $date);
        $inscription2 = $make->makeInscriptionForUser($user, $date);

        $response = $this->get('/pubdroit/colloque/'.$colloque->id);

        $response->assertStatus(200);
        $response->assertViewHas('colloque');
        $response->assertSee($colloque->titre);
    }

    public function testCantRegisterHasRappel()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque();
        $user     = $make->makeUser();

        $this->actingAs($user);
        $date = \Carbon\Carbon::now()->subDays(32)->toDateString();

        $inscription1 = $make->makeInscriptionForUser($user, $date);
        $inscription2 = $make->makeInscriptionForUser($user, $date);

        $inscription1->rappels()->create([
            'user_id'     => $user->id,
            'colloque_id' => $inscription1->colloque_id,
        ]);

        $inscription2->rappels()->create([
            'user_id'      => $user->id,
            'colloque_id'  => $inscription2->colloque_id,
        ]);

        $response = $this->get('/pubdroit/colloque/'.$colloque->id);

        $response->assertStatus(200);
        $response->assertViewHas('colloque');

        $response->assertSee('Vous avez des paiements en attente, veuillez contacter le secrétariat: droit.formation@unine.ch');

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
        $this->assertDatabaseHas('adresses', [
            'id'        => $original->id,
            'livraison' => null
        ]);

        $this->assertDatabaseHas('adresses', [
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
        $this->assertDatabaseHas('adresses', [
            'id'        => $original->id,
            'livraison' => 1
        ]);

        $this->assertDatabaseHas('adresses', [
            'id'        => $edit->id,
            'livraison' => null
        ]);
    }

    public function testProfilInscriptionsUser()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

        $response = $this->get('/pubdroit/profil/colloques');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }


    public function testAboUser()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeUserAbonnement(null, $user);

        $response = $this->get('/pubdroit/profil/abos');

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $user = $content['user'];

        $this->assertEquals(1, $user->abos->count());
        $response->assertStatus(200);

    }

    public function testAccountMiddleware()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

        $response = $this->call('POST', 'profil/account', ['id' => '123']);

        $response->assertStatus(404);
    }
}


