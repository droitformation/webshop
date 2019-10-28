<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeaturePagesTest extends TestCase
{
    use ResetTbl;

    protected $site;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->site = \App::make('App\Droit\Site\Repo\SiteInterface');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testPagesSite()
    {
        $page   = new \App\Droit\Page\Entities\Page();
        $pages  = $page->whereNull('isExternal')->get();

        foreach ($pages as $p)
        {
            $prefix   = $p->site->prefix ? $p->site->prefix : '/';
            $page_url = ($p->isExternal ? $p->url : $prefix.'/page/'.$p->slug);
            $page_url = ($p->template != 'page' ? $prefix : $page_url);

            $response = $this->call('get', $page_url);
            $response->assertStatus(200);
        }
    }

    public function testFrontPage()
    {
        $slugs = $this->site->getAll();

        foreach ($slugs as $slug)
        {
            $response = $this->call('get', '/'.$slug->slug);
            $response->assertStatus(200);
        }
    }

    public function testListSearchCurrentColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $visible = $make->colloque(); // colloque visible
        $hidden = $make->colloque(); // colloque hidden

        $visible->titre = 'ids';
        $visible->visible = 1;
        $visible->save();

        $hidden->titre = 'ids';
        $hidden->visible = 0;
        $hidden->save();

        $response = $this->post('pubdroit/search', ['term' => 'ids']);

        $content   = $response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(1, $colloques->count());
    }
}
