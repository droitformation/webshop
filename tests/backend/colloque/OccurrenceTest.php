<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class OccurrenceTest extends BrowserKitTest {

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

    public function testCreateNewOccurrence()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $occurrences =[
            'colloque_id'    => $colloque->id,
            'title'          => 'testing',
            'capacite_salle' => 8,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ];

        $data = ['occurrence' => $occurrences];

        $this->json('POST', '/vue/occurrence', $data)->seeJson($occurrences);

        $this->seeInDatabase('colloque_occurrences', [
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

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $occurrence = factory(App\Droit\Occurrence\Entities\Occurrence::class)->create([
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

        $this->json('PUT', '/vue/occurrence/'.$occurrence->id, $data)->seeJson($occurrences);

        $this->seeInDatabase('colloque_occurrences', [
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

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $occurrence = factory(App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'    => $colloque->id,
            'title'          => 'testing',
            'capacite_salle' => 8,
            'lieux_id'       => 1,
            'starting_at'    => '2016-12-31'
        ]);

        $this->json('DELETE', '/vue/occurrence/'.$occurrence->id)->seeJson([]);
    }
}
