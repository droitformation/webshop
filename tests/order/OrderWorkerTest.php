<?php

class OrderWorkerTest extends TestCase {

    protected $worker;
    protected $mock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Shop\Order\Repo\OrderInterface');

        $this->worker  = new \App\Droit\Shop\Order\Worker\OrderWorker(
            $this->mock,
            \App::make('App\Droit\Shop\Cart\Worker\CartWorker'),
            \App::make('App\Droit\User\Repo\UserInterface')
        );

    }

    public function tearDown()
    {
        Mockery::close();
        \Cart::instance('newInstance')->destroy();
    }

	/**
	 * @return void
	 */
	public function testNewOrderNumber()
	{
        $last = new App\Droit\Shop\Order\Entities\Order();
        $last->order_no = '2015-00000003' ;

        $this->mock->shouldReceive('maxOrder')->once()->andReturn($last);
        $response = $this->worker->newOrderNumber();

        $this->assertEquals('2015-00000004', $response);
	}

    /**
     * @return void
     */
    public function testGetProductIdFromCart()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));
        \Cart::add(57, 'tres', 1, '35' , array('weight' => 125));

        $result = $this->worker->productIdFromCart();

        $this->assertEquals([55,55,56,57], $result);
    }

}
