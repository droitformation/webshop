<?php

namespace Tests\Feature\backend\user;

use Tests\TestCase;
use Tests\ResetTbl;
use Tests\TestFlashMessages;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdresseUpdateTest extends TestCase
{
    use RefreshDatabase,ResetTbl,TestFlashMessages;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testAdresseToTypeNotContact()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1, 'user_id' => $user->id]);

        $response = $this->put('admin/adresse/'.$adresse->id, [
            'id'           => $adresse->id,
            'type'         => 2,
            'first_name'   => $adresse->first_name,
            'last_name'    => $adresse->last_name,
            'email'        => $adresse->email,
            'adresse'      => $adresse->adresse,
            'npa'          => $adresse->npa,
            'ville'        => $adresse->ville,
        ]);

        $response->assertStatus(302);

        $this->assertCount(1, $this->flashMessagesForLevel('warning'));
        $this->assertCount(1, $this->flashMessagesForMessage('Attention! Un compte doit avoir une adresse de contact!'));
    }

    public function testAdresseToTypeContact()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $adresse1 = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1, 'user_id' => $user->id]);
        $adresse2 = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 2, 'user_id' => $user->id]);

        $response = $this->put('admin/adresse/'.$adresse2->id, [
            'id'           => $adresse2->id,
            'type'         => 1,
            'first_name'   => $adresse2->first_name,
            'last_name'    => $adresse2->last_name,
            'email'        => $adresse2->email,
            'adresse'      => $adresse2->adresse,
            'npa'          => $adresse2->npa,
            'ville'        => $adresse2->ville,
        ]);

        $this->assertCount(1, $this->flashMessagesForLevel('warning'));
        $this->assertCount(1, $this->flashMessagesForMessage('Adresse mise à jour. Il existe déjà un adresse de contact, seul la première crée sera pris en compte pour les transactions!'));
    }

    public function testAdresseNormal()
    {
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1]);

        $response = $this->put('admin/adresse/'.$adresse->id, [
            'id'           => $adresse->id,
            'type'         => 2,
            'first_name'   => $adresse->first_name,
            'last_name'    => $adresse->last_name,
            'email'        => $adresse->email,
            'adresse'      => $adresse->adresse,
            'npa'          => $adresse->npa,
            'ville'        => $adresse->ville,
        ]);

        $this->assertCount(1, $this->flashMessagesForLevel('success'));
        $this->assertCount(1, $this->flashMessagesForMessage('Adresse mise à jour'));
    }
}
