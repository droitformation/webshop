<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureFrontendInscriptionTest extends TestCase
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

    public function testUserRegister()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $data = [
            'price_id'       => $colloque->prices->first()->id,
            'options'        => [$colloque->options->first()->id],
            //'occurrences'    => [6],
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
            'user_id'        => $person->id,
            'colloque_id'    => $colloque->id
        ];

        $reponse = $this->post('pubdroit/registration', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'price_id'    => $colloque->prices->first()->id,
            'user_id'     => $person->id,
            'colloque_id' => $colloque->id,
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no' => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824'
        ]);
    }

    public function testUserRegisterWithReferences()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        session()->put('reference_no', 'Ref_2019_designpond');
        session()->put('transaction_no', '2109_10_19824');

        $reference = \App\Droit\Transaction\Reference::make($inscription);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no' => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824'
        ]);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id' => $inscription->id,
            'reference_id' => $reference->id
        ]);
    }
}
