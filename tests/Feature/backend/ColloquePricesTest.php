<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ColloquePricesTest extends TestCase
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

    public function testCreateNewPrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $prices =[
            'colloque_id' => $colloque->id,
            'price'       => '10.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
        ];

        $data = ['price' => $prices];

        $this->json('POST', '/vue/price', $data)->assertJsonFragment($prices);

        $this->assertDatabaseHas('colloque_prices', [
            'colloque_id' => $colloque->id,
            'price'       => 1000,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy'
        ]);
    }

    public function testUpdatePrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $price = factory(\App\Droit\Price\Entities\Price::class)->create([
            'colloque_id' => $colloque->id,
            'price'       => '12.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
            'rang'        => 1,
        ]);

        // Fake update
        $prices = [
            'id'          => $price->id,
            'colloque_id' => $colloque->id,
            'price'       => '15.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
        ];

        $data = ['price' => $prices];

        $this->json('PUT', '/vue/price/'.$price->id, $data)->assertJsonFragment($prices);

        $this->assertDatabaseHas('colloque_prices', [
            'id'          => $price->id,
            'colloque_id' => $colloque->id,
            'price'       => 1500,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy'
        ]);
    }

    public function testDeletePrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $price = factory(\App\Droit\Price\Entities\Price::class)->create([
            'colloque_id' => $colloque->id,
            'price'       => '12.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
            'rang'        => 1,
        ]);

        $this->json('DELETE', '/vue/price/'.$price->id)->assertJsonFragment([]);

        $this->assertDatabaseMissing('colloque_prices', [
            'id'          => $price->id,
            'description' => 'cindy',
            'colloque_id' => $colloque->id,
        ]);
    }
}
