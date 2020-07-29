<?php

namespace Tests\Feature\backend\content;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class PageSetTest extends TestCase
{
    use ResetTbl;

    protected $site;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->site = \App::make('App\Droit\Site\Repo\SiteInterface');

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);

        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateChildPage()
    {
        $slugs = $this->site->getAll();
        $site  = $slugs->first();
        $menu  = factory(\App\Droit\Menu\Entities\Menu::class)->create();

        $parent = factory(\App\Droit\Page\Entities\Page::class)->create([
            'site_id'    => $site->id,
            'menu_id'    => $menu->id,
        ]);

        $response = $this->call('POST', 'admin/page', [
            'title'      => 'Lorem ipsum',
            'content'    => 'Amet dolor',
            'template'   => 'page',
            'menu_title' => 'Main',
            'rang'       => 1,
            'parent_id'  => $parent->id,
            'menu_id'    => $menu->id,
            'site_id'    => $site->id,
        ]);

        $this->assertDatabaseHas('pages', [
            'title'      => 'Lorem ipsum',
            'content'    => 'Amet dolor',
            'template'   => 'page',
            'menu_title' => 'Main',
            'slug'       => 'main',
            'rang'       => 1,
            'parent_id'  => $parent->id,
            'menu_id'    => $menu->id,
            'site_id'    => $site->id,
        ]);
    }
}
