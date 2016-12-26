<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class InscriptionControllerTest extends TestCase {

	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testMakeSimpleInscription()
	{
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

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
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);
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
            'type'        => 'multiple',
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
}
