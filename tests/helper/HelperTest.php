<?php

class HelperTest extends TestCase {

    protected $format;
    protected $helper;

    public function setUp()
    {
        parent::setUp();

        $this->format  = new \App\Droit\Helper\Format();
        $this->helper  = new \App\Droit\Helper\Helper();
    }

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testConvertAjaxData()
	{
        $data = [
            'data' => [
                [ 'name' => '_method', 'value' => 'PUT' ],
                [ 'name' => 'company', 'value' => 'Unine' ],
                [ 'name' => 'civilite', 'value' => '2' ]
            ]
        ];

        $expect = [
             '_method'  => 'PUT',
             'company'  => 'Unine',
             'civilite' => '2'
        ];

		$result = $this->format->convertSerializedData($data);

        //$this->assertEquals($expect, $result);
	}

    public function testIsMulti()
    {
        $expect = [1 => [1,2,3]];
        $result = $this->helper->is_multi($expect);

        $this->assertTrue($result);
    }

    public function testIsNotMulti()
    {
        $expect = [1];
        $result = $this->helper->is_multi($expect);

        $this->assertFalse($result);
    }

    public function testAddInterval()
    {
        $expect = [1];
        $result = $this->helper->addInterval($date,$interval);

        $this->assertFalse($result);
    }
}
