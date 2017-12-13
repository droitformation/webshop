<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class AbonnementAdminTest extends BrowserKitTest {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        //Login as admin
        $user = factory(App\Droit\User\Entities\User::class)->create();

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
        $user = $make->user();

        $this->visit('admin/abonnements/'.$abo->id);
        $this->assertViewHas('abo');

        $this->click('addAbonne');

        $this->seePageIs('admin/abonnement/create/'.$abo->id);

        $data = [
            'abo_id'         => $abo->id,
            'numero'         => 1,
            'exemplaires'    => 1,
            'user_id'        => $user->id,
            'status'         => 'abonne',
            'renouvellement' => 'auto',
        ];

        $response = $this->call('POST', '/admin/abonnement', $data);

        $this->seeInDatabase('abo_users', [
            'abo_id'  => $abo->id,
            'user_id' => $user->id,
        ]);
    }

    public function testUpdateAboUser()
    {
        $make = new \tests\factories\ObjectFactory();

        $abo        = $make->makeAbo();
        $abonnement = $make->makeAbonnement($abo);
        $user       = $make->makeUser();

        $defaults = [
            'id'             => $abonnement->id,
            'abo_id'         => $abo->id,
            'numero'         => $abonnement->numero,
            'exemplaires'    => $abonnement->exemplaires,
            'status'         => $abonnement->status,
            'renouvellement' => $abonnement->renouvellement,
        ];

        // Only user_id
        $data = array_merge($defaults,['adresse_id' => '', 'user_id' => $user->id]);

        $response = $this->call('PUT', '/admin/abonnement/'.$abonnement->id, $data);

        $this->seeInDatabase('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => null,
            'user_id'    => $user->id,
        ]);

        // Both but we need only user_id
        $data = array_merge($defaults,['adresse_id' => 12, 'user_id' => $user->id]);

        $response = $this->call('PUT', '/admin/abonnement/'.$abonnement->id, $data);

        $this->seeInDatabase('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => null,
            'user_id'    => $user->id,
        ]);

        // Only adresse_id
        $data = array_merge($defaults,['adresse_id' => 12, 'user_id' => null]);

        $response = $this->call('PUT', '/admin/abonnement/'.$abonnement->id, $data);

        $this->seeInDatabase('abo_users', [
            'abo_id'     => $abo->id,
            'adresse_id' => 12,
            'user_id'    => null
        ]);
    }

    public function testFactureUserEdition()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $this->visit('admin/factures/'.$abo->current_product->id);
        $this->assertViewHas('factures');
    }

    public function testRappelsUser()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $this->visit('admin/rappel/'.$abonnement->abo->current_product->id);
        $this->assertViewHas('factures');
    }

    public function testEditFacture()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $facture = $abonnement->factures->first();

        $this->visit('admin/facture/'.$facture->id);
        $this->type('2016-12-31', 'payed_at');
        $this->type('2016-12-31', 'created_at');
        $this->press('editFacture');

        $this->seeInDatabase('abo_factures', [
            'id'       => $facture->id,
            'payed_at' => '2016-12-31',
            'created_at' => '2016-12-31',
        ]);
    }

    public function testEditFactureWithoutCreatedAt()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $facture = $abonnement->factures->first();

        $this->visit('admin/facture/'.$facture->id);
        $this->type('2016-12-31', 'payed_at');
        $this->press('editFacture');

        $this->seeInDatabase('abo_factures', [
            'id'         => $facture->id,
            'payed_at'   => '2016-12-31',
            'created_at' => $facture->created_at->toDateString()
        ]);
    }

    public function testEditFactureDisplayInAbonnement()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $facture = $abonnement->factures->first();

        $this->visit('admin/facture/'.$facture->id);
        $this->type('2016-12-31', 'payed_at');
        $this->press('editFacture');

        $this->seeInDatabase('abo_factures', [
            'id'       => $facture->id,
            'payed_at' => '2016-12-31'
        ]);

        $this->visit('admin/abonnement/'.$facture->abo_user_id);
        $this->see('Payé le 2016-12-31');

    }

    public function testDesinscriptionPage()
    {
        $make = new \tests\factories\ObjectFactory();
        $abo  = $make->makeAbo();

        $this->visit('admin/abo/desinscription/'.$abo->id);
        $this->assertViewHas('abo');
    }

    public function testDesinscriptionAboUser()
    {
        $make = new \tests\factories\ObjectFactory();

        $abonnement = $make->makeAbonnement();
        $make->abonnementFacture($abonnement);

        $this->visit('admin/abonnements/'.$abonnement->abo_id);
        $this->assertViewHas('abo');

        // desinscription
        $this->press('deleteAbo_'.$abonnement->id);

        $this->notSeeInDatabase('abo_users', [
            'id'         => $abonnement->id,
            'deleted_at' => null
        ]);

        $this->visit('admin/abo/desinscription/'.$abonnement->abo_id);
        $this->assertViewHas('abo');

        // restore abo
        $this->press('restore_'.$abonnement->id);

        $this->seeInDatabase('abo_users', [
            'id'         => $abonnement->id,
            'deleted_at' => null
        ]);

    }
}
