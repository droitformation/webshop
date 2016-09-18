<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesTest extends TestCase {

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
	public function testPagesSite()
	{
		$page   = new \App\Droit\Page\Entities\Page();
		$pages  = $page->whereNull('isExternal')->get();

		foreach ($pages as $p)
        {
            $prefix   = $p->site->prefix ? $p->site->prefix : '/';
            $page_url = ($p->isExternal ? $p->url : $prefix.'/page/'.$p->slug);
            $page_url = ($p->template != 'page' ? $prefix : $page_url);

            $this->visit(url($page_url));
		}
	}
}
