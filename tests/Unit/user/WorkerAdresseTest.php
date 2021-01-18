<?php

namespace Tests\Unit\user;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class WorkerAdresseTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testAdressesWorkerSet()
    {
        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');
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

        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        // The user has no orders
        $this->assertTrue($user->orders->isEmpty());

        $worker->setAction('delete')->setTypes(['orders'])->setFromAdresses([$adresse1->id, $adresse2->id])->reassignFor($user);

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

    public function testReassignOrdersToUserFromUser()
    {
        $make      = new \tests\factories\ObjectFactory();

        $recipient = $make->makeUser();
        $donor     = $make->makeUser();
        $orders    = $make->order(2, $donor->id);

        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        // The user has no orders
        $this->assertTrue($recipient->orders->isEmpty());

        $worker->setAction('delete')->setTypes(['orders'])->setFromAdresses([$donor->adresses->pluck('id')->all()])->reassignFor($recipient);

        $recipient->load('orders');

        $orders->map(function ($order, $key)  use ($recipient){
            $this->assertTrue($recipient->orders->contains($order->id));
        });

        // The adresses are trashed
        $donor->adresses->map(function ($adresse, $key) {
            $this->assertTrue($adresse->fresh()->trashed());
        });

        // The user is trashed
        $this->assertTrue($donor->fresh()->trashed());
    }

    public function testReassignAbosFromAdresseToUser()
    {
        $make    = new \tests\factories\ObjectFactory();
        $worker  = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');
        $user    = $make->makeUser();
        $adresse = $make->adresse();

        $abonnement = $make->makeAbonnementForAdresse($adresse);

        $adresse->load('abos');

        // The adresse has one abo
        $this->assertTrue($user->abos->isEmpty());
        $this->assertTrue($adresse->abos->contains('id',$abonnement->id));

        $worker->setTypes(['abos'])->setFromAdresses([$adresse->id])->reassignFor($user);

        $user->load('abos');

        $this->assertTrue($user->abos->contains('id',$abonnement->id));
    }

    /*
     * With mocks repo
     * */

    public function testReassignOrdersToUserFromAdressesDeleteMocks()
    {
        $make     = new \tests\factories\ObjectFactory();

        $adresse1 = $make->adresse();
        $adresse2 = $make->adresse();

        $user = $make->makeUser();

        $order1 = $make->makeAdresseOrder($adresse1->id);
        $order2 = $make->makeAdresseOrder($adresse2->id);

        // Mocks
        $mockadresse = \Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
        $mockuser    = \Mockery::mock('App\Droit\User\Repo\UserInterface');

        $worker = new \App\Droit\Adresse\Worker\AdresseWorker($mockadresse,$mockuser);

        $mockadresse->shouldReceive('getMultiple')->once()->andReturn(collect([$adresse1, $adresse2]));
        $mockadresse->shouldReceive('delete')->twice();
        $mockadresse->shouldReceive('setSpecialisation');
        $mockadresse->shouldReceive('setMember');

        $worker->setAction('delete')->setTypes(['orders'])->setFromAdresses([$adresse1->id, $adresse2->id])->reassignFor($user);
        $this->assertTrue(true);
    }

    public function testReassignOrdersToUserFromAdressesAttachMocks()
    {
        $make     = new \tests\factories\ObjectFactory();

        $adresse1 = $make->adresse();
        $adresse2 = $make->adresse();

        $user = $make->makeUser();

        $order1 = $make->makeAdresseOrder($adresse1->id);
        $order2 = $make->makeAdresseOrder($adresse2->id);

        // Mocks
        $mockadresse = \Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
        $mockuser    = \Mockery::mock('App\Droit\User\Repo\UserInterface');

        $worker = new \App\Droit\Adresse\Worker\AdresseWorker($mockadresse,$mockuser);

        $mockadresse->shouldReceive('getMultiple')->once()->andReturn(collect([$adresse1, $adresse2]));
        $mockadresse->shouldReceive('update')->twice();
        $mockadresse->shouldReceive('setSpecialisation');
        $mockadresse->shouldReceive('setMember');

        $worker->setAction('attach')->setTypes(['orders'])->setFromAdresses([$adresse1->id, $adresse2->id])->reassignFor($user);

        $this->assertTrue(true);
    }

    public function testReassignSpecialisationsAndMembers()
    {
        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');
        $make   = new \tests\factories\ObjectFactory();

        $specialisations = $make->items('Specialisation', 2);
        $spec_data       = $specialisations->pluck('id')->all();
        $members         = $make->items('Member', 2);
        $mem_data        = $members->pluck('id')->all();

        $recipient = $make->makeUser();
        $donor     = $make->makeUser();

        // Add specialisations
        $make->addMemberships($donor,['specialisations' => $spec_data]);
        $make->addMemberships($donor,['members' => $mem_data]);

        $donor->fresh();

        // The recipient has no specialisation
        $this->assertTrue(!$donor->adresse_contact->specialisations->isEmpty());
        $this->assertTrue($recipient->adresse_contact->specialisations->isEmpty());
        $this->assertTrue(!$donor->adresse_contact->members->isEmpty());
        $this->assertTrue($recipient->adresse_contact->members->isEmpty());

        $worker->setAction('attachdelete')->reasignMembership($donor,$recipient);

        $recipient->fresh();
        $recipient->load('adresses.specialisations','adresses.members');
        $donor->load('adresses.specialisations','adresses.members');

        $this->assertTrue($donor->adresse_contact->specialisations->isEmpty());
        $this->assertTrue(!$recipient->adresse_contact->specialisations->isEmpty());
        $this->assertEquals($spec_data,$recipient->adresse_contact->specialisations->pluck('id')->all());

        $this->assertTrue($donor->adresse_contact->members->isEmpty());
        $this->assertTrue(!$recipient->adresse_contact->members->isEmpty());
        $this->assertEquals($mem_data,$recipient->adresse_contact->members->pluck('id')->all());
    }

    public function testReassignOrdersToUserFromAdressesAttachDeleteMocks()
    {
        $make     = new \tests\factories\ObjectFactory();

        $recipient = $make->makeUser();
        $donor     = $make->makeUser();

        $orders   = $make->order(2, $donor->id);
        $adresses = $donor->adresses;

        // Mocks
        $mockadresse = \Mockery::mock('App\Droit\Adresse\Repo\AdresseInterface');
        $mockuser    = \Mockery::mock('App\Droit\User\Repo\UserInterface');

        $worker = new \App\Droit\Adresse\Worker\AdresseWorker($mockadresse,$mockuser);

        $mockadresse->shouldReceive('getMultiple')->once()->andReturn($donor->adresses);
        $mockadresse->shouldReceive('update')->once();
        $mockuser->shouldReceive('delete')->once();
        $mockadresse->shouldReceive('setSpecialisation');
        $mockadresse->shouldReceive('setMember');

        $worker->setAction('attachdelete')->setTypes(['orders'])->setFromAdresses([$adresses->pluck('id')->all()])->reassignFor($recipient);

        $this->assertTrue(true);
    }

    /*
     * Helpers collection
     * */
    public function testPartitionTypeAdresseOrUser()
    {
        $worker  = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');
        $make    = new \tests\factories\ObjectFactory();

        $user     = $make->makeUser();
        $adresse1 = $make->adresse();
        $adresse2 = $make->adresse();

        $result = $worker->getList(collect([$user->adresse_contact,$adresse1,$adresse2]));

        $this->assertEquals($result->pluck('id'),collect([$user->adresse_contact,$adresse1,$adresse2])->pluck('id'));

        $result = $worker->getList(collect([$user->adresse_contact,$adresse1,$adresse2]), 'user');

        $this->assertEquals($result->pluck('id'),collect([$user])->pluck('id'));
    }

    public function testPrepareTerms()
    {
        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        $terms = [
            'terms'   => ['Unine', 'droitformation.web@gmail.com'],
            'columns' => ['company','email'],
        ];

        $expect = [
            ['column' => 'company', 'value'  => 'Unine'],
            ['column' => 'email', 'value' => 'droitformation.web@gmail.com']
        ];

        $result = $worker->prepareTerms($terms, 'adresse');

        $this->assertEquals($expect,$result);

        $this->assertTrue($worker->authorized('email','user'));
        $this->assertTrue($worker->authorized('first_name','user'));

        $this->assertTrue($worker->authorized('first_name','adresse'));

        $this->assertFalse($worker->authorized('company','user'));
    }
}
