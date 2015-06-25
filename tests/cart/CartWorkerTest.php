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
    public function testGetFreeShipping()
    {
        \Cart::instance('newInstance');

        \Cart::add(55, 'Uno', 1, '12' , array('weight' => 155));
        \Cart::add(56, 'Duo', 1, '34' , array('weight' => 25));

        $this->worker->getTotalWeight();

        $this->worker->noShipping()->setShipping();

        $this->assertEquals(0, $this->worker->orderShipping->price);
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

        $this->assertEquals(1000, $this->worker->orderShipping->price);
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

        $this->assertEquals(1100, $this->worker->orderShipping->price);
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
        \Cart::add(89, 'cuatro', 1, '44' , array('weight' => 5000));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1400, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateVeryBigger()
    {
        \Cart::instance('newInstance');

        \Cart::add(65, 'Uno', 1, '12' , array('weight' => 5500));
        \Cart::add(76, 'Duo', 1, '34' , array('weight' => 5500));
        \Cart::add(88, 'tres', 1, '44' , array('weight' => 4000));
        \Cart::add(89, 'cuatro', 1, '44' , array('weight' => 5000));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(1900, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testGetShippingRateEvenBigger()
    {
        \Cart::instance('newInstance');

        \Cart::add(65, 'Uno', 1, '12' , array('weight' => 5500));
        \Cart::add(76, 'Duo', 1, '34' , array('weight' => 5500));
        \Cart::add(88, 'tres', 1, '44' , array('weight' => 4000));
        \Cart::add(89, 'cuatro', 1, '43' , array('weight' => 5000));
        \Cart::add(90, 'cinquo', 1, '42' , array('weight' => 10000));

        $this->worker->getTotalWeight();
        $this->worker->setShipping();

        $this->assertEquals(2600, $this->worker->orderShipping->price);
    }

    /**
     * @return void
     */
    public function testApplyCoupon()
    {
        \Cart::instance('newInstance');

        \Cart::add(1, 'Uno', 1, '1000' , array('weight' => 500));

        $product = $this->worker->setCoupon('test')->applyCoupon();

echo '<pre>';
print_r($this->worker->hasCoupon);
echo '</pre>';exit;

        $this->assertEquals(0, $product);
    }

}
