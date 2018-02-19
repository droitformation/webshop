<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\ResetTbl;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboBackend extends TestCase
{
    use ResetTbl;

    protected $person;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->person = $user;
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testEditFacture()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $abonnement = $make->makeUserAbonnement($abo);
        $make->abonnementFacture($abonnement);

        $facture = $abonnement->factures->first();

        $this->assertDatabaseHas('abo_factures', [
            'id'         => $facture->id,
            'payed_at'   => null,
            'created_at' => $facture->created_at,
        ]);

        $response = $this->call('PUT', 'admin/facture/'.$facture->id, ['id' => $facture->id, 'created_at' => '2016-12-31','payed_at' => '2016-12-31']);

        $this->assertDatabaseHas('abo_factures', [
            'id'         => $facture->id,
            'payed_at'   => '2016-12-31',
            'created_at' => '2016-12-31',
        ]);

        $response = $this->call('PUT', 'admin/facture/'.$facture->id, ['id' => $facture->id, 'payed_at' => '2017-12-11']);

        $this->assertDatabaseHas('abo_factures', [
            'id'         => $facture->id,
            'payed_at'   => '2017-12-11',
            'created_at' => '2016-12-31',
        ]);
    }

    public function testDeleteAbo()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $abonnement = $make->makeUserAbonnement($abo);
        $make->abonnementFacture($abonnement);

        $response = $this->delete('admin/abonnement/'.$abonnement->id);

        $this->assertDatabaseMissing('abo_users', [
            'id'         => $abonnement->id,
            'deleted_at' => null
        ]);

        $response = $this->post('admin/abonnement/restore/'.$abonnement->id);

        $this->assertDatabaseHas('abo_users', [
            'id'         => $abonnement->id,
            'deleted_at' => null
        ]);
    }
}
