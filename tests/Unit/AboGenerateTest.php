<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class AboGenerateTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testGetAbo()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getAbo();

        $this->assertEquals($response->numero, $abo_user->numero);
    }

    public function testGetAboAdresse()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getAdresse();

        $this->assertEquals($response->name, $abo_user->user_adresse->name);
    }

    public function testGetAboUserAdresse()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeUserAbonnement($abo);

        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getAdresse();

        $this->assertEquals($response->name, $abo_user->user_adresse->name);
    }

    public function testGetAboTiersAdresse()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeUserAbonnement($abo, null, true);
        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getAdresse();

        $this->assertNotSame($response->name, $abo_user->user_adresse->name);
        $this->assertSame($response->name, $abo_user->user_facturation->name);
        $this->assertSame($response->name, $abo_user->user_facturation->name);
    }

    public function testGetAboFilename()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);

        $response = $generate->getFilename('facture',$abo_facture);

        $file = 'files/abos/facture/'.$abo->current_product->id.'/facture_'.$abo->current_product->reference.'-'.$abo_user->id.'_'.$abo->current_product->id.'.pdf';

        $this->assertEquals($response, public_path($file));
    }

    public function testGetAboRappelFilename()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);
        $abo_rappel  = factory(\App\Droit\Abo\Entities\Abo_rappels::class)->create(['abo_facture_id' => $abo_facture->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getFilename('rappel','rappel_'.$abo_rappel->id);

        $file = 'files/abos/rappel/'.$abo_facture->product->id.'/rappel_'.$abo_rappel->id.'_'.$abo_facture->id.'.pdf';

        $this->assertEquals($response, public_path($file));
    }
}
