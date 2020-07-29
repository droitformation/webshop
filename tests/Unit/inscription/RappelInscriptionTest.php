<?php

namespace Tests\Unit\inscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class RappelInscriptionTest extends TestCase
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

    public function testListRappelsNormalColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        // Third is payed not in rappels
        $third->payed_at = \Carbon\Carbon::today()->subDays(5);
        $third->save();

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(2, $inscriptions->count());

        // Pay all inscriptions
        $first->payed_at = \Carbon\Carbon::today()->subDays(5);
        $first->save();

        $second->payed_at = \Carbon\Carbon::today()->subDays(5);
        $second->save();

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(0, $inscriptions->count());

    }

    public function testListRappelsOccurrencesColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        $occurence1 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->addDays(5)
        ]);

        $occurence2 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->subDays(5)
        ]);

        $colloque->occurrences()->saveMany([$occurence1,$occurence2]);

        $first->occurrences()->attach($occurence1->id);
        $first->fresh();
        $first->load('occurrences');

        $this->assertEquals(1,$first->occurrences->count());

        $second->occurrences()->attach($occurence2->id);
        $third->occurrences()->attach([$occurence1->id,$occurence2->id]);

        // First occurrence 1 not passed, not in rappels
        // Second occurrence 2 passed, in rappels
        // Third occurrence 1 and 2 not passed/passed, in rappels

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(2, $inscriptions->count());

        $inrappels = [$second->id,$third->id];

        $inscriptions->map(function ($item, $key) use ($inrappels) {
            $this->assertTrue(in_array($item->id, $inrappels));
        });
    }

    public function testListRappelsPayedOccurrencesColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        $occurence1 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->addDays(5)
        ]);

        $occurence2 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->subDays(5)
        ]);

        $colloque->occurrences()->saveMany([$occurence1,$occurence2]);

        $first->occurrences()->attach($occurence1->id);
        $second->occurrences()->attach($occurence2->id);
        $third->occurrences()->attach([$occurence1->id,$occurence2->id]);

        // Pay all inscriptions
        $first->payed_at = \Carbon\Carbon::today()->subDays(5);
        $first->save();

        $second->payed_at = \Carbon\Carbon::today()->subDays(5);
        $second->save();

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(1, $inscriptions->count());
    }
}
