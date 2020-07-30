<?php

namespace Tests\Unit\inscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class WorkerInscriptionTest extends TestCase
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
            'colloques' =>[
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
            ]
        ];

        $expected = collect([
            $colloque1->id => [
                'user_id'      => 710,
                'colloque_id'  => $colloque1->id,
                'type'         => 'multiple',
                'participants' => [
                    [
                        'name'  => 'Marc,Leschaud',
                        'email' => 'Marc.Leschaud@romandie.ch',
                        "price_link_id"   => $price_link->id,
                        'price_linked_id' => $price_link->id,
                        'options' => [259],
                        'groupes' => [268 => 150],
                    ],
                    [
                        'name'  => 'Cindy,Leschaud',
                        'email' => 'cindy.leschaud@gmail.com',
                        "price_id" => $price1->id,
                        'options' => [258],
                        'groupes' => [268 => 151],
                    ]
                ],
            ],
            $colloque2->id => [
                'user_id'      => 710,
                'colloque_id'  => $colloque2->id,
                'type'         => 'multiple',
                'participants' => [
                    [
                        'name'  => 'Marc,Leschaud',
                        'email' => 'Marc.Leschaud@romandie.ch',
                        "price_id" => $price2->id,
                        'price_linked_id' => $price_link->id,
                        'options' => [261],
                    ]
                ],
            ]
        ]);

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->prepare($data);

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
}
