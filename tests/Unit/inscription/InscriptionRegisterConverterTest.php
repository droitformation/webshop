<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InscriptionRegisterConverterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testConvertSimple()
    {
        $data = [
            'reference_no'   => '',
            'transaction_no' => '',
            'price_id'       => "price_link_id:1",
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'options'        => [
                260 => [
                    1 => 261,
                    2 => 270
                ]
            ],
            'colloque' =>[
               164 => [
                   'options' => [
                       0 => [
                           259,
                           269 => [
                               ['Un truc']
                           ]
                       ]
                   ],
                   'groupes' => [
                       268 => 150
                   ]
               ]
            ]
        ];

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'options'        => [
                260 => [
                    1 => 261,
                    2 => 270
                ]
            ],
            'colloques' =>[
                164 => [
                    'options' => [
                        259,
                        269 => [['Un truc']]
                    ],
                    'groupes' => [
                        268 => 150
                    ]
                ]
            ],
            'price_link_id'  => 1,
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
            'price_id[]'     => [
                "price_link_id:1",
                "price_link_id:1"
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'options'        => [
                [
                    0 => 261,
                    1 => 270
                ],
                [
                    0 => 260,
                    1 => 261
                ]
            ],
            'colloque' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => [
                                ['Un truc 1']
                            ]
                        ],
                        1 => [
                            258,
                            259,
                            269 => [
                                ['Un truc 2']
                            ]
                        ]
                    ],
                    'groupes' => [
                        [268 => 150],
                        [268 => 151]
                    ]
                ]
            ],
            'price_id' => [
                'price_link_id:1',
                'price_link_id:1'
            ]
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
            'options'        => [
                [
                    0 => 261,
                    1 => 270
                ],
                [
                    0 => 260,
                    1 => 261
                ]
            ],
            'colloques' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => [
                                ['Un truc 1']
                            ]
                        ],
                        1 => [
                            258,
                            259,
                            269 => [
                                ['Un truc 2']
                            ]
                        ]
                    ],
                    'groupes' => [
                        [268 => 150],
                        [268 => 151]
                    ]
                ]
            ],
            'prices' => [
                ['price_link_id' => 1],
                ['price_link_id' => 1],
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
            'price_link_id'  => 1,
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
            'price_id[]'     => [
                "price_link_id:1",
                "price_link_id:1"
            ],
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'multiple',
            'price_id' => [
                'price_link_id:1',
                'price_link_id:1'
            ]
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
            'prices' => [
                ['price_link_id' => 1],
                ['price_link_id' => 1],
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
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'price_id'  => 1,
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
            'options'        => [
                260 => [
                    1 => 261,
                    2 => 270
                ]
            ],
            'colloque' =>[
                164 => [
                    'options' => [
                        0 => [
                            259,
                            269 => [
                                ['Un truc']
                            ]
                        ]
                    ],
                    'groupes' => [268 => 150]
                ],
                166 => [
                    'options' => [
                        0 => [259]
                    ],
                    'groupes' => [268 => 150]
                ]
            ]
        ];

        $expected = [
            'user_id'        => 710,
            'colloque_id'    => 165,
            'type'           => 'simple',
            'options'        => [
                260 => [
                    1 => 261,
                    2 => 270
                ]
            ],
            'colloques' =>[
                164 => [
                    'options' =>  [
                        259,
                        269 => [
                            ['Un truc']
                        ]
                    ],
                    'groupes' => [268 => 150]
                ],
                166 => [
                    'options' => [259],
                    'groupes' => [268 => 150]
                ]
            ],
            'price_link_id'  => 1,
        ];

        $register = new \App\Droit\Inscription\Entities\Register($data);
        $actual = $register->general();

        $this->assertEquals($expected,$actual);
    }
}
