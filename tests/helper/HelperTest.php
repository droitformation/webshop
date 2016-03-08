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

    public function testRemoveNonAlphaNumeric()
    {
        $string = 'Cindy#12';
        $result = $this->helper->_removeNonAlphanumericLetters($string);

        $this->assertEquals('cindy_12',$result);
    }

    public function testInsertBeforeArray()
    {
        $key    = 'Cindy';
        $value  = 2;
        $data   = [0 => 'first',4 => 'second'];

        $result = $this->helper->insertFirstInArray( $key , $value , $data );

        $this->assertEquals(['Cindy' => 2, 0 => 'first',4 => 'second'],$result);
    }

    public function testFormatName()
    {
        $names = [
            'Coralie von allmen'     => 'Coralie von Allmen',
            'Sandra de monmoulin'    => 'Sandra de Monmoulin',
            'sandra dela les chemin' => 'Sandra dela les Chemin',
            'cindy leschaud'         => 'Cindy Leschaud'
        ];

        foreach($names as $input => $output)
        {
            $result = $this->helper->format_name($input);

            $this->assertEquals($output, $result);
        }
    }

    public function testExplodeContent()
    {
        $text = 'Quis consectetur aenean dictumst proîn ïd prétium namé mattisé llä'."\n".'Est ornare convallis dïam £at platéa per tellus class cubliâ hac'."\n".'';

        $output = [
            'Quis consectetur aenean dictumst proîn ïd prétium namé mattisé llä',
            'Est ornare convallis dïam £at platéa per tellus class cubliâ hac'
        ];

        $result = $this->helper->contentExplode($text);

        $this->assertEquals($output, $result);
    }

}
