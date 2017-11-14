<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureSeminaireTest extends TestCase
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

    public function testDeleteSeminaire()
    {
        $seminaire = factory(\App\Droit\Seminaire\Entities\Seminaire::class)->create();

        $response = $this->call('DELETE','admin/seminaire/'.$seminaire->id);

        $this->assertDatabaseMissing('seminaires', ['id' => $seminaire->id, 'deleted_at' => null]);
    }

    public function testAddNewSubject()
    {
        $seminaire = factory(\App\Droit\Seminaire\Entities\Seminaire::class)->create();
        $subject   = factory(\App\Droit\Seminaire\Entities\Subject::class)->create();

        $seminaire->subjects()->attach([$subject->id]);

        $response = $this->call('POST','admin/subject', ['title' => 'Un nouveau sujet intéressant', 'seminaire_id' => $seminaire->id, 'rang' => 1234]);

        $this->assertDatabaseHas('subjects', [
            'title' => 'Un nouveau sujet intéressant',
            'rang' => '1234'
        ]);

        $seminaire->fresh();

        $this->assertEquals(2, $seminaire->subjects->count());
    }

    public function testEditSubject()
    {
        $seminaire = factory(\App\Droit\Seminaire\Entities\Seminaire::class)->create();
        $subject   = factory(\App\Droit\Seminaire\Entities\Subject::class)->create(['title' => 'Un sujet', 'rang' => 1]);

        $seminaire->subjects()->attach([$subject->id]);

        $response = $this->call('PUT','admin/subject/'.$subject->id,
            ['id' => $subject->id, 'title' => 'Un nouveau sujet intéressant', 'seminaire_id' => $seminaire->id, 'rang' => 1234]
        );

        $this->assertDatabaseHas('subjects', [
            'id'    => $subject->id,
            'title' => 'Un nouveau sujet intéressant',
            'rang'  => '1234'
        ]);
    }

    public function testDeleteSubject()
    {
        $seminaire = factory(\App\Droit\Seminaire\Entities\Seminaire::class)->create();
        $subject   = factory(\App\Droit\Seminaire\Entities\Subject::class)->create();

        $seminaire->subjects()->attach([$subject->id]);

        $response = $this->call('DELETE','admin/subject/'.$subject->id);

        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
            'deleted_at' => null,
        ]);

        $seminaire->fresh();

        $this->assertEquals(0, $seminaire->subjects->count());
    }
}
