<?php

class ExampleTest extends BrowserKitTest {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		$response = $this->call('GET', '/');

		//$this->assertEquals(200, $response->getStatusCode());
	}

}
