<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisplayTest extends TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

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

    /**
     *
     * @return void
     */
    public function testTypeOfInscription()
    {
        // Create colloque
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions;

        $normal = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->user_id;
        })->first();

        $group = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $display = new \App\Droit\Inscription\Entities\Display($normal);
        $display->getType();

        $this->assertEquals('inscription', $display->type);

        $display2 = new \App\Droit\Inscription\Entities\Display($group);
        $display2->getType();

        $this->assertEquals('group', $display2->type);
    }

    public function testInscritNormal()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $adresse = $inscription->user->adresses->first();

        $display = new \App\Droit\Inscription\Entities\Display($inscription);
        $display->isValid();

        $this->assertEquals($adresse->name, $display->inscrit->name);
    }

    public function testInscritGroup()
    {
        // Create colloque
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions;

        $group = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $adresse = $group->groupe->user->adresses->first();

        $display = new \App\Droit\Inscription\Entities\Display($group);
        $display->isValid();

        $this->assertEquals($adresse->name, $display->inscrit->name);
    }

    public function testIsCorrectModel()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $display = new \App\Droit\Inscription\Entities\Display($inscription);
        $display->isValid();

        $this->assertEquals($inscription, $display->getModel());
    }

    public function testIsCorrectModelGroupe()
    {
        // Create colloque
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $group = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();
        
        $display = new \App\Droit\Inscription\Entities\Display($group);
        $display->isValid();

        $this->assertEquals('group', $display->type);
        $this->assertEquals($group->groupe, $display->getModel());
    }

    public function testCorrectDetenteurNormal()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $user     = $make->makeUser(['first_name' => 'Cindy', 'last_name' => 'Leschaud', 'email' => 'cindy.leschaud@gmail.com', 'civilite_id' => 2, 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertEquals('Cindy Leschaud', $user->adresses->first()->name);
        $this->assertEquals('Madame', $user->adresses->first()->civilite_title);

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create(['user_id' => $user->id, 'colloque_id' => $colloque->id]);

        $display = new \App\Droit\Inscription\Entities\Display($inscription);
        $display->isValid();

        $this->assertTrue($display->valid);
        $this->assertEquals(['id' => $user->id, 'civilite' => 'Madame', 'name' => 'Cindy Leschaud', 'email' => 'cindy.leschaud@gmail.com'], $display->detenteur);
    }

    public function testCorrectDetenteurGroup()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $user     = $make->makeUser(['first_name' => 'Cindy', 'last_name' => 'Leschaud', 'civilite_id' => 2, 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertEquals('Cindy Leschaud', $user->adresses->first()->name);
        $this->assertEquals('Madame', $user->adresses->first()->civilite_title);

        $group = factory(\App\Droit\Inscription\Entities\Groupe::class)->create([
            'user_id'     => $user->id,
            'colloque_id' => $colloque->id
        ]);

        $inscriptions = factory(\App\Droit\Inscription\Entities\Inscription::class,2)->create(['user_id' => null, 'group_id' => $group->id, 'colloque_id' => $colloque->id]);

        $display = new \App\Droit\Inscription\Entities\Display($inscriptions->first());
        $display->isValid();

        $this->assertEquals($user->adresses->first(), $display->adresse);
        $this->assertTrue($display->valid);
        $this->assertEquals(['id' => $user->id, 'civilite' => 'Madame', 'name' => 'Cindy Leschaud', 'email' => 'cindy.leschaud@gmail.com'], $display->detenteur);
    }

    public function testNotValid()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $user     = factory(App\Droit\User\Entities\User::class,'admin')->create();
        
        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create(['user_id' => $user->id, 'group_id' => null, 'colloque_id' => $colloque->id]);

        $display = new \App\Droit\Inscription\Entities\Display($inscription);

        $this->assertFalse($display->isValid());
        $this->assertEquals(['Pas de\'adresse pour l\'inscrit'], $display->errors);
    }

    public function testGetParticipants()
    {
        // Create colloque
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscription = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $particiants = $inscription->groupe->inscriptions->map(function ($item, $key) {
            return $item->participant;
        });

        $display = new \App\Droit\Inscription\Entities\Display($inscription);
        $display->isValid();

        $this->assertEquals('group', $display->type);
        $this->assertEquals($particiants, $display->getParticiants());

    }
}
