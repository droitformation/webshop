<?php



class SiteTest extends TestCase {

	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testFrontPage()
	{
		$this->visit('/')->see('BIENVENUE sur publications-droit.ch');
	}
	
	public function testColloqueListPage()
	{
		$this->visit('pubdroit/colloque')->see('Colloques');
	}
	
}
