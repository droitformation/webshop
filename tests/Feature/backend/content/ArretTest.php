<?php

namespace Tests\Feature\backend\content;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;
use Tests\HubDate;

class ArretTest extends TestCase
{
    use RefreshDatabase,ResetTbl,HubDate;

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
        $this->setDate('hub');

        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create();

        $response = $this->call('PUT', '/admin/arret/'.$arret->id, ['id' => $arret->id, 'dumois' => 1]);

        $this->isDate('hub');

        $this->assertDatabaseHas('arrets', [
            'id'     => $arret->id,
            'dumois' => 1
        ]);
    }

    public function testDeleteArret()
    {
        $this->setDate('hub');

        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create();

        $response = $this->call('DELETE','admin/arret/'.$arret->id, [] ,['id' => $arret->id]);

        $this->isDate('hub');

        $this->assertDatabaseMissing('arrets', [
            'id'         => $arret->id,
            'deleted_at' => null
        ]);
    }
}
