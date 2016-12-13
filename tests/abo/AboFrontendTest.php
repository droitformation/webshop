<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class AboFrontendTest extends TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        \Cart::instance('abonnement')->destroy();
        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testAddAboInCart()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $this->actingAs($abonnement->user->user);

        // Make new product and add the attributes
        $this->visit(url('pubdroit'));
        $this->press('addAbo_'.$abonnement->abo_id);

        $response = $this->response->getContent();
        echo '<pre>';
        print_r($response);
        echo '</pre>';exit();
        $this->assertRedirectedTo('pubdroit');

        //$this->visit(url('pubdroit/checkout/cart'));
       // $this->see('Demande d\'abonnement');
        // Test if the product is in the cart
        //$inCart = \Cart::instance('abonnement')->search(['id' => (int)$abonnement->abo_id]);

       // $this->assertTrue(!empty($inCart));

     /*   $response = $this->call('POST', '/admin/abo', $data);

        $this->seeInDatabase('abos', [
            'title' => 'TestAbo',
            'price' => '5000'
        ]);*/
    }

}
