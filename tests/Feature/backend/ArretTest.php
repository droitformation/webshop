<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ArretTest extends TestCase
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

    public function testCreateNewArret()
    {
        // Create an analyse
        $author  = factory(\App\Droit\Author\Entities\Author::class)->create();
        $analyse = factory(\App\Droit\Analyse\Entities\Analyse::class)->create();

        //Create a categorie
        $categorie = factory(\App\Droit\Categorie\Entities\Categorie::class)->create();

        $analyse->authors()->attach([$author->id]);

        $data = [
            'site_id'    => 1,
            'reference'  => 'reference 123',
            'pub_date'   => \Carbon\Carbon::now(),
            'abstract'   => 'lorem ipsum dolor amet',
            'pub_text'   => 'amet dolor ipsum lorem',
            'dumois'     => 1,
            'categories' => [$categorie->id],
            'analyses'   => [$analyse->id]
        ];

        $response = $this->call('POST', '/admin/arret', $data);

        $this->assertDatabaseHas('arrets', [
            'reference' => 'reference 123',
            'abstract'  => 'lorem ipsum dolor amet',
            'dumois'    => 1
        ]);

        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/arret/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $arret   = $content['arret'];

        $this->assertEquals(1, $arret->categories->count());
    }

    public function testUpdateArret()
    {
        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create();

        $response = $this->call('PUT', '/admin/arret/'.$arret->id, ['id' => $arret->id, 'dumois' => 1]);

        $this->assertDatabaseHas('arrets', [
            'id'     => $arret->id,
            'dumois' => 1
        ]);
    }

    public function testDeleteArret()
    {
        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create();

        $response = $this->call('DELETE','admin/arret/'.$arret->id, [] ,['id' => $arret->id]);

        $this->assertDatabaseMissing('arrets', [
            'id'         => $arret->id,
            'deleted_at' => null
        ]);
    }
}
