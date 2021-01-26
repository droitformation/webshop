<?php

namespace Tests\Feature\backend\colloque;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ColloquePricesLinkTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

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

    public function testCreateNewPrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque1 = $make->colloque();
        $colloque2 = $make->colloque();

        $prices = [
            'colloques'   => [$colloque1->id,$colloque2->id],
            'price'       => '10.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'test',
        ];

        $results = [
            'colloques'      => [['id' => $colloque1->id, 'text' => $colloque1->title], ['id' => $colloque2->id, 'text' => $colloque2->title]], // all coloques
            'linked'         => [['id' => $colloque1->id, 'text' => $colloque1->title], ['id' => $colloque2->id, 'text' => $colloque2->title]], // only other colloque
            'description'    => 'test',
            'price'          => '10.00',
            'type'           => 'public',
            'remarque'       => 'testing',
            'rang'           => 1,
            'state'          => false,
        ];

        $data = ['price' => $prices, 'colloque_id' => $colloque1->id];

        $response = $this->json('POST', '/vue/price_link', $data);
        $response->assertJsonFragment($results);

        $this->assertDatabaseHas('price_link', [
            'price'       => 1000,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'test'
        ]);

        $this->assertDatabaseHas('price_link_colloques', ['colloque_id' => $colloque1->id,]);
        $this->assertDatabaseHas('price_link_colloques', ['colloque_id' => $colloque2->id,]);
    }

    public function testUpdatePrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque1 = $make->colloque();
        $colloque2 = $make->colloque();
        $colloque3 = $make->colloque();

        $price = factory(\App\Droit\PriceLink\Entities\PriceLink::class)->create([
            'price'       => '12.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'test',
            'rang'        => 1,
        ]);

        $price->colloques()->attach([$colloque1->id,$colloque2->id]);

        // Fake update
        $prices = [
            'id'          => $price->id,
            'price'       => '15.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'other',
            'rang'        => 1,
        ];

        $data = ['price' => $prices, 'colloque_id' => $colloque1->id, 'relations' => [$colloque1->id,$colloque2->id,$colloque3->id]];

        $results = [
            'colloques'      => [['id' => $colloque1->id, 'text' => $colloque1->title],['id' => $colloque2->id, 'text' => $colloque2->title],['id' => $colloque3->id, 'text' => $colloque3->title]], // all coloques
            'linked'         => [['id' => $colloque1->id, 'text' => $colloque1->title],['id' => $colloque2->id, 'text' => $colloque2->title],['id' => $colloque3->id, 'text' => $colloque3->title]], // only other colloque
            'price'          => '15.00',
            'type'           => 'public',
            'remarque'       => 'testing',
            'description'    => 'other',
            'rang'           => 1,
            'state'          => false,
        ];

        $this->json('PUT', '/vue/price_link/'.$price->id, $data)->assertJsonFragment($prices);

        $this->assertDatabaseHas('price_link', [
            'price'       => 1500,
            'type'        => 'public',
            'remarque'    => 'testing',
            'description' => 'other',
        ]);

        $this->assertDatabaseHas('price_link_colloques', ['colloque_id' => $colloque1->id]);
        $this->assertDatabaseHas('price_link_colloques', ['colloque_id' => $colloque2->id]);
        $this->assertDatabaseHas('price_link_colloques', ['colloque_id' => $colloque3->id]);
    }

    public function testDeletePrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque1 = $make->colloque();
        $colloque2 = $make->colloque();
        $colloque3 = $make->colloque();

        $price = factory(\App\Droit\PriceLink\Entities\PriceLink::class)->create([
            'price'       => '12.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'test',
            'rang'        => 1
        ]);

        $price->colloques()->attach([$colloque1->id,$colloque2->id]);

        $this->json('DELETE', '/vue/price_link/'.$price->id.'/'.$colloque1->id)->assertJsonFragment([]);

        $this->assertDatabaseMissing('price_link', ['id' => $price->id, 'description' => 'test','deleted_at'  => null]);
        $this->assertDatabaseMissing('price_link_colloques', ['colloque_id' => $colloque1->id]);
        $this->assertDatabaseMissing('price_link_colloques', ['colloque_id' => $colloque2->id]);
    }

/*    public function testColloquePriceLink()
    {
        $make      = new \tests\factories\ObjectFactory();
        $colloque1 = $make->colloque();
        $colloque2 = $make->colloque();

        $price = factory(\App\Droit\PriceLink\Entities\PriceLink::class)->create([
            'price'       => '320.00', // with quotes else json is not formatted correctly
            'description' => 'Price linked',
        ]);

        $price->colloques()->attach([$colloque1->id,$colloque2->id]);

        // Frontend?

    }*/
}
