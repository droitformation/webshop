<?php

namespace Tests\Unit\inscription;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\ResetTbl;

class InscriptionRegisterConverterTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $repo_colloque;

    public function setUp(): void
    {
        parent::setUp();

       // $this->repo_colloque = \Mockery::mock('App\Droit\Colloque\Repo\ColloqueInterface');
        //$this->app->instance('App\Droit\Colloque\Repo\ColloqueInterface', $this->repo_colloque);

        $this->app['config']->set('database.default','testing');
        $this->reset_all();
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testConvertPriceMultiple()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'price_id'     => [
                "price_link_id:1",
                "price_link_id:1"
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
        ];

        $expected = [
            'prices' => [
                "price_link_id:1",
                "price_link_id:1"
            ]
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->getPrice();

        $this->assertEquals($expected,$actual);
    }

    public function testConvertPriceSimple()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'price_id'       => 'price_link_id:1',
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
        ];

        $expected = ['price_id' => 'price_link_id:1'];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->getPrice();

        $this->assertEquals($expected,$actual);
    }

    public function testConvertSimple()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'price_id'       => 'price_link_id:1',
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'colloques' =>[
               164 => [
                   'options' => [
                       0 => [
                           259,
                           269 => ['Un truc']
                       ]
                   ],
                   'groupes' => [
                       268 => 150
                   ]
               ],
                165 => [
                    'options' => [
                        0 => [
                            260,
                            261
                        ]
                    ],
                ]
            ]
        ];

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'colloques' =>[
                164 => [
                    'options' => [
                        259,
                        269 => ['Un truc']
                    ],
                    'groupes' => [
                        268 => 150
                    ]
                ],
                165 => [
                    'options' => [
                        260,
                        261
                    ],
                ]
            ],
            'price_id' => 'price_link_id:1',
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }

    public function testConvertSimpleEmptyOptions()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'price_id'       => 'price_link_id:1',
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'colloques' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => ['']
                        ]
                    ]
                ],
                165 => [
                    'options' => [0 => [260]],
                ]
            ]
        ];

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'colloques' =>[
                164 => [
                    'options' => [0 => 259]
                ],
                165 => [
                    'options' => [0 => 260],
                ]
            ],
            'price_id' => 'price_link_id:1',
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }

    public function testConvertMultiple()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'price_id'     => [
                "price_link_id:1",
                "price_link_id:1"
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'colloques' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => ['Un truc 1']
                        ],
                        1 => [
                            258,
                            259,
                            269 => ['Un truc 2']
                        ]
                    ],
                    'groupes' => [
                        [268 => 150],
                        [268 => 151]
                    ]
                ],
                165 =>[
                    'options' => [
                        0 => [261, 270],
                        1 => [260, 261]
                    ],
                ]
            ]
        ];

        $expected = [
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'colloques' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => ['Un truc 1']
                        ],
                        1 => [
                            258,
                            259,
                            269 => ['Un truc 2']
                        ]
                    ],
                    'groupes' => [
                        [268 => 150],
                        [268 => 151]
                    ]
                ],
                165 => [
                    'options'  => [
                        0 => [261, 270],
                        1 => [260, 261]
                    ],
                ]
            ],
            'price_id' => [
                "price_link_id:1",
                "price_link_id:1"
            ]
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }

    public function testConvertMultipleEmptyOption()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'price_id'     => [
                "price_link_id:1",
                "price_link_id:1"
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'colloques' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => ['']
                        ],
                        1 => [
                            258,
                            259,
                            269 => ['Un truc 2']
                        ]
                    ],
                    'groupes' => [
                        [268 => 150],
                        [268 => null]
                    ]
                ],
                165 =>[
                    'options' => [
                        0 => [261, 270],
                        1 => [260, 261]
                    ],
                ]
            ]
        ];

        $expected = [
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'colloques' =>[
                164 => [
                    'options' => [
                        0 => [
                            259
                        ],
                        1 => [
                            258,
                            259,
                            269 => ['Un truc 2']
                        ]
                    ],
                    'groupes' => [
                        [268 => 150]
                    ]
                ],
                165 => [
                    'options'  => [
                        0 => [261, 270],
                        1 => [260, 261]
                    ],
                ]
            ],
            'price_id' => [
                "price_link_id:1",
                "price_link_id:1"
            ]
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }

    public function testSimpleWithoutOptions()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'price_id'       => "price_link_id:1",
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
        ];

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'price_id'       => 'price_link_id:1'
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }

    public function testMultipleWithoutOptions()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'price_id'     => [
                "price_link_id:1",
                "price_link_id:1"
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
        ];

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'participant' => [
                'Marc,Leschaud',
                'Cindy,Leschaud'
            ],
            'email' => [
                'Marc.Leschaud@romandie.ch',
                'cindy.leschaud@gmail.com'
            ],
            'price_id' => [
                "price_link_id:1",
                "price_link_id:1"
            ]
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }

     public function testSimpleWithoutPriceNormal()
     {
         $data = [
             'reference_no'   => '',
             'transaction_no' => '',
             'price_id'       => "price_id:1",
             'user_id'        => 710,
             'colloque_id'    => 165,
             'type'           => 'simple',
         ];

         $expected = [
             'user_id'     => 710,
             'colloque_id' => 165,
             'type'        => 'simple',
             'price_id'    => "price_id:1",
         ];

         $register = new \App\Droit\Inscription\Entities\Register($data);
         $actual = $register->general();

         $this->assertEquals($expected,$actual);
     }

     public function testConvertSimpleMoreColloques()
     {
         $data = [
             'reference_no'   => '',
             'transaction_no' => '',
             'price_id'       => "price_link_id:1",
             'user_id'        => 710,
             'colloque_id'    => 165,
             'type'           => 'simple',
             'colloques' =>[
                 164 => [
                     'options' => [
                         [
                             259,
                             269 => ['Un truc']
                         ]
                     ],
                     'groupes' => [268 => 150]
                 ],
                 165 => [
                     'options'  => [
                        [
                            261,
                            270
                        ]
                     ],
                 ],
                 166 => [
                     'options' => [
                         [259]
                     ],
                     'groupes' => [268 => 150]
                 ]
             ]
         ];

         $expected = [
             'user_id'        => 710,
             'colloque_id'    => 165,
             'type'           => 'simple',
             'colloques' =>[
                 164 => [
                     'options' =>  [
                         259,
                         269 => ['Un truc']
                     ],
                     'groupes' => [268 => 150]
                 ],
                 165 => [
                     'options'  => [261, 270],
                 ],
                 166 => [
                     'options' => [259],
                     'groupes' => [268 => 150]
                 ]
             ],
             'price_id' => "price_link_id:1",
         ];

         $register = new \App\Droit\Inscription\Entities\Register($data);
         $actual = $register->general();

         $this->assertEquals($expected,$actual);
     }

    public function testConvertOptionsBackendSimple()
    {
        $data = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'colloques' =>[
                165 => [
                    'options' => [
                        0 => [
                            0 => 259,
                            269 => ['Un truc']
                        ]
                    ],
                    'groupes' => [268 => 150]
                ]
            ],
            'price_id' => 'price_link_id:1',
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'colloques' =>[
                165 => [
                    'options' => [
                        0   => 259,
                        269 => ['Un truc']
                    ],
                    'groupes' => [268 => 150]
                ]
            ],
            'price_id' => 'price_link_id:1',
        ];

        $this->assertEquals($expected,$actual);
    }

    public function testConvertOptionsFrontend()
    {
        $data = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'colloques' =>[
                165 => [
                    'options' => [
                        0 => [ // like in admin
                            0 => 259,
                            269 => ['Un truc']
                        ]
                    ],
                    'groupes' => [268 => 150]
                ]
            ],
            'price_id' => 'price_link_id:1',
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'colloques' =>[
                165 => [
                    'options' => [
                        0   => 259,
                        269 => ['Un truc']
                    ],
                    'groupes' => [268 => 150]
                ]
            ],
            'type'     => 'simple',
            'price_id' => 'price_link_id:1',
        ];

        $this->assertEquals($expected,$actual);
    }

    public function testConvertDataToCollectionOneColloque()
    {
        $data = [
            'user_id'     => 710,
            'colloque_id' => 165,
            'type'        => 'simple',
            'price_id'    => 'price_id:1',
        ];

        $expected = collect([
            [
                'user_id'      => 710,
                'colloque_id'  => 165,
                'type'         => 'simple',
                'price_id'     => '1',
            ]
        ]);

        $register = new \App\Droit\Inscription\Entities\Register();
        $actual = $register->prepare($data);

        $this->assertEquals($expected,$actual);
    }

    public function testConvertDataToCollectionColloques()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque1 = $make->colloque();

        $price      = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price_link = factory( \App\Droit\PriceLink\Entities\PriceLink::class)->create();
        $price_link->colloques()->attach([$colloque1->id]);

        $data = [
            'user_id'        => 710,
            'colloque_id'    => $colloque1->id,
            'type'           => 'simple',
            'colloques' =>[
                $colloque1->id => [
                    'options' => [
                        259,
                        269 => ['Un truc']
                    ],
                    'groupes' => [268 => 150]
                ]
            ],
            'price_id' => 'price_link_id:'.$price_link->id,
        ];

        $expected = collect([
            $colloque1->id => [
                'user_id'      => 710,
                'colloque_id'  => $colloque1->id,
                'type'         => 'simple',
                'options'  => [
                    259,
                    269 => ['Un truc']
                ],
                'groupes'  => [268 => 150],
                'price_link_id' => $price_link->id,
                'price_linked_id' => $price_link->id,
            ]
        ]);

        $register = new \App\Droit\Inscription\Entities\Register();
        $actual = $register->prepare($data);

        $this->assertEquals($expected,$actual);
    }

    public function testConvertDataToCollectionColloquesPrices()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque1 = $make->colloque();
        $colloque2 = $make->colloque();

        $price1 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price2 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'type' => 'admin']);

        $data = [
            'user_id'        => 710,
            'colloque_id'    => $colloque1->id,
            'type'           => 'simple',
            'colloques' => [
                $colloque1->id => [
                    'options' => [259],
                    'groupes' => [268 => 150]
                ],
                $colloque2->id => [
                    'options' => [261]
                ]
            ],
            'price_id' => 'price_link_id:1',
        ];

        $expected = collect([
            $colloque1->id => [
                'user_id'       => 710,
                'colloque_id'   => $colloque1->id,
                'type'          => 'simple',
                'options'       => [259],
                'groupes'       => [268 => 150],
                'price_link_id' => '1',
                'price_linked_id' => 1,
            ],
            $colloque2->id => [
                'user_id'       => 710,
                'colloque_id'   => $colloque2->id,
                'type'          => 'simple',
                'options'       => [261],
                'price_id'      => $price2->id,
                'price_linked_id' => 1,
            ]
        ]);

        //$this->repo_colloque->shouldReceive('find')->andReturn($colloque1);
        //$this->repo_colloque->shouldReceive('find')->andReturn($colloque2);

        $register = new \App\Droit\Inscription\Entities\Register();
        $actual = $register->prepare($data);

        $this->assertEquals($expected,$actual);
    }

    public function testMultiplePriceAndColloques()
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
                "price_link_id:".$price_link->id,
            ]
        ];

        $expected = collect([
            $colloque1->id => [
                'user_id'       => 710,
                'colloque_id'   => $colloque1->id,
                'type'          => 'multiple',
                'participant' => [
                    'Marc,Leschaud',
                    'Cindy,Leschaud'
                ],
                'email' => [
                    'Marc.Leschaud@romandie.ch',
                    'cindy.leschaud@gmail.com'
                ],
                'options' => [
                    0 => [259],
                    1 => [258]
                ],
                'groupes' => [
                    0 => [268 => 150],
                    1 => [268 => 151]
                ],
                'prices' => [
                    ["price_link_id" => $price_link->id],
                    ["price_link_id" => $price_link->id],
                ],
                'price_linked_id' => [
                    ['price_linked_id' => $price_link->id],
                    ['price_linked_id' => $price_link->id]
                ],
            ],
            $colloque2->id => [
                'user_id'       => 710,
                'colloque_id'   => $colloque2->id,
                'type'          => 'multiple',
                'participant' => [
                    'Marc,Leschaud',
                    'Cindy,Leschaud'
                ],
                'email' => [
                    'Marc.Leschaud@romandie.ch',
                    'cindy.leschaud@gmail.com'
                ],
                'options'  => [
                    0 => [261],
                    1 => [260]
                ],
                'prices' => [
                    ["price_id" => $price2->id],
                    ["price_id" => $price2->id],
                ],
                'price_linked_id' => [
                    ['price_linked_id' => $price_link->id],
                    ['price_linked_id' => $price_link->id]
                ],
            ]
        ]);

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->prepare($data);

        $this->assertEquals($expected,$actual);
    }

    public function testMultiplePriceAndColloquesNotSamePrice()
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
                'user_id'       => 710,
                'colloque_id'   => $colloque1->id,
                'type'          => 'multiple',
                'participant' => [
                    'Marc,Leschaud',
                    'Cindy,Leschaud'
                ],
                'email' => [
                    'Marc.Leschaud@romandie.ch',
                    'cindy.leschaud@gmail.com'
                ],
                'options' => [
                    0 => [259],
                    1 => [258]
                ],
                'groupes' => [
                    0 => [268 => 150],
                    1 => [268 => 151]
                ],
                'prices' => [
                    ["price_link_id" => $price_link->id],
                    ["price_id" => $price1->id],
                ],
                'price_linked_id' => [
                    ['price_linked_id' => $price_link->id],
                    ['price_linked_id' => null]
                ],
            ],
            $colloque2->id => [
                'user_id'       => 710,
                'colloque_id'   => $colloque2->id,
                'type'          => 'multiple',
                'participant' => [
                    'Marc,Leschaud',
                ],
                'email' => [
                    'Marc.Leschaud@romandie.ch',
                ],
                'options'  => [
                    0 => [261],
                ],
                'prices' => [
                    ["price_id" => $price2->id],
                ],
                'price_linked_id' => [
                    ['price_linked_id' => $price_link->id],
                ],
            ]
        ]);

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->multiple($data);

        echo '<pre>';
        print_r($actual);
        echo '</pre>';
        exit;

        $this->assertEquals($expected,$actual);
    }
}
