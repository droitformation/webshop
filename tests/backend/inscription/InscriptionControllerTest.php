<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class InscriptionControllerTest extends BrowserKitTest {

	use DatabaseTransactions;

	public function setUp()
	{
        parent::setUp();
        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);
	}

	public function tearDown()
	{
		parent::tearDown();
	}

    public function testSearchInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        // All inscriptions

        $this->visit('admin/inscription/colloque/'.$colloque->id);
        $this->assertViewHas('inscriptions');

        $content = $this->response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals($colloque->inscriptions->count(), $inscriptions->count());

        // with search results

        $this->call('POST', 'admin/inscription/colloque/'.$colloque->id, ['inscription_no' => $inscription->inscription_no]);

        $content = $this->response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];
        $result  = $inscriptions->first();

        $this->assertEquals($result->inscription_no, $inscription->inscription_no);
    }

    public function testCreateInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $this->visit('admin/inscription/create');
        $this->see('Créer une Inscription');
        $this->assertViewHas('colloques');
    }

    public function testCreateInscriptionColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $this->visit('admin/inscription/create/'.$colloque->id);
        $this->see($colloque->title);
        $this->see('Créer une Inscription');
        $this->assertViewHas('colloques');
    }

    public function testDesinscriptionList()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('admin/desinscription/'.$colloque->id);
        $this->assertViewHas('desinscriptions');
    }

    public function testDesinscription()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = factory(App\Droit\User\Entities\User::class)->create();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create(['user_id' => $person->id, 'group_id' => null, 'colloque_id' => $colloque->id]);

        $this->visit('admin/inscription/colloque/'.$colloque->id);
        $this->call('DELETE', 'admin/inscription/'.$inscription->id);

        $this->notSeeInDatabase('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'deleted_at'  => null
        ]);

        $this->visit('admin/desinscription/'.$colloque->id);
        $this->call('POST', 'admin/desinscription/restore/'.$inscription->id);

        $this->seeInDatabase('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'deleted_at'  => null
        ]);
    }

	public function testMakeSimpleInscription()
	{
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        // Redirect because no session
        $this->visit('admin/inscription/make');
        $this->seePageIs('admin/inscription/create');
        
        // See pag with data
        $data = ['colloque_id' => $colloque->id, 'user_id' => $person->id,'type' => 'simple'];
        $this->call('POST', 'admin/inscription/make', $data);

        $this->see('Créer une inscription simple');

        $data = [
            'type'        => 'simple' ,
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
            'options'     => [
                $options[0]
            ]
        ];

        $this->call('POST', 'admin/inscription', $data);

        $this->seeInDatabase('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
        ]);

	}

    public function testMakeMultipleInscription()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();
        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        // Redirect because no session
        $this->visit('admin/inscription/make');
        $this->seePageIs('admin/inscription/create');

        // See pag with data
        $data = ['colloque_id' => $colloque->id, 'user_id' => $person->id, 'type' => 'multiple'];
        $this->call('POST', 'admin/inscription/make', $data);

        $this->see('Créer une inscription multiple');

        $data = [
            'colloque_id' => $colloque->id ,
            'user_id'     => $person->id,
            'participant' => [
                'Cindy Leschaud',
                'Coralie Ahmetaj'
            ],
            'email' => [
                'cindy.leschaud@gmail.com',
                'coralie.ahmetaj@hotmail.com'
            ],
            'price_id' => [
                $prices[0],
                $prices[0]
            ],
            'options' => [
                0 => [$options[0]],
                1 => [$options[0]]
            ]
        ];

        $reponse = $this->call('POST', 'admin/inscription', $data);
        $content = $this->followRedirects()->response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];
        $inscription  = $inscriptions->first();

        $this->seeInDatabase('colloque_inscriptions', [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'group_id'    => $inscription->group_id,
            'price_id'    => $prices[0],
        ]);
    }

    public function testEditColumnsViaAjax()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create(['user_id' => $person->id, 'group_id' => null, 'colloque_id' => $colloque->id]);

        $data = [
            'model'    => 'inscription' ,
            'pk'       => $inscription->id,
            'name'     => 'payed_at',
            'value'    => \Carbon\Carbon::now()->toDateString()
        ];

        $result = ['OK' => 200, 'etat' => 'Payé','color' => 'success'];

        $this->json('POST', 'admin/inscription/edit', $data)->seeJson($result);

        $this->seeInDatabase('colloque_inscriptions', [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'payed_at'    => \Carbon\Carbon::now()->toDateString(),
        ]);
    }

    public function testEditGroupeColumnsViaAjax()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        });
        
        $groupe = $inscriptions->first();

        $data = [
            'model'    => 'group' ,
            'pk'       => $groupe->groupe->id,
            'name'     => 'payed_at',
            'value'    => \Carbon\Carbon::now()->toDateString()
        ];

        $result = ['OK' => 200, 'etat' => 'Payé','color' => 'success'];

        $this->json('POST', 'admin/inscription/edit', $data)->seeJson($result);

        foreach($inscriptions as $inscription)
        {
            $this->seeInDatabase('colloque_inscriptions', [
                'id'          => $inscription->id,
                'colloque_id' => $colloque->id,
                'payed_at'    => \Carbon\Carbon::now()->toDateString(),
            ]);
        }
    }
}
