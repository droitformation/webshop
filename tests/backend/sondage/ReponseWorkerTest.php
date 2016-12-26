<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReponseWorkerTest extends TestCase {

	use DatabaseTransactions;

	protected $mock;

	public function setUp()
	{
		parent::setUp();

/*		$this->mock = Mockery::mock('App\Droit\Sondage\Repo\ReponseInterface');
		$this->app->instance('App\Droit\Sondage\Repo\ReponseInterface', $this->mock);*/
	}

	public function tearDown()
	{
		parent::tearDown();
	}
	
	public function testCreateReponse()
	{
		$worker = new App\Droit\Sondage\Worker\ReponseWorker(App::make('App\Droit\Sondage\Repo\ReponseInterface'));

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

        $data = [
            'sondage_id' => $sondage->id,
            'email'      => 'cindy.leschaud@gmail.com',
            'isTest'     => null,
        ];

        $reponses = [
            'reponses' => [
                $question->id => 'Ceci est une réponse'
            ]
        ];

        // Create a reponse
        $reponse = $worker->make($data, $reponses);

        // Assert that the reponse was correctly created
        $this->assertEquals(1, $reponse->items()->count());
        $this->assertEquals('Ceci est une réponse', $reponse->items()->first()->reponse);
        $this->assertEquals($sondage->id, $reponse->sondage_id);
	}
}
