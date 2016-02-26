<?php

class GenerateTest extends TestCase {

	public function setUp()
	{
		parent::setUp();
	}

	public function testGetTypeOfModel()
	{
		$inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make();
		$group       = factory(App\Droit\Inscription\Entities\Groupe::class)->make();

		$generate = new \App\Droit\Inscription\Entities\Generate($inscription);
		$response = $generate->getType();

		$this->assertEquals('inscription', $response);

		$generate = new \App\Droit\Inscription\Entities\Generate($group);
		$response = $generate->getType();

		$this->assertEquals('group', $response);
	}

	public function testGetNo()
	{
		$inscription  = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
			'inscription_no' => '10-2016/1'
		]);

		$generate = new \App\Droit\Inscription\Entities\Generate($inscription);
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

		$generate = new \App\Droit\Inscription\Entities\Generate($group);
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

		$generate = new \App\Droit\Inscription\Entities\Generate($inscription);
		$response = $generate->getPrice();

		$this->assertEquals(4000, $response);

		$group        = factory(App\Droit\Inscription\Entities\Groupe::class)->make();
		$inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make();
		$inscriptions = $inscriptions->map(function ($item, $key) {
			$item->price = factory(\App\Droit\Price\Entities\Price::class)->make();
			return $item;
		});

		$group->inscriptions = $inscriptions;

		$generate = new \App\Droit\Inscription\Entities\Generate($group);
		$response = $generate->getPrice();

		$this->assertEquals(12000, $response);
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

		$generate = new \App\Droit\Inscription\Entities\Generate($inscription);
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

		$generate = new \App\Droit\Inscription\Entities\Generate($inscription);

		$response = $generate->getFilename('bon','bon');
		$filename = public_path('files/colloques/bon/bon_12-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('facture','facture');
		$filename = public_path('files/colloques/facture/facture_12-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('rappel','rappel_5');
		$filename = public_path('files/colloques/rappel/rappel_5_12-20.pdf');

		$this->assertEquals($response, $filename);

		/*
		$group        = factory(App\Droit\Inscription\Entities\Groupe::class)->make();
		$inscriptions = factory(App\Droit\Inscription\Entities\Inscription::class,3)->make();

		$inscriptions = $inscriptions->map(function ($item, $key) {
			$item->participant = factory(\App\Droit\Inscription\Entities\Participant::class)->make([ 'id' => $key ]);
			return $item;
		});

		$group->inscriptions = $inscriptions;

		$generate = new \App\Droit\Inscription\Entities\Generate($group);

		$response = $generate->getFilename($annexe,$name);

		$this->assertEquals($participants, $response);
		*/
	}

	public function testGetFilenameGroup()
	{
		$group = factory(App\Droit\Inscription\Entities\Groupe::class)->make([
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

		$generate = new \App\Droit\Inscription\Entities\Generate($group);

		$response = $generate->getFilename('facture','facture');
		$filename = public_path('files/colloques/facture/facture_12-20.pdf');

		$this->assertEquals($response, $filename);

		$response = $generate->getFilename('rappel','rappel_7');
		$filename = public_path('files/colloques/rappel/rappel_7_12-20.pdf');

		$this->assertEquals($response, $filename);

		foreach($group->inscriptions as $index => $inscription)
		{
			$generate = new \App\Droit\Inscription\Entities\Generate($inscription);

			$response = $generate->getFilename('bon','bon');

			$filename = public_path('files/colloques/bon/bon_12-5-'.$index.'.pdf');

			$this->assertEquals($response, $filename);
		}

	}
}
