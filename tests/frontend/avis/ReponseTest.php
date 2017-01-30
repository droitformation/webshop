<?php

use Laravel\BrowserKitTesting\DatabaseTransactions;

class ReponseTest extends BrowserKitTest {

	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testReponseTestPage()
	{
		// Create colloque
		$make     = new \tests\factories\ObjectFactory();
		$colloque = $make->colloque();

		// Create a sondage for the colloque
		$sondage = factory(App\Droit\Sondage\Entities\Sondage::class)->create([
			'colloque_id' => $colloque->id,
			'valid_at'    => \Carbon\Carbon::now()->addDay(5),
		]);

		// Create and attach a questioin to sondage
		$question = factory(App\Droit\Sondage\Entities\Avis::class)->create(['type' => 'text','question' => 'One question' ,'choices' => null]);
		$sondage->avis()->attach($question->id, ['rang' => 1]);

		// Make the token with the infos
		$token = base64_encode(json_encode([
			'sondage_id' => $sondage->id,
			'email'      => 'cindy.leschaud@gmail.com',
			'isTest'     => 1,
		]));

		// Visit the sondage page and test if the question exist on the page
		$this->withSession(['sondage' => $sondage, 'email' => 'cindy.leschaud@gmail.com', 'isTest' => 1])
			->visit('reponse/create/'.$token)
			->see('One question')
			->submitForm('Envoyer le sondage', [
				'sondage_id' => $sondage->id,
				'reponses'   => [$question->id =>'Ceci est une réponse'],
				'email'      => 'cindy.leschaud@gmail.com',
				'isTest'     => 1
			])
			->seePageIs('reponse')
			->see('Merci pour votre participation au sondage!');

		// See if the reponse is in the database
		$this->seeInDatabase('sondage_reponses', [
			'sondage_id' => $sondage->id,
			'email'      => 'cindy.leschaud@gmail.com',
			'isTest'     => 1
		]);

		// Return see the sondage, it's a test so we can do the sondage again
		$this->visit('reponse/create/'.$token)->see('One question');
	}

	public function testReponseNormalPage()
	{
		// Create colloque
		$make     = new \tests\factories\ObjectFactory();
		$colloque = $make->colloque();

		// Create a sondage for the colloque
		$sondage = factory(App\Droit\Sondage\Entities\Sondage::class)->create([
			'colloque_id' => $colloque->id,
			'valid_at'    => \Carbon\Carbon::now()->addDay(5),
		]);

		// Create and attach a questioin to sondage
		$question = factory(App\Droit\Sondage\Entities\Avis::class)->create(['type' => 'text','question' => 'One question' ,'choices' => null]);
		$sondage->avis()->attach($question->id, ['rang' => 1]);

		// Make the token with the infos
		$token = base64_encode(json_encode([
			'sondage_id' => $sondage->id,
			'email'      => 'cindy.leschaud@gmail.com'
		]));

		// Visit the sondage page and test if the question exist on the page
		$this->withSession(['sondage' => $sondage, 'email' => 'cindy.leschaud@gmail.com', 'isTest' => null])
			->visit('reponse/create/'.$token)
			->see('One question')
			->submitForm('Envoyer le sondage', [
				'sondage_id' => $sondage->id,
				'reponses'   => [$question->id => 'Ceci est une réponse'],
				'email'      => 'cindy.leschaud@gmail.com',
				'isTest'     => false
			])
			->seePageIs('reponse')
			->see('Merci pour votre participation au sondage!');

		// See if the reponse is in the database
		$this->seeInDatabase('sondage_reponses', [
			'sondage_id' => $sondage->id,
			'email'      => 'cindy.leschaud@gmail.com',
			'isTest'     => null
		]);

		// Return see the sondage, but it's already done so redirect to reponse page with message
		$this->visit('reponse/create/'.$token)
			->seePageIs('reponse')
			->see('Vous avez déjà répondu au sondage!');
	}

	public function testCreateQuestion()
	{
		$user = factory(App\Droit\User\Entities\User::class)->create();
		$user->roles()->attach(1);
		$this->actingAs($user);

		$this->visit('admin/avis');
		$this->assertViewHas('avis');

		$this->click('addBtn')->seePageIs(url('admin/avis/create'));
		$this->select('text', 'type')
			->type('Une nouvelle question', 'question')
			->press('Envoyer');

		// See if the reponse is in the database
		$this->seeInDatabase('sondage_avis', [
			'type'    => 'text',
			'question' => 'Une nouvelle question'
		]);
	}
}
