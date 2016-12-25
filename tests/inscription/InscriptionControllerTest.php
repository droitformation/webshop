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

        // Redirect because no session
        $this->visit('admin/inscription/make');
        $this->seePageIs('admin/inscription/create');

        // See pag with data
        $data = ['colloque_id' => $colloque->id, 'user_id' => $person->id,'type' => 'simple'];
        $response = $this->call('POST', 'admin/inscription/make', $data);

        $this->see('Créer une inscription simple');

       // $this->assertCount(1, $this->crawler->filter('input[name="price_id"][value="'.$prices[0].'"]'));

	}
}
