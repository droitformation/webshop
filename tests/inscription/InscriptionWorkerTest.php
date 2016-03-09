<?php

class InscriptionWorkerTest extends TestCase {

    protected $inscription;

    public function setUp()
    {
        parent::setUp();

        $this->inscription = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->inscription);

        $user = App\Droit\User\Entities\User::find(710);

        $this->actingAs($user);
    }

    public function tearDown()
    {
         \Mockery::close();
    }

    /**
     * Inscription regenerate documents
     * @return void
     */
    public function testPrepareDataInscriptionSendInscription()
    {
        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
            'id'          => '10',
            'user_id'     => '710',
            'colloque_id' => '12'
        ]);

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);
        $colloque->annexe = ['bon','facture','bv'];

        $inscription->colloque = $colloque;
        $inscription->user     = \App\Droit\User\Entities\User::find(710);

        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');

        $result = $worker->prepareData($inscription);

        $this->assertEquals($result['annexes'], $colloque->annexe);
        $this->assertEquals($result['user'], $inscription->user);
        $this->assertEquals($result['logo'], 'facdroit.png');

    }

    /**
     * @return void
     */
    public function testPrepareDataGroupSendInscription()
    {
        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make([
            'user_id'     => '20',
            'colloque_id' => '12'
        ]);

        $inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make(['group_id' => '5', 'colloque_id' => '12']);
        $inscriptions = $inscriptions->map(function ($item, $key) {
            $item->inscription_no = '10-2016/1'.$key;
            $item->participant = factory(\App\Droit\Inscription\Entities\Participant::class)->make([
                'name'           => 'Cindy Leschaud',
                'inscription_id' => '10-2016/1'.$key
            ]);
            return $item;
        });

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);
        $colloque->annexe = ['bon','facture','bv'];

        $group->colloque     = $colloque;
        $group->user         = \App\Droit\User\Entities\User::find(710);
        $group->inscriptions = $inscriptions;

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');

        $result = $worker->prepareData($group);

        $this->assertEquals($result['annexes'], $group->colloque->annexe);
        $this->assertEquals($result['user'],    $group->user);
        $this->assertEquals($result['logo'],   'facdroit.png');

        $participants = [
            '10-2016/10' => 'Cindy Leschaud',
            '10-2016/11' => 'Cindy Leschaud',
            '10-2016/12' => 'Cindy Leschaud'
        ];

        $this->assertEquals($result['participants'], $participants);

    }

    public function testUpdateInscription()
    {
        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
            'id'          => '10',
            'user_id'     => '710',
            'colloque_id' => '12'
        ]);

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);
        $colloque->annexe = ['bon','facture','bv'];

        $inscription->colloque = $colloque;
        $inscription->user     = \App\Droit\User\Entities\User::find(710);

        $this->inscription->shouldReceive('update')->once();

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->updateInscription($inscription);

    }

    /**
     * @return void
     */
    public function testUpdateGroupInscription()
    {
        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make([
            'user_id'     => '20',
            'colloque_id' => '12'
        ]);

        $inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make(['group_id' => '5', 'colloque_id' => '12']);
        $inscriptions = $inscriptions->map(function ($item, $key) {
            $item->inscription_no = '10-2016/1'.$key;
            $item->participant = factory(\App\Droit\Inscription\Entities\Participant::class)->make([
                'name'           => 'Cindy Leschaud',
                'inscription_id' => '10-2016/1'.$key
            ]);
            return $item;
        });

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);
        $colloque->annexe = ['bon','facture','bv'];

        $group->colloque     = $colloque;
        $group->user         = \App\Droit\User\Entities\User::find(710);
        $group->inscriptions = $inscriptions;

        $this->inscription->shouldReceive('update')->times(3);

        $worker = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $worker->updateInscription($group);

    }
}
