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
	 * @return void
	 */
	public function testGetShipping()
	{
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

		$result = $this->worker->getTotalWeight();

        $this->assertEquals(180, $result->orderWeight);
	}

    /**
     * @return void
     */
    public function testGetShippingRate()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1000, $this->worker->orderShipping->first()->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateBigger()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 500));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 800));
        \Cart::add(58, 'Duo', 1, '44' , array('weight' => 800));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1100, $this->worker->orderShipping->first()->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateVeryBig()
    {
        \Cart::instance('newInstance');

        \Cart::add(65, 'Uno', 1, '12' , array('weight' => 1500));
        \Cart::add(76, 'Duo', 1, '34' , array('weight' => 1500));
        \Cart::add(88, 'tres', 1, '44' , array('weight' => 2000));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

       $result =  $this->worker->getShipping();

echo '<pre>';
print_r($result);
echo '</pre>';exit;

        $this->assertEquals(1400, $this->worker->orderShipping->first()->price);
    }

}
