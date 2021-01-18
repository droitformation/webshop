<?php

namespace Tests\Unit\inscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class RegisterInscriptionTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $generator;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->generator = \Mockery::mock('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->app->instance('App\Droit\Generate\Pdf\PdfGeneratorInterface', $this->generator);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testRegisterDataMultiple()
    {
        $make       = new \tests\factories\ObjectFactory();
        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();

        $price1     = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price2     = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'description' => 'Price free']);
        $price_link = factory( \App\Droit\PriceLink\Entities\PriceLink::class)->create();
        $price_link->colloques()->attach([$colloque1->id,$colloque2->id]);

        $data = [
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'user_id'        => 710,
            'colloque_id'    => $colloque1->id,
            'type'           => 'multiple',
            'addons' =>[
                $colloque1->id => [
                    'options' => [
                        0 => [259],
                        1 => [258]
                    ],
                    'groupes' => [
                        0 => [268 => 150],
                        1 => [268 => 151]
                    ]
                ],
                $colloque2->id => [
                    'options'  => [
                        0 => [261],
                        1 => [260]
                    ],
                ]
            ],
            'price_id' => [
                "price_link_id:".$price_link->id,
                "price_id:".$price1->id,
            ],
            'colloques' => [
                0 => [$colloque1->id, $colloque2->id],
                1 => [$colloque1->id]
            ]
        ];

        $expected = collect([
            $colloque1->id => [
                'type'         => 'multiple',
                'colloque_id'  => $colloque1->id,
                'user_id'      => 710,
                'participants' => [
                    [
                        'participant'  => 'Marc,Leschaud',
                        'email' => 'Marc.Leschaud@romandie.ch',
                        'options' => [259],
                        'groupes' => [268 => 150],
                        "price_link_id"   => $price_link->id,
                        'price_linked_id' => $price_link->id,
                    ],
                    [
                        'participant'  => 'Cindy,Leschaud',
                        'email' => 'cindy.leschaud@gmail.com',
                        'options' => [258],
                        'groupes' => [268 => 151],
                        "price_id" => $price1->id,
                    ]
                ],
            ],
            $colloque2->id => [
                'type'         => 'multiple',
                'colloque_id'  => $colloque2->id,
                'user_id'      => 710,
                'participants' => [
                    [
                        'participant'  => 'Marc,Leschaud',
                        'email' => 'Marc.Leschaud@romandie.ch',
                        'options' => [261],
                        "price_id" => $price2->id,
                        'price_linked_id' => $price_link->id,
                    ]
                ],
            ]
        ]);

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->prepare();

        $this->assertEquals($expected,$actual);
    }

    public function testGetPriceElements()
    {
        $register = new \App\Droit\Inscription\Entities\Register();
        $actual   = $register->convertPrices('price_link_id:1');
        $expected = [
            'price_link_id'   => 1,
            'price_linked_id' => 1
        ];

        $this->assertEquals($expected,$actual);
    }

    public function testSimpleRegisterWithRabais()
    {
        $make       = new \tests\factories\ObjectFactory();
        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();
        $person     = $make->makeUser();

        $price1 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price2 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'description' => 'Price free']);

        $rabais   = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 100, 'title' => 'GLOBAL', 'type' => 'colloque', 'description' => 'test']);
        $options1 = $colloque1->options->pluck('id')->all();
        $options2 = $colloque2->options->pluck('id')->all();

        $person->rabais()->attach($rabais->id);

        $price_link = factory(\App\Droit\PriceLink\Entities\PriceLink::class)->create();
        $price_link->colloques()->attach([$colloque1->id,$colloque2->id]);

        $data = [
            'rabais_id'      => $rabais->id,
            'user_id'        => $person->id,
            'colloque_id'    => $colloque1->id,
            'type'           => 'simple',
            'colloques' => [
                $colloque1->id => ['options' => $options1],
                $colloque2->id => ['options' => $options2]
            ],
            'price_id' => 'price_link_id:'.$price_link->id,
        ];

        $response = $this->call('POST', 'admin/inscription', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque1->id,
            'user_id'     => $person->id,
            'price_link_id'  => $price_link->id,
        ]);

    }

    public function testGetGroupLinked()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque1 = $make->colloque();
        $colloque2 = $make->colloque();

        $user   = factory(\App\Droit\User\Entities\User::class)->create();

        $group1 = factory(\App\Droit\Inscription\Entities\Groupe::class)->create(['user_id' => $user->id, 'colloque_id' => $colloque1->id]);
        $group2 = factory(\App\Droit\Inscription\Entities\Groupe::class)->create(['user_id' => $user->id, 'colloque_id' => $colloque2->id]);

        $repo  = \App::make('App\Droit\Inscription\Repo\GroupeInterface');

        $found = $repo->linkedGroup($group1->id,$colloque2->id);

        $this->assertEquals($group2->id,$found);
    }
}
