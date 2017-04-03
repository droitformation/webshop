<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdresseWorkerTest extends BrowserKitTest {

    use DatabaseTransactions;

    protected $adresse;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testAdressesWorkerSet()
    {
        $worker = App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');
        $worker->setTypes(['abos']);
        
        $this->assertEquals(['abos'],$worker->types);

        $worker->setFromAdresses([3,4,5]);

        $this->assertEquals([3,4,5],$worker->fromadresses);
    }

    public function testReassignOrdersToUserFromAdresses()
    {
        $make     = new \tests\factories\ObjectFactory();

        $adresse1 = $make->adresse();
        $adresse2 = $make->adresse();

        $user = $make->makeUser();

        $order1 = $make->makeAdresseOrder($adresse1->id);
        $order2 = $make->makeAdresseOrder($adresse2->id);

        $worker = App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        // The user has no orders
        $this->assertTrue($user->orders->isEmpty());

        $worker->setTypes(['orders'])->setFromAdresses([$adresse1->id, $adresse2->id])->reassignFor($user);

        $user->load('orders');

        // Orders are now belonging to the user
        $this->assertTrue($user->orders->contains($order1->id));
        $this->assertTrue($user->orders->contains($order2->id));

        // Orders are not belonging to the adresse anymore
        $this->assertFalse($adresse1->fresh()->orders->contains('id',$order1->id));
        $this->assertFalse($adresse2->fresh()->orders->contains('id',$order2->id));

        // The adresse are trashed
        $this->assertTrue($adresse1->fresh()->trashed());
        $this->assertTrue($adresse2->fresh()->trashed());

    }

    public function testReassignAbosToUserContactAdress()
    {
        $make     = new \tests\factories\ObjectFactory();
        $worker   = App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        $adresse1 = $make->adresse();

        $abonnement = $make->makeAbonnementForAdresse($adresse1);

        $user = $make->makeUser();

        // The user has one adresse
        $this->assertTrue(isset($user->adresse_contact));
        $this->assertTrue($user->adresse_contact->abos->isEmpty());
        $this->assertTrue($adresse1->abos->contains('id',$abonnement->id));

        $worker->setTypes(['abos'])->setFromAdresses([$adresse1->id])->reassignFor($user);

        $user->load('adresses.abos');

        $this->assertTrue($user->adresse_contact->abos->contains('id',$abonnement->id));
    }
}
