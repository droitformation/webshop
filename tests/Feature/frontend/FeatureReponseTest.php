<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class FeatureReponseTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

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

    public function testCreateSondageMarketing()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $response = $this->withSession(['colloques' => collect([$colloque])]);

        $response = $response->get('admin/sondage/create');
        $response->assertStatus(200);

        $response = $this->post('admin/sondage', [
            'title' => 'Ceci est un titre',
            'description' => 'Ceci est une description',
            'marketing'   => 1,
            'valid_at'    => \Carbon\Carbon::today()->addDay(5)->toDateString()
        ]);

        $response->isRedirect(url('admin/sondage'));

        $response = $this->get('admin/sondage');
        $response->assertSee('Ceci est une description');

        // See if the reponse is in the database
        $this->assertDatabaseHas('sondages', [
            'title'       => 'Ceci est un titre',
            'description' => 'Ceci est une description',
            'marketing'   => 1,
            'valid_at'    => \Carbon\Carbon::today()->addDay(5)->toDateString()
        ]);
    }
}
