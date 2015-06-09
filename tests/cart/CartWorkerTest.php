<?php

class CartWorkerTest extends TestCase {

    protected $worker;

    public function setUp()
    {
        parent::setUp();

        $this->worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');
    }

    public function tearDown()
    {

        \Cart::instance('newInstance')->destroy();

    }

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGetShipping()
	{
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

		$result = $this->worker->getShipping();

        $this->assertEquals(180, $result);
	}

}
