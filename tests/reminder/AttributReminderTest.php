<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AttributReminderTest extends TestCase {

	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		$user = factory(App\Droit\User\Entities\User::class,'admin')->create();

		$user->roles()->attach(1);
		$this->actingAs($user);

		DB::beginTransaction();
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
		parent::tearDown();
	}

	public function testCreateNewAttribut()
	{
		$this->visit(url('admin/attribut'));
		$this->click('attribut_create');

        $this->seePageIs(url('admin/attribut/create'));

        $this->type('Rappel pour le livre', 'title')
            ->type('The title', 'title')
            ->type('Dapibus ante suscipurcusit çunc, primiés?', 'text')
            ->check('reminder')
            ->select('week', 'duration')
            ->press('Envoyer');

        // Calculat the date for 1 week
        $send_at = \Carbon\Carbon::now()->addWeek()->toDateString();

		$this->seeInDatabase('shop_attributes', [
            'title'    => 'The title',
            'reminder' => true,
            'duration' => 'week',
		]);
	}

	public function testUpdateAttribut()
	{
       /* $make    = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Colloque: Default 1 month from start_at test if it's ok
        $send_at = $colloque->start_at->addMonth();

        // Colloque reminder with
		$reminder = factory(App\Droit\Reminder\Entities\Reminder::class)->create([
            'model_id' => $colloque->id,
            'send_at'  => $send_at->toDateString()
        ]);

		$this->visit(url('admin/reminder/'.$reminder->id));

        $this->assertEquals($reminder->send_at->toDateString(), $send_at->toDateString());

        // Update and add 1 week this time

        $this->type('Rappel pour le livre', 'title')
            ->type('Dapibus ante suscipurcusit çunc, primiés?', 'text')
            ->select('week', 'duration')
            ->select('start_at', 'start')
            ->select('colloque', 'type')
            ->select($colloque->id, 'model_id')
            ->press('Envoyer');

        $send_at = $colloque->start_at->addWeek();

        $this->seeInDatabase('reminders', [
            'id'       => $reminder->id,
            'duration' => 'week',
            'send_at'  => $send_at->toDateString()
        ]);*/

	}


}
