<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class InscriptionWorkerTest extends BrowserKitTest {

    protected $generator;
    
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $this->generator = Mockery::mock('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->app->instance('App\Droit\Generate\Pdf\PdfGeneratorInterface', $this->generator);

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

    public function testUpdateDateSend()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $worker->updateInscription($inscription);

        $this->seeInDatabase('colloque_inscriptions', [
            'id'      => $inscription->id,
            'send_at' => date('Y-m-d'),
            'admin'   => 1
        ]);
    }

    public function testUpdateDateSendGroupe()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1,1);

        $group = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $worker->updateInscription($group->groupe);

        foreach($group->groupe->inscriptions as $inscription)
        {
            $this->seeInDatabase('colloque_inscriptions', [
                'id'      => $inscription->id,
                'send_at' => date('Y-m-d'),
                'admin'   => 1
            ]);
        }
    }

    public function testPrepareData()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $result = $worker->prepareData($inscription);

        $data = [
            'title'     => 'Votre inscription sur publications-droit.ch',
            'logo'      => 'facdroit.png',
            'concerne'  => 'Inscription',
            'date'      => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            'annexes'   => $inscription->colloque->annexe,
            'colloque'  => $inscription->colloque,
            'user'      => $inscription->user
        ];

        $this->assertEquals($data, $result);
    }

    public function testPrepareDataGroup()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1,1);

        $group = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $result = $worker->prepareData($group->groupe);

        $data = [
            'title'        => 'Votre inscription sur publications-droit.ch',
            'logo'         => 'facdroit.png',
            'concerne'     => 'Inscription',
            'date'         => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            'annexes'      => $group->groupe->colloque->annexe,
            'colloque'     => $group->groupe->colloque,
            'user'         => $group->groupe->user,
            'participants' => $group->groupe->participant_list
        ];

        $this->assertEquals($data, $result);
    }

    public function testAddSpecialisationToUser()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');

        $coll_specialisations = factory(App\Droit\Specialisation\Entities\Specialisation::class,2)->create();
        $user_specialisations = factory(App\Droit\Specialisation\Entities\Specialisation::class,2)->create();

        $colloque->specialisations()->attach($coll_specialisations->pluck('id')->all());
        $person->adresse_contact->specialisations()->attach($user_specialisations->pluck('id')->all());

        $all = array_merge($coll_specialisations->pluck('id')->all(), $user_specialisations->pluck('id')->all());
        sort($all);

        $worker->specialisation($colloque, $person);

        $person->adresse_contact->load('specialisations');
        $result = $person->adresse_contact->specialisations->pluck('id')->all();
        sort($result);

        $this->assertEquals($all, $result);

    }

    public function testMakedocuments()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $this->generator->shouldReceive('make')->times(3);

        $worker->makeDocuments($inscription, true);
    }

    public function testMakedocumentsGroupe()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1,1);

        $group = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $this->generator->shouldReceive('make')->times(4);// 2x bon 1x facture, 1x bv

        $worker->makeDocuments($group->groupe, true);
    }

    public function testMakeOnlyBon()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);
        $colloque = $make->colloqueOnlyBon($colloque);

        $inscription = $colloque->inscriptions->first();

        $this->generator->shouldReceive('make')->times(1);

        $worker->makeDocuments($inscription, true);
    }
}
