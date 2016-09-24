<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderControllerTest extends TestCase {

    use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
	}

	public function tearDown()
	{
		Mockery::close();
		DB::rollBack();
        parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testFilterCommandes()
	{
		$make   = new \tests\factories\ObjectFactory();
		
		$pending = $make->order(5);
		$send    = $make->order(3);

		//$make->updateOrder($pending, ['column' => 'payed_at', 'date' => '2016-09-10']);
		$make->updateOrder($send, ['column' => 'send_at', 'date' => '2016-09-10']);

        $this->visit(url('admin/orders'))->see('Commandes');

		$response = $this->call('POST', url('admin/orders'), ['start' => '2016-09-01', 'end' => '2016-09-30', 'send' => 'send']);

		$content = $response->getOriginalContent();
		$content = $content->getData();

		$result = $content['orders'];

		$this->assertEquals($result->count(), 3);

        $response = $this->call('POST', url('admin/orders'), ['start' => '2016-09-01', 'end' => '2016-09-30', 'send' => 'pending']);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $result = $content['orders'];

        $this->assertEquals($result->count(), 5);
	}
}
