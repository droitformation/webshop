<?php

use Laravel\BrowserKitTesting\DatabaseTransactions;

class AttributReminderTest extends BrowserKitTest {

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

	public function testCreateNewAttribut()
	{
		$this->visit(url('admin/attribut'));
		$this->click('attribut_create');

        $this->seePageIs(url('admin/attribut/create'));

        $this->type('Rappel pour le livre', 'title')
            ->type('Dapibus ante suscipurcusit çunc, primiés?', 'text')
            ->check('reminder')
            ->select('week', 'duration')
            ->press('Envoyer');

        // Calculat the date for 1 week
        $send_at = \Carbon\Carbon::now()->addWeek()->toDateString();

		$this->seeInDatabase('shop_attributes', [
            'title'    => 'Rappel pour le livre',
            'reminder' => true,
            'duration' => 'week',
		]);
	}

	public function testUpdateAttribut()
	{
		$attribute = factory(App\Droit\Shop\Attribute\Entities\Attribute::class)->create([
			'title'    => 'One title',
			'duration' => 'week'
		]);

		$this->visit(url('admin/attribut/'.$attribute->id));

		$this->type('Two title', 'title')->press('Envoyer');

		$this->seeInDatabase('shop_attributes', [
			'title'    => 'Two title',
			'reminder' => false,
		]);
	}

	public function testDeleteReminder()
	{
		$attribute = factory(App\Droit\Shop\Attribute\Entities\Attribute::class)->create([
			'title'    => 'One title',
			'duration' => 'week'
		]);

		$this->visit(url('admin/attribut'))->press('deleteAttribut_'.$attribute->id);
		
		$this->notSeeInDatabase('shop_attributes', [
			'id' => $attribute->id,
			'deleted_at' => null
		]);
	}

}
