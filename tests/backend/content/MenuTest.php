<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenuTest extends TestCase {

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
	public function testMenuList()
	{
        $this->visit('admin/menus/1')->see('Menus');
        $this->assertViewHas('menus');
	}
    
   public function testMenuCreate()
    {
        $this->visit('admin/menus/1')->click('addMenu');
        $this->seePageIs('admin/menu/create/1');

        $this->type('Un menu', 'title')->select('main', 'position')->press('Envoyer');

        $this->seeInDatabase('shop_products', [
            'title'       => 'Un menu',
            'position'    => 'main',
            'sit_id'      => 1
        ]);
    }

    /*
        public function testUpdateMenu()
        {
            $this->visit('admin/theme')->see('Thèmes');
            $this->assertViewHas('themes');
        }

        public function testDeleteMenu()
        {
            $this->visit('admin/shipping')->see('Frais de port');
            $this->assertViewHas('shippings');
        }*/
    
}
