<?php


class SiteTest extends TestCase {

	protected  $site;

	public function setUp()
	{
		parent::setUp();

		$this->site = App::make('App\Droit\Site\Repo\SiteInterface');
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testFrontPage()
	{
		$slugs = $this->site->getAll();

		foreach ($slugs as $slug)
		{
			$this->visit('/'.$slug->slug);
		}
	}
	
}
