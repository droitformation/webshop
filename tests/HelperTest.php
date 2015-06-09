<?php

class HelperTest extends TestCase {

    protected $format;

    public function setUp()
    {
        parent::setUp();

        $this->format  = new \App\Droit\Helper\Format();
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

        $this->assertEquals($expect, $result);
	}

}
