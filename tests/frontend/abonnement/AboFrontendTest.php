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

        //make abo ansd user
        $abo  = $make->makeAbo();
        $user = $make->makeUser();

        // login user
        $this->actingAs($user);

        // add abo in cart
        $this->visit('pubdroit');

        $this->press('addAbo_'.$abo->id);

        $this->seePageIs('pubdroit');

        // See abo is on cart page
        $this->visit('pubdroit/checkout/cart');
        $this->see('Demande d\'abonnement');

        // Test if the abo is in the cart
        $inCart = \Cart::instance('abonnement')->search(['id' => (int)$abo->id]);

        $this->assertTrue(!empty($inCart));
    }

    public function testAddAboInCartAlreadyAbonnee()
    {
        $make = new \tests\factories\ObjectFactory();

        //make abo ansd user
        $abo  = $make->makeAbo();
        $user = $make->makeUser();

        // add abo for the user
        $abonnement = $make->makeAbonnement($abo,$user);
        $make->abonnementFacture($abonnement);

        // login user
        $this->actingAs($user);

        // add abo in cart
        $this->visit('pubdroit');
        $this->press('addAbo_'.$abo->id);

        // Test if the abo is not in the cart
        $inCart = \Cart::instance('abonnement')->search(['id' => (int)$abo->id]);

        $this->assertTrue(empty($inCart));
    }

    public function testBuyAbo()
    {
        $make = new \tests\factories\ObjectFactory();

        //make abo ansd user
        $abo  = $make->makeAbo();
        $user = $make->makeUser();

        // login user
        $this->actingAs($user);

        // add abo in cart
        $this->visit('pubdroit');
        $this->press('addAbo_'.$abo->id);
        $this->seePageIs('pubdroit');

        // See abo is on cart page
        $this->visit('pubdroit/checkout/resume');
        $this->see('Demande d\'abonnement');

        $this->check('termsAndConditions');
        $this->click('btn-invoice');

        $this->seeInDatabase('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => $user->adresses->first()->id,
        ]);

    }
}
