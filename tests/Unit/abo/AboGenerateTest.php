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
        $this->app['config']->set('database.default','testing');
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
        $abo_user    = $make->makeTiersAbonnement($abo, null);
        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getAdresse();

        $this->assertNotSame($response->name, $abo_user->user_adresse->name);
        $this->assertSame($response->name, $abo_user->user_facturation->name);
    }

    public function testIsTiersAdresse()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeTiersAbonnement($abo, null);

        $this->assertTrue($abo_user->is_tiers);
    }

    public function testGetAboAdresseName()
    {
        $make  = new \tests\factories\ObjectFactory();

        $user    = $make->makeUser();
        $adresse = $make->adresse($user);

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeTiersAbonnement($abo, $user);

        $abo_facture = factory(\App\Droit\Abo\Entities\Abo_factures::class)->create(['abo_user_id' => $abo_user->id ,'product_id' => $abo->current_product->id]);

        $generate = new \App\Droit\Generate\Entities\AboGenerate($abo_facture);
        $response = $generate->getDetenteurAdresse();

        $this->assertSame($response->main_name, $adresse->main_name);
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

    public function testMakeOrderWithReferences()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        session()->put('reference_no', 'Ref_2019_DesignPond');
        session()->put('transaction_no', '2109_10_1982');

        $reference = \App\Droit\Transaction\Reference::make($abo_user);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no' => 'Ref_2019_DesignPond',
            'transaction_no' => '2109_10_1982'
        ]);

        $this->assertDatabaseHas('abo_users', [
            'id' => $abo_user->id,
            'reference_id' => $reference->id
        ]);
    }

    public function testUpdateAboWithReferences()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        $reference = \App\Droit\Transaction\Entities\Transaction_reference::create([
            'reference_no'   => 'Ref_2019_DesignPond',
            'transaction_no' => '2109_10_1982',
        ]);

        $abo_user->reference_id = $reference->id;
        $abo_user->save();

        $this->assertDatabaseHas('abo_users', [
            'id' => $abo_user->id,
            'reference_id' => $reference->id
        ]);

        $data = [
            'reference_no'   => 'NEW_Ref_2019_DesignPond',
            'transaction_no' => 'NEW_2109_10_1982',
        ];

        $reference = \App\Droit\Transaction\Reference::update($abo_user,$data);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'NEW_Ref_2019_DesignPond',
            'transaction_no' => 'NEW_2109_10_1982'
        ]);
    }

    public function testUpdateNonExistantReferencesForAbo()
    {
        $make  = new \tests\factories\ObjectFactory();

        $abo         = $make->makeAbo();
        $abo_user    = $make->makeAbonnement($abo);

        $this->assertDatabaseHas('abo_users', [
            'id' => $abo_user->id,
            'reference_id' => null
        ]);

        $data = [
            'reference_no'   => 'NEW_Ref_2019_DesignPond',
            'transaction_no' => 'NEW_2109_10_1982',
        ];

        $reference = \App\Droit\Transaction\Reference::update($abo_user,$data);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'NEW_Ref_2019_DesignPond',
            'transaction_no' => 'NEW_2109_10_1982'
        ]);

        $this->assertDatabaseHas('abo_users', [
            'id' => $abo_user->id,
            'reference_id' => $reference->id
        ]);
    }
}
