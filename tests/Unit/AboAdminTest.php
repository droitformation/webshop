<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboAdminTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testGetAboProductWithAttributes()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        // Make new product and add the attributes
        $make = new \tests\factories\ObjectFactory();

        $product = $make->product();
        $product = $make->addAttributesAbo($product);

        $data = [
            'title'       => 'TestAbo',
            'price'       => 50,
            'plan'        => 'year',
            'shipping'    => '12',
            'products_id' => [$product->id]
        ];

        $response = $this->call('POST', '/admin/abo', $data);

        $this->assertDatabaseHas('abos', [
            'title'    => 'TestAbo',
            'price'    => '5000',
            'shipping' => '1200'
        ]);
    }

    public function testAboAssignToUser()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();
        $user = $make->user();

        $this->assertEquals(0, $user->abos->count());

        $data = [
            'abo_id'         => $abo->id,
            'numero'         => 1,
            'exemplaires'    => 1,
            'user_id'        => $user->id,
            'status'         => 'abonne',
            'renouvellement' => 'auto',
            'product_id'     => $abo->products->first()->id
        ];

        $response = $this->call('POST', '/admin/abonnement', $data);

        $this->assertDatabaseHas('abo_users', [
            'abo_id'  => $abo->id,
            'user_id' => $user->id,
        ]);

        // Reload everything
        $user->fresh();
        $user->load('abos.factures.rappels');

        // Has 1 abo and it's the one we wanted
        $this->assertEquals(1, $user->abos->count());
        $this->assertTrue($user->abos->contains('abo_id',$abo->id));

        // Has 1 factures created with the new account
        $this->assertEquals(1, $user->abos->pluck('factures')->flatten()->count());

        //Has no rappels
        $this->assertEquals(0, $user->abos->pluck('factures')->flatten()->first()->rappels->count());
    }

    public function testAboUserUpdate()
    {
        $make = new \tests\factories\ObjectFactory();

        $abo        = $make->makeAbo();
        $abonnement = $make->makeAbonnement($abo);
        $user       = $make->makeUser();

        $defaults = [
            'id'             => $abonnement->id,
            'abo_id'         => $abo->id,
            'numero'         => $abonnement->numero,
            'exemplaires'    => $abonnement->exemplaires,
            'status'         => $abonnement->status,
            'renouvellement' => $abonnement->renouvellement,
        ];

        // Only user_id
        $data = array_merge($defaults,['adresse_id' => '', 'user_id' => $user->id]);

        $response = $this->call('PUT', '/admin/abonnement/'.$abonnement->id, $data);

        $this->assertDatabaseHas('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => null,
            'user_id'    => $user->id,
        ]);

        // Both but we need only user_id
        $data = array_merge($defaults,['adresse_id' => 12, 'user_id' => $user->id]);

        $response = $this->call('PUT', '/admin/abonnement/'.$abonnement->id, $data);

        $this->assertDatabaseHas('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => null,
            'user_id'    => $user->id,
        ]);

        // Only adresse_id
        $data = array_merge($defaults,['adresse_id' => 12, 'user_id' => null]);

        $response = $this->call('PUT', '/admin/abonnement/'.$abonnement->id, $data);

        $this->assertDatabaseHas('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => 12,
            'user_id'    => null
        ]);
    }
}
