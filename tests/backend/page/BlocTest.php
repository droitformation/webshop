<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;

class BlocTest extends BrowserKitTest {

    protected $author;

    use DatabaseTransactions;

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

    /**
     * @return void
     */
    public function testBlocList()
    {
        $this->visit('admin/blocs/1')->see('Blocs de contenus');
        $this->assertViewHas('blocs');
    }

    public function testBlocCreate()
    {
        $menu = factory(App\Droit\Menu\Entities\Menu::class)->create();
        $page = factory(App\Droit\Page\Entities\Page::class)->create([
            'menu_id' => $menu->id
        ]);

        $this->visit('admin/blocs/2')->click('addBloc');
        $this->seePageIs('admin/bloc/create/2');

        $this->select('Lorem ipsum ', 'title')
            ->type('Amet dolor', 'content')
            ->type(1, 'rang')
            ->select('pub', 'type')
            ->select('sidebar', 'position')
            ->select($page->id, 'page_id[]')
            ->press('Envoyer');

        $this->seeInDatabase('blocs', [
            'title'    => 'Lorem ipsum',
            'content'  => 'Amet dolor',
            'type'     => 'pub',
            'rang'     => 1,
            'position' => 'sidebar'
        ]);
    }

    public function testUpdateBloc()
    {
        $bloc = factory(App\Droit\Bloc\Entities\Bloc::class)->create();

        $this->visit('admin/bloc/'.$bloc->id)->see($bloc->title);

        $response = $this->call('PUT', 'admin/bloc/'.$bloc->id,
            [
                'id'       => $bloc->id,
                'title'    => 'Un autre bloc',
                'content'  => '<p>Autre Adresse</p>'
            ]
        );

        $this->seeInDatabase('blocs', [
            'id'       => $bloc->id,
            'title'    => 'Un autre bloc',
            'content'  => '<p>Autre Adresse</p>'
        ]);
    }

    public function testDeleteBloc()
    {
        $bloc = factory(App\Droit\Bloc\Entities\Bloc::class)->create();

        $this->visit('admin/bloc/'.$bloc->id);

        $response = $this->call('DELETE','admin/bloc/'.$bloc->id);

        $this->notSeeInDatabase('blocs', [
            'id' => $bloc->id,
            'deleted_at' => null
        ]);
    }
}
