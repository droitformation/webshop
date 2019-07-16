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

    public function testCreateAboWithReferences()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();
        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $response = $this->call('POST', 'admin/abonnement', [
            'abo_id'   => $abo->id,
            'user_id'  => $user->id,
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
            'numero' => 213,
            'exemplaires' => 1,
            'renouvellement' => 'auto',
            'status' => 'abonne',
            'product_id'  => $abo->products->first()->id
        ]);

        $this->assertDatabaseHas('abo_users', [
            'abo_id'  => $abo->id,
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
        ]);
    }

    public function testUpdateAboWithReferences()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $abonnement = $make->makeUserAbonnement($abo);
        $make->abonnementFacture($abonnement);

        $response = $this->call('PUT', 'admin/abonnement/'.$abonnement->id, [
            'id'   => $abonnement->id,
            'abo_id'   => $abo->id,
            'user_id'  => $abonnement->user_id,
            'reference_no'   => 'Ref_24567designpond',
            'transaction_no' => '2109456_19824',
            'numero' => 234567,
            'exemplaires' => 2,
            'renouvellement' => 'auto',
            'status' => 'abonne'
        ]);

        $this->assertDatabaseHas('abo_users', [
            'abo_id'  => $abo->id,
            'user_id' => $abonnement->user_id,
            'numero' => 234567,
            'exemplaires' => 2,
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Ref_24567designpond',
            'transaction_no' => '2109456_19824',
        ]);
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
