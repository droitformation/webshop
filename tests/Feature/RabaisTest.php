<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\ResetTbl;

class RabaisTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->user = factory(\App\Droit\User\Entities\User::class)->create();

        $this->actingAs($this->user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testFrontendRabais()
    {
        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10]);

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $price        = $colloque->prices->first()->price_cents;
        $price_rabais = $price - $rabais->value;

        $data = [
            'price_id'       => $colloque->prices->first()->id,
            'options'        => [$colloque->options->first()->id],
            'rabais_id'      => $rabais->id,
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
            'user_id'        => $person->id,
            'colloque_id'    => $colloque->id
        ];

        $reponse = $this->post('pubdroit/registration', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'price_id'    => $colloque->prices->first()->id,
            'user_id'     => $person->id,
            'colloque_id' => $colloque->id,
            'rabais_id'   => $rabais->id,
        ]);

        $inscription = \App\Droit\Inscription\Entities\Inscription::orderBy('id','DESC')->get()->first();

        $this->assertEquals($price_rabais,$inscription->price_cents);
    }

    public function testMultipleInscriptionRabais()
    {
        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create([
            'value' => 10
        ]);

        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $price        = $colloque->prices->first()->price_cents;
        $price_rabais = 2 * ($price - $rabais->value);

        $data = [
            'colloque_id' => $colloque->id ,
            'user_id'     => $person->id,
            'participant' => ['Cindy Leschaud', 'Coralie Ahmetaj'],
            'email'       => ['cindy.leschaud@gmail.com', 'coralie.ahmetaj@hotmail.com'],
            'prices'      => [['price_id' => $prices[0]], ['price_id' => $prices[0]]],
            'rabais_id'   => [$rabais->id, $rabais->id],
            'options'     => [0 => [$options[0], [148 => 'psum odolr amet']], 1 => [$options[0], [148 => 'lorexm ipsu']]],
            'groupes'     => [[147 => 44], [147 => 45]]
        ];

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->register($data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => null,
            'price_id'    => $prices[0],
            'rabais_id'   => $rabais->id,
        ]);

        $group = \App\Droit\Inscription\Entities\Groupe::orderBy('id','DESC')->get()->first();

        $this->assertEquals($price_rabais,$group->price_cents);

    }

    public function testFrontendRegisterRabaisGlobal()
    {
        $make   = new \tests\factories\ObjectFactory();
        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10, 'type' => 'global', 'description' => 'Un rabais pour colloque']);

        $this->user->rabais()->attach($rabais->id);

        $colloque = $make->colloque();
        $price    = $colloque->prices->first()->price_cents;

        $response = $this->get('/pubdroit/colloque/'.$colloque->id);

        $response->assertSee($price.' CHF');
        $response->assertSee($rabais->description);
    }

    public function testFrontendRegisterRabaisColloque()
    {
        $make   = new \tests\factories\ObjectFactory();
        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10, 'type' => 'colloque', 'description' => 'Un rabais pour colloque']);
        $compte = factory(\App\Droit\Compte\Entities\Compte::class)->create(['centre' => 'U.12345']);

        $rabais->comptes()->attach($compte->id);
        $this->user->rabais()->attach($rabais->id);

        $colloque = $make->colloque();
        $colloque->compte_id = $compte->id;
        $colloque->save();

        $price    = $colloque->prices->first()->price_cents;

        $response = $this->get('/pubdroit/colloque/'.$colloque->id);

        $response->assertSee($price.' CHF');
        $response->assertSee($rabais->description);
    }

    public function testFrontendRegisterRabaisNotColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10, 'type' => 'colloque', 'description' => 'Un rabais pour colloque']);
        $compte = factory(\App\Droit\Compte\Entities\Compte::class)->create(['centre' => 'U.12345']);

        $rabais->comptes()->attach($compte->id);
        $this->user->rabais()->attach($rabais->id);

        $price    = $colloque->prices->first()->price_cents;

        $response = $this->get('/pubdroit/colloque/'.$colloque->id);

        $response->assertSee($price.' CHF');
        $response->assertDontSee($rabais->description);
    }
}
