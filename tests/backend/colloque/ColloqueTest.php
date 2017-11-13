<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ColloqueTest extends BrowserKitTest {

    use DatabaseTransactions;

    protected $colloque;

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

	public function testIntersectAnnexes()
	{
        $annexes = ['bon','facture','bv'];
        $result  = (count(array_intersect($annexes, ['bon','facture'])) == count(['bon','facture']) ? true : false);

        $this->assertTrue($result);
	}

    public function testListCurrentColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $make->colloque(); // colloque active
        $make->colloque(); // colloque active
        $make->colloque(\Carbon\Carbon::now()->subMonths(2), \Carbon\Carbon::now()->subMonth()); // colloque finished
        
        $this->visit('admin/colloque');
        $this->assertViewHas('colloques');

        $content   = $this->response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(2, $colloques->count());
    }

    public function testListArchiveColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $make->colloque(\Carbon\Carbon::createFromDate(2015, 5, 21), \Carbon\Carbon::createFromDate(2015, 5, 31)); // colloque finished

        $this->visit('admin/colloque/archive/2015');
        $this->assertViewHas('colloques');

        $content   = $this->response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(1, $colloques->count());
    }

    public function testCreateNewColloque()
    {
        $start    = \Carbon\Carbon::now()->addDays(5)->toDateTimeString();
        $register = \Carbon\Carbon::now()->toDateTimeString();
        $location = factory(App\Droit\Location\Entities\Location::class)->create();

        $this->visit('admin/colloque/create');

        $this->assertViewHas('locations');
        $this->assertViewHas('organisateurs');

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

        $this->notSeeInDatabase('colloques', [
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
        $location = factory(App\Droit\Location\Entities\Location::class)->create();

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->create([
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

        $this->visit('admin/colloque/'.$colloque->id);

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

        $this->assertRedirectedTo('admin/colloque/'.$colloque->id);
        $this->followRedirects();
        $this->see('Le champ compte est obligatoire quand la génération d\'une facture est demandé.');
    }
    
    public function testDeleteColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('admin/colloque/'.$colloque->id);

        $response = $this->call('DELETE','admin/colloque/'.$colloque->id);

        $this->notSeeInDatabase('colloques', [
            'id' => $colloque->id,
            'deleted_at' => null
        ]);
    }

    /**
     * @expectedException \App\Exceptions\ColloqueMissingInfoException
     */
    public function testColloqueValidationFails()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $colloque->prices()->delete();

        $validator = new \App\Droit\Colloque\Worker\ColloqueValidation($colloque);
        $validator->activate();

        $this->expectExceptionMessage('Il manque les infos d\'attestation, Il manque au moins un prix');
    }

    public function testColloqueValidation()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $attestation = factory(App\Droit\Colloque\Entities\Colloque_attestation::class)->create([
            'colloque_id'  => $colloque->id,
        ]);

        $validator = new \App\Droit\Colloque\Worker\ColloqueValidation($colloque);
        $result = $validator->activate();

        $this->assertTrue($result);
    }
}
