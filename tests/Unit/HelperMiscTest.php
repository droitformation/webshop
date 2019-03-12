<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelperMiscTest extends TestCase
{
    protected $format;
    protected $helper;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $this->format  = new \App\Droit\Helper\Format();
        $this->helper  = new \App\Droit\Helper\Helper();
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

    public function testIsSubstitudeEmail()
    {
        $email = 'wdq932wnre@publications-droit.ch';
        $result = isSubstitute($email);

        $this->assertTrue($result);
    }

    public function testInsertBeforeArray()
    {
        $key    = 'Cindy';
        $value  = 2;
        $data   = [0 => 'first',4 => 'second'];

        $result = $this->helper->insertFirstInArray( $key , $value , $data );

        $this->assertEquals(['Cindy' => 2, 0 => 'first',4 => 'second'],$result);
    }

    public function testFormatNameOther()
    {
        $names = [
            'Coralie von allmen'       => 'Coralie von Allmen',
            'Sandra de mon-moulin'      => 'Sandra de Mon-Moulin',
            'sandra dela les chemin'   => 'Sandra dela les Chemin',
            'cindy leschaud'           => 'Cindy Leschaud',
            'Coralie amhetaj-leschaud' => 'Coralie Amhetaj-Leschaud',
            'françois de voma'         => 'François de Voma'
        ];

        foreach($names as $input => $output)
        {
            $result = $this->format->formatingName($input);
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

    public function testConvertProductsForOrder()
    {
        $data = [
            'products' => [0 => 22, 1 => 12],
            'qty'      => [0 => 2,  1 => 1],
            'rabais'   => [0 => 25],
            'gratuit'  => [1 => 1]
        ];

        $expect = [
            ['product' => 22, 'rabais' => 25,   'qty' => 2],
            ['product' => 12, 'qty' => 1, 'gratuit' => 1],
        ];

        $result = $this->helper->convertProducts($data);

        $this->assertEquals($expect, $result);
    }

    public function testPrepareCategories()
    {
        $data   = [65,19,16,2];

        $expect = [
            65 => ['sorting' =>  0],
            19 => ['sorting' =>  1],
            16 => ['sorting' =>  2],
            2  => ['sorting' =>  3]
        ];

        $result = $this->helper->prepareCategories($data);

        $this->assertEquals($expect, $result);

        $data   = [];
        $expect = [];

        $result = $this->helper->prepareCategories($data);

        $this->assertEquals($expect, $result);
    }

    public function testRangeBeforeCurrentPage()
    {
        $current = [
            21, 45, 76, 33, 88
        ];

        $expect  = [
            [10],
            [10,20,30,40],
            [10,20,30,40,50,60,70],
            [10,20],
            [10,20,30,40,50,60,70,80],
        ];

        for ($i = 0; $i < count($current); $i++) {

            $result = range_before_current_page($current[$i]);

            $this->assertEquals($expect[$i], $result);
        }
    }

    public function testRangeAfterCurrentPage()
    {
        $current = [
            21, 45, 76, 33, 1
        ];

        $last = [
            45, 98, 80, 85, 50
        ];

        $expect  = [
            [30,40],
            [50,60,70,80,90],
            [],
            [40,50,60,70,80],
            [10,20,30,40],
        ];

        for ($i = 0; $i < count($current); $i++) {

            $result = range_after_current_page($current[$i],$last[$i]);

            $this->assertEquals($expect[$i], $result);
        }
    }


    public function testPrepareNotifiy()
    {
        $make = new \tests\factories\ObjectFactory();

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'notify_url' => 'http://publications-droit.ch'
        ]);

        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'notify_url' => 'http://designpond.ch'
        ]);

        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'notify_url' => 'http://designpond.ch'
        ]);

        $orders = $make->order(1);
        $order  = $orders->first();
        $order->products()->sync([]);

        $order->products()->attach($product1->id);
        $order->products()->attach($product2->id);
        $order->products()->attach($product3->id);

        $order = $order->load('products');

        $actual = $this->helper->prepareNotifyEvent($order);

        $expect = collect(['http://publications-droit.ch' => 1, 'http://designpond.ch' => 2]);

        $this->assertEquals($expect,$actual);
    }

/*    public function testFilleMissingKeys()
    {
        $data = [
            '01' => 12,
            '02' => 5,
            '05' => 2,
            '06' => 5,
            '10' => 1
        ];

        $results = fillMissing(1,10, $data);

        $expect = [
            '01' => 12,
            '02' => 5,
            '03' => 0,
            '04' => 0,
            '05' => 2,
            '06' => 5,
            '07' => 0,
            '08' => 0,
            '09' => 0,
            '10' => 1
        ];

        $this->assertEquals($results,$expect);
    }*/

}
