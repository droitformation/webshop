<?php

class PagesTest extends BrowserKitTest {

	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
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
