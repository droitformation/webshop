<?php

namespace Tests\Feature\frontend;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureFrontendInscriptionTest extends TestCase
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

    public function testUserRegister()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $data = [
            'price_id'       => 'price_id:'.$colloque->prices->first()->id,
            'colloques'      => [
                $colloque->id => ['options' => [$colloque->options->first()->id]]
            ],
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

    public function testUserRegisterMultipleColloquesPricelink()
    {
        $make       = new \tests\factories\ObjectFactory();
        $person     = $make->makeUser();
        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();

        $price      = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'description' => 'Price free']);
        $price_link = factory( \App\Droit\PriceLink\Entities\PriceLink::class)->create();
        $price_link->colloques()->attach([$colloque1->id,$colloque2->id]);

        $data = [
            'price_id'       => 'price_link_id:'.$price_link->id,
            'colloques'      => [
                $colloque1->id => [
                    'options' => [$colloque1->options->first()->id]
                ],
                $colloque2->id => [
                    'options' => [$colloque2->options->first()->id]
                ]
            ],
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824',
            'user_id'        => $person->id,
            'colloque_id'    => $colloque1->id
        ];

        $reponse = $this->post('pubdroit/registration', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'price_link_id' => $price_link->id,
            'user_id'       => $person->id,
            'colloque_id'   => $colloque1->id,
        ]);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'price_id'    => $price->id,
            'user_id'     => $person->id,
            'colloque_id' => $colloque2->id,
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824'
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'id'        => 1,
            'option_id' => $colloque1->options->first()->id,
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'id'        => 2,
            'option_id' => $colloque2->options->first()->id,
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
            'reference_no'   => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824'
        ]);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id' => $inscription->id,
            'reference_id' => $reference->id
        ]);
    }
}
