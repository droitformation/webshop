<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateTest extends BrowserKitTest {

	use DatabaseTransactions;
	
	public function setUp()
	{
		parent::setUp();

		DB::beginTransaction();

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

	public function testGetTypeOfModel()
	{
		$inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make();
		$group       = factory(App\Droit\Inscription\Entities\Groupe::class)->make();

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);
		$response = $generate->getType();

		$this->assertEquals('inscription', $response);

		$generate = new \App\Droit\Generate\Entities\Generate($group);
		$response = $generate->getType();

		$this->assertEquals('group', $response);
	}

	public function testGetNo()
	{
		$inscription  = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
			'inscription_no' => '10-2016/1'
		]);

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);
		$response = $generate->getNo();

		$this->assertEquals('10-2016/1', $response);

		$group        = factory(App\Droit\Inscription\Entities\Groupe::class)->make();
		$inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make();

		$inscriptions = $inscriptions->map(function ($item, $key) {
			$item->inscription_no = '10-2016/1'.$key;
			$item->participant = factory(\App\Droit\Inscription\Entities\Participant::class)->make([
				'name'           => 'Cindy Leschaud',
				'inscription_id' => '10-2016/1'.$key
			]);
			return $item;
		});

		$group->inscriptions = $inscriptions;

		$generate = new \App\Droit\Generate\Entities\Generate($group);
		$response = $generate->getNo();

		$participants = [
			'10-2016/10' => 'Cindy Leschaud',
			'10-2016/11' => 'Cindy Leschaud',
			'10-2016/12' => 'Cindy Leschaud'
		];

		$this->assertEquals($participants, $response);
	}

	public function testGetPrice()
	{
		$price        = factory(App\Droit\Price\Entities\Price::class)->make();
		$inscription  = factory(App\Droit\Inscription\Entities\Inscription::class)->make();

		$inscription->price = $price;

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);
		$response = $generate->getPrice();

		$this->assertEquals(40.00, $response);

		$group        = factory(App\Droit\Inscription\Entities\Groupe::class)->make();
		$inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make();
		$inscriptions = $inscriptions->map(function ($item, $key) {
			$item->price = factory(\App\Droit\Price\Entities\Price::class)->make();
			return $item;
		});

		$group->inscriptions = $inscriptions;

		$generate = new \App\Droit\Generate\Entities\Generate($group);
		$response = $generate->getPrice();

		$this->assertEquals(120.00, $response);
	}

	public function testGetPriceNames()
	{
		$price        = factory(App\Droit\Price\Entities\Price::class)->make(['description' => 'Prix normal']);
		$inscription  = factory(App\Droit\Inscription\Entities\Inscription::class)->make();

		$inscription->price = $price;

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);
		$response1 = $generate->getPrice();
		$response2 = $generate->getPriceName();

		$this->assertEquals(40.00, $response1);
		$this->assertEquals('Prix normal', $response2);

		$group        = factory(App\Droit\Inscription\Entities\Groupe::class)->make();
		$inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make();
		$inscriptions = $inscriptions->map(function ($item, $key) {
			$item->price = factory(\App\Droit\Price\Entities\Price::class)->make(['description' => 'Prix normal '.$key]);
			return $item;
		});

		$group->inscriptions = $inscriptions;

		$generate = new \App\Droit\Generate\Entities\Generate($group);
		$response1 = $generate->getPrice();
        $response2 = $generate->getPriceName();

		$this->assertEquals(120.00, $response1);
        $this->assertEquals(['Prix normal 0','Prix normal 1','Prix normal 2'], $response2);
	}

	public function testGetOptiions()
	{
		$inscription  = factory(App\Droit\Inscription\Entities\Inscription::class)->make();

		$user_option1 = factory(App\Droit\Option\Entities\OptionUser::class)->make();
		$user_option2 = factory(App\Droit\Option\Entities\OptionUser::class)->make();
		$user_option3 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['reponse' => 'Reponse']);

		// option normal
		$user_option1->option = factory(App\Droit\Option\Entities\Option::class)->make([
			'title' => 'Option',
			'type'  => 'checkbox'
		]);

		// option choix
		$user_option2->option = factory(App\Droit\Option\Entities\Option::class)->make([
			'title' => 'Option choix',
			'type'  => 'choix'
		]);

		$user_option2->option_groupe = factory(App\Droit\Option\Entities\OptionGroupe::class)->make([
			'text' => 'Groupe'
		]);

		// option choix
		$user_option3->option = factory(App\Droit\Option\Entities\Option::class)->make([
			'title' => 'Option text',
			'type'  => 'text'
		]);

		$inscription->user_options = new \Illuminate\Support\Collection([ $user_option1, $user_option2, $user_option3 ]);

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);
		$response = $generate->getOptions();

		$options = [
			['title' => 'Option'],
			['title' => 'Option choix','choice' => 'Groupe'],
			['title' => 'Option text', 'choice' => 'Reponse']
		];

		$this->assertEquals($options, $response);

	}

	public function testGetFilename()
	{
		$inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
			'id'          => '10',
			'user_id'     => '20',
			'colloque_id' => '12'
		]);

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);

		$response = $generate->getFilename('bon','bon');
		$filename = public_path('files/colloques/bon/bon_12-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('facture','facture');
		$filename = public_path('files/colloques/facture/facture_12-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('rappel','rappel_5');
		$filename = public_path('files/colloques/rappel/rappel_5_12.pdf');

		$this->assertEquals($response, $filename);

	}

	public function testGetFilenameGroup()
	{
		$group = factory(App\Droit\Inscription\Entities\Groupe::class)->make([
			'id'          => '2',
			'user_id'     => '20',
			'colloque_id' => '12'
		]);

		$inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make([
			'group_id'    => '5',
			'colloque_id' => '12'
		]);

		$inscriptions = $inscriptions->map(function ($item, $key) {
			$item->participant = factory(\App\Droit\Inscription\Entities\Participant::class)->make([ 'id' => $key ]);
			return $item;
		});

		$group->inscriptions = $inscriptions;

		$generate = new \App\Droit\Generate\Entities\Generate($group);

		$response = $generate->getFilename('facture','facture');
		$filename = public_path('files/colloques/facture/facture_12-2-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('bv','bv');
		$filename = public_path('files/colloques/bv/bv_12-2-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('rappel','rappel_6');
		$filename = public_path('files/colloques/rappel/rappel_6_12.pdf');

		$this->assertEquals($response, $filename);

		foreach($group->inscriptions as $index => $inscription)
		{
			$generate = new \App\Droit\Generate\Entities\Generate($inscription);

			$response = $generate->getFilename('bon','bon');

			$filename = public_path('files/colloques/bon/bon_12-5-'.$index.'.pdf');

			$this->assertEquals($response, $filename);
		}
	}

	public function testGetColloque()
	{
        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
            'id'          => '10',
            'user_id'     => '1',
            'colloque_id' => '12'
        ]);

        $inscription->colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);

        $generate = new \App\Droit\Generate\Entities\Generate($inscription);
        $response = $generate->getColloque();

        $this->assertEquals($response->id, 12);

        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make([
            'user_id'     => '1',
            'colloque_id' => '12'
        ]);

        $group->colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 12]);

        $generate = new \App\Droit\Generate\Entities\Generate($group);
        $response = $generate->getColloque();

        $this->assertEquals($response->id, 12);
	}

    public function testGetAdresse()
    {
		$make     = new \tests\factories\ObjectFactory();
		$person   = $make->makeUser();

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
            'id'          => '10',
            'user_id'     => '1',
            'colloque_id' => '12'
        ]);

        $inscription->user = $person;

        $generate = new \App\Droit\Generate\Entities\Generate($inscription);
        $response = $generate->getAdresse();

        $this->assertEquals($response->name, $person->name);
    }

	public function testGetOccurences()
	{
		$inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
			'id'          => '10',
			'user_id'     => '1',
			'colloque_id' => '12'
		]);

        $occurrences = factory( App\Droit\Occurrence\Entities\Occurrence::class,2)->make();
		$inscription->occurrences = $occurrences;

		$generate = new \App\Droit\Generate\Entities\Generate($inscription);
		$response = $generate->getOccurrences();

		$this->assertEquals($occurrences, $response);
	}

    public function testGetParticipant()
    {
        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make(['user_id'=> '20', 'colloque_id' => '12']);

        $inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make(['group_id' => '5', 'colloque_id' => '12']);

        $inscriptions = $inscriptions->map(function ($item, $key) {
            $item->participant = factory(\App\Droit\Inscription\Entities\Participant::class)->make(['id' => $key ,'name' => 'Cindy_'.$key ]);
            return $item;
        });

        $group->inscriptions = $inscriptions;

        foreach($group->inscriptions as $index => $inscription)
        {
            $generate = new \App\Droit\Generate\Entities\Generate($inscription);

            $response = $generate->getParticipant();

            $this->assertEquals($response, 'Cindy_'.$index);
        }
    }

}
