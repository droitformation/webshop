<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testCreateNewAnalyse()
    {
        //Create a categorie
        $categorie = factory(\App\Droit\Categorie\Entities\Categorie::class)->create();

        // Create an arret
        $arret   = factory(\App\Droit\Arret\Entities\Arret::class)->create();
        $arret->categories()->attach([$categorie->id]);

        // Create an analyse
        $author  = factory(\App\Droit\Author\Entities\Author::class)->create();

        $data = [
            'site_id'    => 1,
            'title'      => 'Un titre',
            'pub_date'   => \Carbon\Carbon::now(),
            'abstract'   => 'lorem ipsum dolor amet',
            'arrets'     => [$arret->id],
            'author_id'  => [$author->id]
        ];

        $response = $this->call('POST', '/admin/analyse', $data);

        $this->assertDatabaseHas('analyses', [
            'title'     => 'Un titre',
            'abstract'  => 'lorem ipsum dolor amet',
            'site_id'    => 1,
        ]);

        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/analyse/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $analyse = $content['analyse'];

        $this->assertEquals(1, $analyse->arrets->count());
        $this->assertEquals(1, $analyse->authors->count());
    }

    public function testUpdateAnalyse()
    {
        $analyse = factory(\App\Droit\Analyse\Entities\Analyse::class)->create();

        $author1  = factory(\App\Droit\Author\Entities\Author::class)->create();
        $author2  = factory(\App\Droit\Author\Entities\Author::class)->create();

        $analyse->authors()->attach([$author1->id]);

        $this->assertEquals(1, $analyse->authors->count());

        $response = $this->call('PUT', '/admin/analyse/'.$analyse->id, ['id' => $analyse->id, 'site_id' => 2, 'author_id' => [$author1->id, $author2->id]]);

        $this->assertDatabaseHas('analyses', [
            'id'      => $analyse->id,
            'site_id' => 2
        ]);

        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/analyse/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $analyse = $content['analyse'];

        $this->assertEquals(2, $analyse->authors->count());

    }

    public function testDeleteAnalyse()
    {
        $analyse = factory(\App\Droit\Analyse\Entities\Analyse::class)->create();

        $response = $this->call('DELETE','admin/analyse/'.$analyse->id);

        $this->assertDatabaseMissing('analyses', [
            'id' => $analyse->id,
            'deleted_at' => null
        ]);
    }
}
