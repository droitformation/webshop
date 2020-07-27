<?php

namespace Tests\Feature\backend\colloque;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ColloqueOccurrenceTest extends TestCase
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

    public function testCreateNewOccurrence()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $occurrences =[
            'colloque_id'    => $colloque->id,
            'title'          => 'testing',
            'capacite_salle' => 8,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ];

        $data = ['occurrence' => $occurrences];

        $presponse =  $this->json('POST', '/vue/occurrence', $data)->assertJsonFragment($occurrences);
   
        $this->assertDatabaseHas('colloque_occurrences', [
            'colloque_id'    => $colloque->id,
            'title'          => 'testing',
            'capacite_salle' => 8,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ]);
    }

    public function testUpdateOccurrence()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $occurrence = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'    => $colloque->id,
            'title'          => 'testing',
            'capacite_salle' => 8,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ]);

        // Fake update
        $occurrences = [
            'id'             => $occurrence->id,
            'colloque_id'    => $colloque->id,
            'title'          => 'other',
            'capacite_salle' => 12,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ];

        $data = ['occurrence' => $occurrences];

        $this->json('PUT', '/vue/occurrence/'.$occurrence->id, $data)->assertJsonFragment($occurrences);

        $this->assertDatabaseHas('colloque_occurrences', [
            'id'             => $occurrence->id,
            'colloque_id'    => $colloque->id,
            'title'          => 'other',
            'capacite_salle' => 12,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ]);
    }

    public function testDeleteOccurrence()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $occurrence = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'    => $colloque->id,
            'title'          => 'testing',
            'capacite_salle' => 8,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ]);

        $this->json('DELETE', '/vue/occurrence/'.$occurrence->id)->assertJsonFragment([]);

        $this->assertDatabaseMissing('colloque_occurrences', [
            'id' => $occurrence->id,
            'deleted_at'  => null
        ]);

    }
}
