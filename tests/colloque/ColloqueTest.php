<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ColloqueTest extends TestCase {

    use DatabaseTransactions, WithoutMiddleware;

    protected $colloque;

    public function setUp()
    {
        parent::setUp();

        $this->colloque = Mockery::mock('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->app->instance('App\Droit\Colloque\Repo\ColloqueInterface', $this->colloque);

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
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

    public function testListColloques()
    {
        $colloques = factory(App\Droit\Colloque\Entities\Colloque::class,3)->make();

        $this->colloque->shouldReceive('getAll')->once()->andReturn($colloques);
        $this->colloque->shouldReceive('getYears')->once()->andReturn($colloques);

        $this->visit('admin/colloque');
        $this->assertViewHas('colloques');
    }

    public function testCreateNewColloque()
    {
        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make([
            'id'              => 1,
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'organisateur'    => 'Organisateur',
            'location_id'     => 3,
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
            'compte_id'       => 1
        ]);

        $this->colloque->shouldReceive('create')->once()->andReturn($colloque);

        $this->visit('/admin/colloque/create')->see('Ajouter un colloque');

        $response = $this->call('POST', '/admin/colloque');

        $this->assertRedirectedTo('/admin/colloque/1');
    }

    public function testColloqueEditPage()
    {
        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make([
            'id'              => 1,
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'organisateur'    => 'Organisateur',
            'location_id'     => 3,
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
            'compte_id'       => 1
        ]);

        $this->colloque->shouldReceive('update')->once()->andReturn($colloque);

        $response = $this->call('PUT','/admin/colloque/1', [
            'id'              => 1,
            'titre'           => 'Titre',
            'sujet'           => 'Sujet',
            'organisateur'    => 'Organisateur',
            'location_id'     => 3,
            'start_at'        => '2020-12-31',
            'registration_at' => '2020-11-31',
            'compte_id'       => 1
        ]);

        $this->assertRedirectedTo('/admin/colloque/1');
    }
    
    public function testDeleteColloque()
    {
        $this->colloque->shouldReceive('delete')->once();

        $response = $this->call('DELETE', '/admin/colloque/1');

        $this->assertRedirectedTo('/admin/colloque');
    }

    public function testCreateColloque()
    {
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $this->actingAs($user);

        $this->colloque->shouldReceive('getAll')->once()->andReturn(collect([$colloque]));
        $this->colloque->shouldReceive('getYears')->once()->andReturn(collect([$colloque]));

        $this->visit('admin/colloque');
        $this->see($colloque->titre);
    }
}
