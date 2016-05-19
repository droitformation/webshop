<?php

class ProductTest extends \TestCase {

    protected $mock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Shop\Product\Repo\ProductInterface');
        $this->app->instance('App\Droit\Shop\Product\Repo\ProductInterface', $this->mock);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
