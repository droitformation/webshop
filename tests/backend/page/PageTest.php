<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;

class PageTest extends BrowserKitTest {

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
    public function testPageList()
    {
        $this->visit('admin/pages/1')->see('Pages');
        $this->assertViewHas('pages');
    }

    public function testPageCreate()
    {
        $menu = factory(App\Droit\Menu\Entities\Menu::class)->create();

        $this->visit('admin/pages/2')->click('addPage');
        $this->seePageIs('admin/page/create/2');

        $this->type('Lorem ipsum ', 'title')
            ->type('Amet dolor', 'content')
            ->select('page', 'template')
            ->type('Main', 'menu_title')
            ->type($menu->id, 'menu_id')
            ->type(1, 'rang')
            ->select(2, 'site_id')
            ->press('Envoyer');

        $this->seeInDatabase('pages', [
            'title'      => 'Lorem ipsum',
            'content'    => 'Amet dolor',
            'template'   => 'page',
            'menu_title' => 'Main',
            'slug'       => 'main',
            'rang'       => 1,
            'menu_id'    => $menu->id
        ]);
    }

    public function testUpdatePage()
    {
        $page = factory(App\Droit\Page\Entities\Page::class)->create();

        $this->visit('admin/page/'.$page->id)->see($page->title);

        $this->type('Un autre page ', 'title')
            ->type('<p>Autre contenu</p>', 'content')
            ->select(1, 'hidden')
            ->press('Envoyer');

        $this->seeInDatabase('pages', [
            'id'       => $page->id,
            'title'    => 'Un autre page',
            'hidden'   => 1,
            'content'  => '<p>Autre contenu</p>'
        ]);

        $this->visit('admin/page/'.$page->id)->see('Un autre page');

        $this->select(0, 'hidden')->press('Envoyer');

        $this->seeInDatabase('pages', [
            'id'       => $page->id,
            'title'    => 'Un autre page',
            'hidden'   => null,
            'content'  => '<p>Autre contenu</p>'
        ]);
    }

    public function testDeletePage()
    {
        $page = factory(App\Droit\Page\Entities\Page::class)->create();

        $this->visit('admin/page/'.$page->id);

        $response = $this->call('DELETE','admin/page/'.$page->id);

        $this->notSeeInDatabase('pages', [
            'id' => $page->id,
            'deleted_at' => null
        ]);
    }

    public function testAddBlocContent()
    {
        $page = factory(App\Droit\Page\Entities\Page::class)->create();

        $this->visit('admin/page/'.$page->id);

        $data['data'] = [
            'title'    => 'Un titre bloc',
            'content'  => '<p>Un bloc de contenu</p>',
            'page_id'  => $page->id,
            'type'     => 'text'
        ];

        $this->call('POST', 'admin/pagecontent', $data);

        $this->seeInDatabase('contents', [
            'title'    => 'Un titre bloc',
            'content'  => '<p>Un bloc de contenu</p>',
            'page_id'  => $page->id,
            'type'     => 'text',
        ]);

        $this->visit('admin/page/'.$page->id)->see('Un titre bloc');

    }
}
