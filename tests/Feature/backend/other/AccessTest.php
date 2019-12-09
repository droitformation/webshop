<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class AccessTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();
    }

    public function testAccessWithSpecialisation()
    {
        $specialisation = factory(\App\Droit\Specialisation\Entities\Specialisation::class)->create();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $second = factory(\App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(3);
        $this->actingAs($user);

        $adresse1 = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1, 'user_id' => $user->id]);
        $adresse2 = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['type' => 1, 'user_id' => $second->id]);

        $adresse1->specialisations()->attach($specialisation->id);
        $adresse2->specialisations()->attach($specialisation->id);

        $user->access()->attach($specialisation->id);

        $reponse = $this->get('access');

        $content = $reponse->getOriginalContent();
        $content = $content->getData();

        $this->assertEquals(1, $content['specialisations']->count());
        $this->assertEquals(2, $content['adresses']->count());
    }

    public function testAccessForbidden()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $response = $this->get('access');

        $response->assertRedirect('login');
    }

    public function testTeamForbidden()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $response = $this->get('team');

        $response->assertRedirect('/login');
    }
}
