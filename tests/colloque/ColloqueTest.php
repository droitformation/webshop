<?php

class ColloqueTest extends TestCase {

    protected $mock;
    protected $interface;

    public function setUp()
    {
        parent::setUp();

        $this->mock      = Mockery::mock('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->interface = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');

        $model = new \App\Droit\User\Entities\User();

        $user = $model->find(710);

        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
    }

	public function testIntersectAnnexes()
	{
        $annexes = ['bon','facture','bv'];
        $result  = (count(array_intersect($annexes, ['bon','facture'])) == count(['bon','facture']) ? true : false);

        $this->assertTrue($result);
	}

    public function testCreateNewColloque()
    {
        $this->visit('/admin/colloque/create')->see('Ajouter un colloque');
    }

    public function testColloqueEditPage()
    {
        $this->visit('/admin/colloque/1')->see('Général');
    }
}
