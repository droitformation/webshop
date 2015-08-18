<?php

class ColloqueTest extends TestCase {

    protected $mock;
    protected $interface;

    public function setUp()
    {
        parent::setUp();

        $this->mock      = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->interface = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    }

    public function tearDown()
    {
        Mockery::close();
    }

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testIntersectAnnexes()
	{

        $annexes = ['bon','facture','bv'];
        $result  = (count(array_intersect($annexes, ['bon','facture'])) == count(['bon','facture']) ? true : false);

        $this->assertTrue($result);

	}

}
