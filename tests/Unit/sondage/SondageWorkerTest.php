<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class SondageWorkerTest extends TestCase
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

    public function testCreateListForSondageColloque()
    {
        $worker = new \App\Droit\Sondage\Worker\SondageWorker();

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(4);

        $list  = $worker->createList($colloque->id);

        $this->assertDatabaseHas('newsletter_lists', [
            'colloque_id' => $colloque->id,
            'title'       => 'SONDAGE | '.$colloque->titre,
        ]);

        $this->assertEquals(4, $list->emails->count());

        $person  = $make->makeUser();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id'     => $person->id,
            'group_id'    => null,
            'colloque_id' => $colloque->id,
            'payed_at'    => null,
        ]);

        $list = $worker->updateList($colloque->id);
        $list->fresh();
        $list->load('emails');

        $this->assertEquals(5, $list->emails->count());

    }

    public function testRemoveEmailsFromList()
    {
        $worker = new \App\Droit\Sondage\Worker\SondageWorker();

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(4);

        $list  = $worker->createList($colloque->id);

        // 4 emails because 4 inscriptions
        $this->assertEquals(4, $list->emails->count());

        // Remove one email/inscription
        $first = $colloque->inscriptions->shift();
        $first->delete();
        $colloque->fresh();

        $list = $worker->updateList($colloque->id);
        $list->fresh();
        $list->load('emails');

        $this->assertEquals(3, $list->emails->count());
    }
}
