<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ConferenceTest extends TestCase
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

    public function testCreateConference()
    {
        $data       = ['title' => 'Test 123', 'date' => date('Y-m-d'),'places' => 25, 'comment' => 'test'];
        $conference = ['conference' => $data];
        $reponse    = $this->post('admin/dejeuner', $conference);

        $this->assertDatabaseHas('system_registries', ['key' => 'conference', 'value' => json_encode($data)]);
    }

    public function testUpdateConference()
    {
        $data1       = ['title' => 'Test 123', 'date' => date('Y-m-d'),'places' => 25, 'comment' => 'test'];
        $conference = ['conference' => $data1];
        $reponse    = $this->post('admin/dejeuner', $conference);

        $this->assertDatabaseHas('system_registries', ['key' => 'conference', 'value' => json_encode($data1)]);

        $data2       = ['title' => 'Test 456', 'date' => date('Y-m-d'),'places' => 15, 'comment' => 'test'];
        $conference = ['conference' => $data2];
        $reponse    = $this->post('admin/dejeuner', $conference);

        $this->assertDatabaseHas('system_registries', ['key' => 'conference', 'value' => json_encode($data2)]);
    }

    public function testConferenceAddInscription()
    {
        $faker = \Faker\Factory::create();

        $data    = ['first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'email' => $faker->email];
        $reponse = $this->post('dejeuner', $data);

        $academiques = \Registry::get('academiques');

        $this->assertContains($data, $academiques);
    }

    public function testConferenceRemoveInscription()
    {
        $academiques = \Registry::get('academiques');
        $lastKey     = key(array_slice($academiques, -1, 1, true));
        $last        = array_pop($academiques);

        $data    = ['index' => $lastKey];
        $reponse = $this->delete('dejeuner', $data);

        $academiques = \Registry::get('academiques');

        $this->assertNotContains($last, $academiques);
    }
}
