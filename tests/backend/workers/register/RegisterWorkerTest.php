<?php

class RegisterWorker extends TestCase {

    public function setUp()
    {
        parent::setUp();

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


    public function testRegisterGroupData()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $data = [
            'colloque_id' => $colloque->id ,
            'user_id'     => $person->id,
            'participant' => [
                'Cindy Leschaud',
                'Coralie Ahmetaj'
            ],
            'price_id' => [
                $prices[0],
                $prices[0]
            ],
            'occurrences' => [
                [2],
                [2,3]
            ],
            'options' => [
                0 => [
                    $options[0],
                    [148 => 'psum odolr amet']
                ],
                1 => [
                    $options[0], [148 => 'lorexm ipsu']
                ]
            ],
            'groupes' => [
                [147 => 44],
                [147 => 45]
            ]
        ];

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->register($data);

        $this->seeInDatabase('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => null,
            'price_id'    => $prices[0],
        ]);

        $this->seeInDatabase('colloque_inscriptions_groupes', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
        ]);
    }

    public function testRegister()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $data = [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
            'options'     => [
                $options[0]
            ]
        ];

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->register($data,true);

        $this->seeInDatabase('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
        ]);
    }
    
}
