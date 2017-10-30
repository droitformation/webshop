<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReponseWorkerTest extends BrowserKitTest {

	use DatabaseTransactions;

	protected $mock;

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

        // Create and attach a question to sondage
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

    public function testUpdateSorting()
    {
        $data     = [2,3,1,4];
        $expected = [
            2 => ['rang' => 0],
            3 => ['rang' => 1],
            1 => ['rang' => 2],
            4 => ['rang' => 3]
        ];

        $repo   = \App::make('App\Droit\Sondage\Repo\SondageInterface');
        $result = $repo->sorting($data);

        $this->assertEquals($result, $expected);
    }
}
