<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeaturePagesTest extends TestCase
{
    use ResetTbl;

    protected $site;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->site = \App::make('App\Droit\Site\Repo\SiteInterface');
    }

    public function tearDown()
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
}
