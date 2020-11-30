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

    public function testRegisterGroupData()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $occurrences = factory(\App\Droit\Occurrence\Entities\Occurrence::class,2)->create(['colloque_id' => $colloque->id,]);
        $option  = factory(\App\Droit\Option\Entities\Option::class)->create(['colloque_id' => $colloque->id, 'title'  => 'Option', 'type' => 'choix',]);
        $groupe1 = factory(\App\Droit\Option\Entities\OptionGroupe::class)->create(['colloque_id' => $colloque->id, 'option_id' => $option->id, 'text' => 'Groupe']);
        $groupe2 = factory(\App\Droit\Option\Entities\OptionGroupe::class)->create(['colloque_id' => $colloque->id, 'option_id' => $option->id, 'text' => 'Autre groupe']);

        // AFTER REGISTER CONVERTER
        $data = [
            'colloque_id'    => $colloque->id ,
            'user_id'        => $person->id,
            'reference_no'   => '21345',
            'transaction_no' => '6543',
            'participants'   => [
                [
                    'participant' => 'Cindy, Leschaud',
                    'email'      => 'cindy.leschaud@gmail.com',
                    'options'    => [$options[0]],
                    'groupes'    => [$option->id => $groupe1->id],
                    'occurrences' => [$occurrences->first()->id],
                    "price_id"   => $prices[0]
                ],
                [
                    'participant' => 'Coralie, Ahmetaj',
                    'email'       => 'coralie.ahmetaj@hotmail.com',
                    'options'     => [$options[0]],
                    'groupes'     => [$option->id => $groupe2->id],
                    'occurrences' => $occurrences->pluck('id')->all(),
                    "price_id"    => $prices[0]
                ]
            ]
        ];

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->register($data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => null,
            'price_id'    => $prices[0],
        ]);

        $this->assertDatabaseHas('colloque_inscriptions_groupes', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
        ]);

        $model = \App\Droit\Inscription\Entities\Inscription::latest('created_at')->first();

        $this->assertEquals('Cindy, Leschaud', $model->participant->name);
        $this->assertEquals(1, $model->occurrences->count());
        $this->assertEquals(2, $model->user_options->count());

        $this->assertTrue($model->user_options->contains('option_id',$option->id));
        $this->assertTrue($model->user_options->contains('option_id',$options[0]));
        $this->assertTrue($model->user_options->contains('groupe_id',$groupe1->id));
    }

    public function testRegister()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $option = factory(\App\Droit\Option\Entities\Option::class)->create([
            'colloque_id' => $colloque->id,
            'title'       => 'Option',
            'type'        => 'choix',
        ]);

        $groupe1 = factory(\App\Droit\Option\Entities\OptionGroupe::class)->create(['colloque_id' => $colloque->id, 'option_id' => $option->id, 'text' => 'Groupe']);
        $groupe2 = factory(\App\Droit\Option\Entities\OptionGroupe::class)->create(['colloque_id' => $colloque->id, 'option_id' => $option->id, 'text' => 'Autre groupe']);

        $occurrences = factory(\App\Droit\Occurrence\Entities\Occurrence::class,2)->create([
            'colloque_id' => $colloque->id,
        ]);

        // AFTER REGISTER CONVERTER
        $data = [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
            'occurrences' => $occurrences->pluck('id')->all(),
            'options'     => [
                $options[0]
            ],
            'groupes' => [
                $option->id => $groupe1->id
            ]
        ];

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->register($data,true);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
        ]);

        $model = \App\Droit\Inscription\Entities\Inscription::latest('created_at')->first();

        $this->assertEquals(2, $model->occurrences->count());
        $this->assertEquals(2, $model->user_options->count());

        $this->assertTrue($model->user_options->contains('option_id',$options[0]));
        $this->assertTrue($model->user_options->contains('option_id',$option->id));
        $this->assertTrue($model->user_options->contains('groupe_id',$groupe1->id));
    }

    public function testUpdateDateSend()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $worker->updateInscription($inscription);

        $this->assertDatabaseHas('colloque_inscriptions', [
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
            $this->assertDatabaseHas('colloque_inscriptions', [
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
            'title'       => 'Votre inscription sur publications-droit.ch',
            'logo'        => 'facdroit.png',
            'concerne'    => 'Inscription',
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            'annexes'     => $inscription->colloque->annexe,
            'colloque'    => $inscription->colloque,
            'inscription' => $inscription,
            'user'        => $inscription->user,
            'attachements'=> $inscription->documents
        ];

        $this->assertEquals($data, $result);
    }

    public function testPrepareDataPassAttachments()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();
        $attachments = $inscription->documents;

        $result = $worker->prepareData($inscription, ['test' => 1]);

        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'logo'        => 'facdroit.png',
            'concerne'    => 'Inscription',
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            'annexes'     => $inscription->colloque->annexe,
            'colloque'    => $inscription->colloque,
            'inscription' => $inscription,
            'user'        => $inscription->user,
            'attachements'=> ['test' => 1]
        ];

        $this->assertEquals($data, $result);
    }

    public function testPrepareDataPassAttachmentsEmpty()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();
        $attachments = $inscription->documents;

        $result = $worker->prepareData($inscription);

        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'logo'        => 'facdroit.png',
            'concerne'    => 'Inscription',
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            'annexes'     => $inscription->colloque->annexe,
            'colloque'    => $inscription->colloque,
            'inscription' => $inscription,
            'user'        => $inscription->user,
            'attachements'=> $attachments
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
            'inscription'  => $group->groupe->inscriptions->first(),
            'participants' => $group->groupe->participant_list,
            'attachements' => []
        ];

        $this->assertEquals($data, $result);
    }

    public function testAddSpecialisationToUser()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');

        $coll_specialisations = factory(\App\Droit\Specialisation\Entities\Specialisation::class,2)->create();
        $user_specialisations = factory(\App\Droit\Specialisation\Entities\Specialisation::class,2)->create();

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

        $inscription->fresh();

        $this->assertTrue(true);

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
        $this->assertTrue(true);

        //$this->assertEquals(3, count($inscription->annexe));
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
        $this->assertTrue(true);
    }

    public function testCounter()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $this->assertEquals(0,$colloque->counter);

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

        $this->assertDatabaseHas('colloques', [
            'id'      => $colloque->id,
            'counter' => 1
        ]);
    }

    public function testGetInscriptionForColloque()
    {
        $model    = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        $first->status   = 'payed';
        $first->payed_at = '2017-09-25';
        $first->save();

        $price = factory(\App\Droit\Price\Entities\Price::class)->create(['price' => 0, 'colloque_id' => $colloque->id]);

        $second->price_id = $price->id;
        $second->save();

        $inscriptions = $model->getColloqe($colloque->id);
        $this->assertEquals(3, $inscriptions->count());

        $inscriptions = $model->getColloqe($colloque->id, false, ['status' => 'pending']);
        $this->assertEquals(1, $inscriptions->count());
        $this->assertEquals($third->id, $inscriptions->first()->id);

        $inscriptions = $model->getColloqe($colloque->id, false, ['status' => 'free']);
        $this->assertEquals(1, $inscriptions->count());
        $this->assertEquals($second->id, $inscriptions->first()->id);

        $inscriptions = $model->getColloqe($colloque->id, false, ['status' => 'payed']);
        $this->assertEquals(1, $inscriptions->count());
        $this->assertEquals($first->id, $inscriptions->first()->id);

    }

    public function testDeleteInscriptionSimple()
    {
        // Create colloque
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $worker->unsubscribe($inscription);

        $inscription = $inscription->fresh();

        $this->assertNotNull($inscription->deleted_at);
    }

    public function testDeleteInscriptionLinked()
    {
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();

        $inscriptions = $make->makePricelinkInscription();

        $inscription1 = $inscriptions->shift();
        $inscription2 = $inscriptions->shift();

        $worker->unsubscribe($inscription1);

        $inscription1 = $inscription1->fresh();
        $inscription2 = $inscription2->fresh();

        $this->assertNotNull($inscription1->deleted_at);
        $this->assertNotNull($inscription2->deleted_at);
    }

    public function testdeleteOneInscriptionFromGroupLinked()
    {
        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make = new \tests\factories\ObjectFactory();

        $inscriptions = $make->makeMultipleInscriptionsPricelink();

        $inscription1 = $inscriptions->shift();
        $inscription2 = $inscriptions->shift();
        $inscription3 = $inscriptions->shift();
        $inscription4 = $inscriptions->shift();

        $this->generator->shouldReceive('make')->times(3);

        $worker->unsubscribe($inscription1);

        $inscription1 = $inscription1->fresh();
        $inscription2 = $inscription2->fresh();

        $this->assertNotNull($inscription1->deleted_at);
        $this->assertNotNull($inscription2->deleted_at);
    }
}
