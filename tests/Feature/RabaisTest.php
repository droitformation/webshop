<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\Types\Integer;
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
            'price_id'       => 'price_id:'.$colloque->prices->first()->id,
            'colloque_id'    => $colloque->id,
            'colloques' => [
                $colloque->id => [
                    'options' => [$colloque->options->first()->id],
                ]
            ],
            'rabais_id'      => $rabais->id,
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
            'user_id'        => $person->id,
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
        $make       = new \tests\factories\ObjectFactory();
        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();

        $price1 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10]);

        $price_rabais = 2 * ($price1->price_cents - $rabais->value);

        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10]);

        $data = [
            'colloque_id'    => $colloque1->id,
            'user_id'        => 710,
            'rabais_id'      => $rabais->id,
            'reference_no'   => '21345',
            'transaction_no' => '6543',
            'participants'   => [
                [
                    'participant' => 'Marc,Leschaud',
                    'email'   => 'Marc.Leschaud@romandie.ch',
                    'options' => [0   => 259],
                    'groupes' => [268 => 150],
                    "price_id" => $price1->id
                ],
                [
                    'participant' => 'Cindy,Leschaud',
                    'email'    => 'cindy.leschaud@gmail.com',
                    'options'  => [0 => 258],
                    'groupes'  => [268 => 151],
                    "price_id" => $price1->id
                ]
            ]
        ];

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->register($data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque1->id,
            'user_id'     => null,
            'price_id'    => $price1->id,
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
