<?php

namespace Tests\Unit\inscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class RegisterPriceLinkInscriptionTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $generator;
    protected $worker;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->generator = \Mockery::mock('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->app->instance('App\Droit\Generate\Pdf\PdfGeneratorInterface', $this->generator);

        //$this->worker = \Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        //$this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testSimpleRegisterWithRabais()
    {
        $make       = new \tests\factories\ObjectFactory();
        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();
        $person     = $make->makeUser();

        $price1 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price2 = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'description' => 'Price free']);

        $rabais   = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 100, 'title' => 'GLOBAL', 'type' => 'colloque', 'description' => 'test']);
        $options1 = $colloque1->options->pluck('id')->all();
        $options2 = $colloque2->options->pluck('id')->all();

        $person->rabais()->attach($rabais->id);

        $price_link = factory(\App\Droit\PriceLink\Entities\PriceLink::class)->create(['price' => 40000,]);
        $price_link->colloques()->attach([$colloque1->id,$colloque2->id]);

        $data = [
            'rabais_id'      => $rabais->id,
            'user_id'        => $person->id,
            'colloque_id'    => $colloque1->id,
            'type'           => 'simple',
            'colloques' => [
                $colloque1->id => ['options' => $options1],
                $colloque2->id => ['options' => $options2]
            ],
            'price_id' => 'price_link_id:'.$price_link->id,
        ];

        $this->generator->shouldReceive('make')->times(4);

        $response = $this->call('POST', 'admin/inscription', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque1->id,
            'user_id'     => $person->id,
            'price_link_id'  => $price_link->id,
        ]);

    }
}
