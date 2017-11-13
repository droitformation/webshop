<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReminderTest extends BrowserKitTest {

	use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

		$user = factory(App\Droit\User\Entities\User::class)->create();

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

	public function testCreateNewReminder()
	{
       // Make a product
        $make    = new \tests\factories\ObjectFactory();
        $product = $make->product();

		$this->visit('admin/reminder');
		$this->click('reminder_product');

        $this->seePageIs(url('admin/reminder/create/product'));

        $this->type('Rappel pour le livre', 'title')
            ->type('Dapibus ante suscipurcusit çunc, primiés?', 'text')
            ->select('created_at', 'start')
            ->select('week', 'duration')
            ->select($product->id, 'model_id')
            ->press('Envoyer');

        // Calculat the date for 1 week
        $send_at = $product->created_at->addWeek();

		$this->seeInDatabase('reminders', [
            'send_at'  => $send_at->toDateString(),
            'title'    => 'Rappel pour le livre',
            'model_id' => $product->id,
            'type'     => 'product',
		]);
	}

	public function testUpdateReminder()
	{
        $make    = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Colloque: Default 1 month from start_at test if it's ok
        $send_at = $colloque->start_at->addMonth();

        // Colloque reminder with
		$reminder = factory(App\Droit\Reminder\Entities\Reminder::class)->create([
            'model_id' => $colloque->id,
            'send_at'  => $send_at->toDateString()
        ]);

		$this->visit('admin/reminder/'.$reminder->id);

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
        ]);

	}

	public function testDeleteReminder()
	{
        $make    = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Colloque reminder with
		$reminder = factory(App\Droit\Reminder\Entities\Reminder::class)->create([
            'model_id' => $colloque->id,
        ]);

        $response = $this->call('DELETE','admin/reminder/'.$reminder->id, [] ,['id' => $reminder->id]);

		$this->notSeeInDatabase('reminders', [
			'id' => $reminder->id,
            'deleted_at' => null
        ]);
	}

    public function testReminderNoDates()
    {
        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->visit('admin/reminder/create/product');

        // Make a product
        $make    = new \tests\factories\ObjectFactory();
        $product = $make->product();

        $data = [
            'start'    => 'send_at',
            'title'    => 'Rappel pour le livre',
            'text'     => 'Dapit',
            'type'     => 'product',
            'duration' => 'month',
            'model_id' => $product->id,
            'model'    => 'App\Droit\Shop\Product\Entities\Product',
        ];

        $response = $this->call('POST', '/admin/reminder', $data);

        $this->assertRedirectedTo('admin/reminder/create/product');
        $this->assertSessionHas('alert.style','danger');
    }

    public function testReminderUpdateNoDates()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        // Colloque reminder with
        $reminder = factory(App\Droit\Reminder\Entities\Reminder::class)->create([
            'model_id' => $colloque->id,
        ]);

        $this->visit('admin/reminder/'.$reminder->id);

        $data = [
            'id'       => $reminder->id,
            'start'    => 'send_at',
            'type'     => 'colloque',
            'model_id' => $colloque->id,
            'model'    => 'App\Droit\Colloque\Entities\Colloque',
        ];

        $response = $this->call('PUT', '/admin/reminder/'.$reminder->id, $data);

        // Error message because date is not good
        $this->assertRedirectedTo('admin/reminder/'.$reminder->id);
        $this->assertSessionHas('alert.style','danger');

        $data = [
            'id'       => $reminder->id,
            'start'    => 'send_at',
            'type'     => 'colloque',
            'model_id' => $colloque->id +1,
            'model'    => 'App\Droit\Colloque\Entities\Colloque',
        ];

        $response = $this->call('PUT', '/admin/reminder/'.$reminder->id, $data);

        // Error message because model not found
        $this->assertRedirectedTo('admin/reminder/'.$reminder->id);
        $this->assertSessionHas('alert.style','warning');
    }
}
