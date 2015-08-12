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
	public function testNewNoInscription()
	{
        $this->mock->shouldReceive('all')->once()->andReturn(4);

        $this->interface->setNoInscription(1);

		$this->assertEquals(200, $response->getStatusCode());
	}

}
