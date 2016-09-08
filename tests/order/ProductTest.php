<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase {

    use DatabaseTransactions;

	public function setUp()
	{
		parent::setUp();

        DB::beginTransaction();
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
	public function testProfilCommandesUser()
	{
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $this->actingAs($user);

		$this->visit('/admin/products');
		$this->assertViewHas('products');
	}
}
