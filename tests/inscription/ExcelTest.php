<?php

class ExcelTest extends TestCase {

    protected $mock;
    protected $excel;

    public function setUp()
    {
        parent::setUp();

        $this->excel = new App\Droit\Generate\Excel\ExcelGenerator();

        $user = App\Droit\User\Entities\User::find(1);

        $this->actingAs($user);
    }

    public function tearDown()
    {
         \Mockery::close();
    }

    /**
	 *
	 * @return void
	 */
	public function testSetColloqueAndOptions()
	{

        $colloque = new \App\Droit\Colloque\Entities\Colloque();

        $inscriptions = factory(\App\Droit\Inscription\Entities\Inscription::class, 2)->make();
        $options      = factory(\App\Droit\Option\Entities\Option::class, 2)->make();

        $colloque->id = 1;
        $colloque->options      = $options;
        $colloque->inscriptions = $inscriptions;

        $actual = $this->excel->setColloque($colloque);

        $this->assertEquals(1, $actual->colloque->id);
        $this->assertEquals(2, $actual->options->count());
        $this->assertEquals(2, $actual->inscriptions->count());

	}

    public function testPrepareOptions()
    {
        $colloque = new \App\Droit\Colloque\Entities\Colloque();

        $inscriptions = factory(\App\Droit\Inscription\Entities\Inscription::class, 2)->make();
        $options      = factory(\App\Droit\Option\Entities\Option::class,2)->make();

        $options = $options->map(function ($item, $key) {
            $groupe       = factory(\App\Droit\Option\Entities\OptionGroupe::class, 2)->make();
            $item->groupe = $groupe;
            return $item;
        });

        $colloque->id = 1;
        $colloque->options      = $options;
        $colloque->inscriptions = $inscriptions;

        $this->excel->setColloque($colloque);

        $actual = $this->excel->getMainOptions();
        $groupe = $this->excel->getGroupeOptions();

        $expect_option = [ 1 => 'Option', 1 => 'Option' ];
        $expect_groupe = [
            1 => [1 => 'Groupe', 1 => 'Groupe']
        ];

        $this->assertEquals($expect_option, $actual);
        $this->assertEquals($expect_groupe, $groupe);
    }

    public function testRowInfosForInscription()
    {
        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->make();

        $this->excel->setColumns(['name','npa']);

        $actual = $this->excel->row($inscription);

        $i = array_search('participant', array_keys($actual));

        $this->assertEquals('2520', $actual['npa']);
        $this->assertEquals('Cindy Leschaud', $actual['name']);
        $this->assertTrue(isset($actual['participant']));
        $this->assertEquals(2,$i);
    }

}
