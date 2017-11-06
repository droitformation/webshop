<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AboUserAdminTest extends DuskTestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'email'      => 'jane.doe@gmail.com',
            'password'   => bcrypt('jane2')
        ]);

        $user->roles()->attach(1);

        $this->user = $user;
    }


    /**
     * Go see facture for user abo
     * @group adabo
     */
    public function testAddAboUser()
    {
        $this->browse(function (Browser $browser) {

            $make = new \tests\factories\ObjectFactory();
            $abo  = $make->makeAbo();
            $person  = $make->makeUser();

            $browser->loginAs($this->user)->visit('admin/abonnement/create/'.$abo->id);

            $script = '$("#appComponent").append(\'<input name="user_id" value="'.$person->id.'">\')';

            $browser->type('numero','1');
            $browser->type('exemplaires','1');
            $browser->driver->executeScript($script);
            $browser->select('status','abonne');
            $browser->select('renouvellement','auto');
            $browser->click('#createAbo');

            $this->assertDatabaseHas('abo_users', [
                'abo_id'  => $abo->id,
                'user_id' => $person->id,
            ]);

        });
    }

    /**
     * Go see facture for user abo
     * @group abo
     */
    public function testFactureUserEdition()
    {
        $this->browse(function (Browser $browser) {
            $make = new \tests\factories\ObjectFactory();
            $abo  = $make->makeAbo();

            $abonnement = $make->makeUserAbonnement($abo);
            $facture    = $make->abonnementFacture($abonnement);

            $browser->loginAs($this->user)->visit('admin/factures/'.$abo->current_product->id);
            $browser->assertSee('Factures');
            $browser->assertSee($abonnement->numero);
            $browser->assertSee($abonnement->user_adresse->name);
        });
    }

    /**
     * Go see facture for user abo
     * @group abo
     */
    public function testEditFactureForUser()
    {
        $this->browse(function (Browser $browser) {
            $make = new \tests\factories\ObjectFactory();
            $abo  = $make->makeAbo();

            $abonnement = $make->makeUserAbonnement($abo);
            $make->abonnementFacture($abonnement);
            $facture = $abonnement->factures->first();

            $this->assertDatabaseHas('abo_factures', [
                'id'         => $facture->id,
                'payed_at'   => null,
                'created_at' => $facture->created_at,
            ]);

            // Change created_at date and payed_at
            $browser->loginAs($this->user)->visit(url('admin/facture/'.$facture->id));
            $browser->type('payed_at','2016-12-31');
            $browser->type('created_at','2016-12-31');
            $browser->click('#editFacture');

            $this->assertDatabaseHas('abo_factures', [
                'id'         => $facture->id,
                'payed_at'   => '2016-12-31',
                'created_at' => '2016-12-31',
            ]);

            // Only change payed_at, created_at should not change
            $browser->visit(url('admin/facture/'.$facture->id));
            $browser->type('payed_at','2017-12-11');
            $browser->click('#editFacture');

            $this->assertDatabaseHas('abo_factures', [
                'id'         => $facture->id,
                'payed_at'   => '2017-12-11',
                'created_at' => '2016-12-31',
            ]);

            $browser->visit(url('admin/abonnement/'.$facture->abo_user_id));
            $browser->assertSee('Payé le 2017-12-11');
        });
    }

    /**
     * Go see facture for user abo
     * @group abo
     */
    public function testDesinscriptionFromAbo()
    {
        $this->browse(function (Browser $browser) {
            $make = new \tests\factories\ObjectFactory();
            $abo  = $make->makeAbo();

            $abonnement = $make->makeUserAbonnement($abo);
            $make->abonnementFacture($abonnement);
            $facture = $abonnement->factures->first();

            $browser->loginAs($this->user)->visit(url('admin/abonnements/'.$abo->id));
            $browser->pause(3500); // datatable rendering

            // desinscription
            $browser->click('#deleteAbo_'.$abonnement->id);
            $browser->driver->switchTo()->alert()->accept();

            $browser->visit(url('admin/abonnements/'.$abo->id));

            $this->assertDatabaseMissing('abo_users', [
                'id'         => $abonnement->id,
                'deleted_at' => null
            ]);

            $browser->visit(url('admin/abo/desinscription/'.$abonnement->abo_id));

            // restore abo
            $browser->click('#restore_'.$abonnement->id);

            $this->assertDatabaseHas('abo_users', [
                'id'         => $abonnement->id,
                'deleted_at' => null
            ]);
        });

    }

}
