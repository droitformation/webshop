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

        $this->seeInDatabase('menus', [
            'title'       => 'Un menu',
            'position'    => 'main',
            'site_id'     => 1
        ]);
    }
    
    public function testUpdateMenu()
    {
        $menu = factory(App\Droit\Menu\Entities\Menu::class)->create();

        $this->visit('admin/menu/'.$menu->id)->see($menu->title);

        $this->type('Un menu', 'title')->select('sidebar', 'position')->press('Envoyer');

        $this->seeInDatabase('menus', [
            'title'       => 'Un menu',
            'position'    => 'sidebar',
            'site_id'     => $menu->site_id
        ]);
    }
  
    public function testDeleteMenu()
    {
        $menu = factory(App\Droit\Menu\Entities\Menu::class)->create();

        $this->visit('admin/menu/'.$menu->id)->see($menu->title);

        $response = $this->call('DELETE','admin/menu/'.$menu->id);

        $this->notSeeInDatabase('menus', [
            'id' => $menu->id,
        ]);
    }
}
