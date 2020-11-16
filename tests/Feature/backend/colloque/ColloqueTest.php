<?php

namespace Tests\Feature\backend\colloque;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ColloqueTest extends TestCase
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

       // $this->withoutExceptionHandling();
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testIntersectAnnexes()
    {
        $annexes = ['bon','facture','bv'];
        $result  = count(array_intersect($annexes, ['bon','facture'])) == count(['bon','facture']);

        $this->assertTrue($result);
    }

    public function testListCurrentColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $make->colloque(); // colloque active
        $make->colloque(); // colloque active
        $make->colloque(\Carbon\Carbon::now()->subMonths(2), \Carbon\Carbon::now()->subMonth()); // colloque finished

        $response = $this->get('admin/colloque');

        $content   = $response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(2, $colloques->count());
    }

    public function testListArchiveColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $make->colloque(\Carbon\Carbon::createFromDate(2015, 5, 21), \Carbon\Carbon::createFromDate(2015, 5, 31)); // colloque finished

        $response = $this->get('admin/colloque/archive/2015');

        $content   = $response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(1, $colloques->count());
    }

    public function testCreateNewColloque()
    {
        $start    = \Carbon\Carbon::now()->addDays(5)->toDateTimeString();
        $register = \Carbon\Carbon::now()->toDateTimeString();
        $location = factory(\App\Droit\Location\Entities\Location::class)->create();

        $response = $this->get('admin/colloque/create');

        $response->assertViewHas('locations');
        $response->assertViewHas('organisateurs');

        $response = $this->call('POST','/admin/colloque', [
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'soustitre'       => 'Un sous titre',
            'organisateur'    => 'Organisateur',
            'organisateur'    => 'Organisateur',
            'location_id'     => $location->id,
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
        ]);

        $this->assertDatabaseMissing('colloques', [
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'soustitre'       => 'Un sous titre',
            'organisateur'    => 'Organisateur',
            'organisateur'    => 'Organisateur',
            'location_id'     => $location->id,
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
        ]);

    }

    public function testColloqueEditFailsValidation()
    {
        $location = factory(\App\Droit\Location\Entities\Location::class)->create();

        $colloque = factory(\App\Droit\Colloque\Entities\Colloque::class)->create([
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'soustitre'       => 'Un sous titre',
            'organisateur'    => 'Organisateur',
            'location_id'     => $location->id, // missing => exception
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
            'compte_id'       => null, // missing => exception
            'visible'         => null,
            'bon'             => 1,
            'facture'         => 1,
            'adresse_id'      => 1
        ]);

        $response = $this->call('PUT','/admin/colloque/'.$colloque->id, [
            'id'              => $colloque->id,
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'organisateur'    => 'Organisateur',
            'location_id'     => $location->id,
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
            'compte_id'       => null, // missing => exception
            'visible'         => null,
            'bon'             => 1,
            'facture'         => 1,
            'adresse_id'      => 1
        ]);

        $errors = $this->app['session.store']->get('errors');

        $this->assertEquals($errors->first(),'Le champ compte est obligatoire quand la génération d\'une facture est demandé.');
    }

    public function testDeleteColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $response = $this->call('DELETE','admin/colloque/'.$colloque->id);

        $this->assertDatabaseMissing('colloques', [
            'id' => $colloque->id,
            'deleted_at' => null
        ]);
    }

    public function testColloqueValidationFails()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $colloque->prices()->delete();

        $validator = new \App\Droit\Colloque\Worker\ColloqueValidation($colloque);
        $validator->activate();

        $errors = $this->app['session.store']->all();

        $this->assertEquals('Il manque au moins un prix',$errors['flash_notification'][0]->message);

    }

    public function testColloqueValidation()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $attestation = factory(\App\Droit\Colloque\Entities\Colloque_attestation::class)->create(['colloque_id'  => $colloque->id,]);

        $validator = new \App\Droit\Colloque\Worker\ColloqueValidation($colloque);
        $result = $validator->activate();

        $this->assertTrue($result);
    }
}
