<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class AbonnementAdminTest extends TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        //Login as admin
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }
    
    public function testAboMakeNewUsers()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo     = $make->makeAbo();
        $adresse = $make->user();

        $this->visit(url('admin/abonnements/'.$abo->id));
        $this->assertViewHas('abo');

        $this->click('addAbonne');

        $this->seePageIs(url('admin/abonnement/create/'.$abo->id));

        $data = [
            'abo_id'         => $abo->id,
            'numero'         => 1,
            'exemplaires'    => 1,
            'adresse_id'     => $adresse->adresses->first()->id,
            'status'         => 'abonne',
            'renouvellement' => 'auto',
        ];

        $response = $this->call('POST', '/admin/abonnement', $data);

        $this->seeInDatabase('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => $adresse->adresses->first()->id,
        ]);
    }

    public function testFactureUserEdition()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo     = $make->makeAbo();

        $this->visit(url('admin/factures/'.$abo->current_product->id));
        $this->assertViewHas('factures');
    }

    public function testRappelsUserEdition()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $this->visit(url('admin/rappel/'.$abonnement->abo->current_product->id));
        $this->assertViewHas('factures');

        $this->press('makeRappel_'.$abonnement->factures->first()->id);

        $this->seePageIs(url('admin/rappel/'.$abonnement->abo->current_product->id))
            ->see('Le rappel a été crée');

        $facture = $abonnement->factures->first();
        $rappels = $facture->rappels;

        $this->assertEquals(1, $rappels->count());
    }

    public function testEditFacture()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $facture = $abonnement->factures->first();

        $this->visit(url('admin/facture/'.$facture->id));
        $this->type('2016-12-31', 'payed_at');
        $this->press('editFacture');

        $this->seeInDatabase('abo_factures', [
            'id'       => $facture->id,
            'payed_at' => '2016-12-31',
        ]);
    }

    public function testDesinscriptionPage()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo     = $make->makeAbo();

        $this->visit(url('admin/abo/desinscription/'.$abo->id));
        $this->assertViewHas('abo');
    }
    
}
