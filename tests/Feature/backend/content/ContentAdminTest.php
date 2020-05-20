<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ContentAdminTest extends TestCase
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

    public function testCreateBloc()
    {
        $page = factory(\App\Droit\Page\Entities\Page::class)->create();

        $response = $this->call('POST', 'admin/bloc', [
            'title'    => 'Un bloc',
            'type'     => 'pub',
            'position' => 'sidebar',
            'page_id'  => $page->id,
            'content'  => '<p>Une Adresse</p>'
        ]);

        $this->assertDatabaseHas('blocs', [

            'title'    => 'Un bloc',
            'type'     => 'pub',
            'position' => 'sidebar',
            'content'  => '<p>Une Adresse</p>'
        ]);
    }

    public function testUpdateBloc()
    {
        $bloc = factory(\App\Droit\Bloc\Entities\Bloc::class)->create();

        $response = $this->call('PUT', 'admin/bloc/'.$bloc->id, [
            'id'       => $bloc->id,
            'title'    => 'Un autre bloc',
            'content'  => '<p>Autre Adresse</p>'
        ]);

        $this->assertDatabaseHas('blocs', [
            'id'       => $bloc->id,
            'title'    => 'Un autre bloc',
            'content'  => '<p>Autre Adresse</p>'
        ]);
    }

    public function testDeleteBloc()
    {
        $bloc = factory(\App\Droit\Bloc\Entities\Bloc::class)->create();

        $response = $this->call('DELETE','admin/bloc/'.$bloc->id);

        $this->assertDatabaseMissing('blocs', [
            'id' => $bloc->id,
            'deleted_at' => null
        ]);
    }

    public function testPageCreate()
    {
        $menu = factory(\App\Droit\Menu\Entities\Menu::class)->create();

        $response = $this->call('POST', 'admin/page', [
            'title'      => 'Lorem ipsum',
            'content'    => 'Amet dolor',
            'template'   => 'page',
            'menu_title' => 'Main',
            'menu_id'    => $menu->id,
            'rang'       => 1,
            'site_id'    => 2,
        ]);

        $this->assertDatabaseHas('pages', [
            'title'      => 'Lorem ipsum',
            'content'    => 'Amet dolor',
            'template'   => 'page',
            'menu_title' => 'Main',
            'slug'       => 'main',
            'rang'       => 1,
            'menu_id'    => $menu->id
        ]);
    }

    public function testUpdatePage()
    {
        $page = factory(\App\Droit\Page\Entities\Page::class)->create();

        $response = $this->call('PUT', 'admin/page/'.$page->id, [
            'id'      => $page->id,
            'title'   => 'Un autre page',
            'content' => '<p>Autre contenu</p>',
            'hidden'  => 1
        ]);

        $this->assertDatabaseHas('pages', [
            'id'       => $page->id,
            'title'    => 'Un autre page',
            'hidden'   => 1,
            'content'  => '<p>Autre contenu</p>'
        ]);

        $response = $this->call('PUT', 'admin/page/'.$page->id, [
            'id'      => $page->id,
            'hidden'  => 0
        ]);

        $this->assertDatabaseHas('pages', [
            'id'       => $page->id,
            'title'    => 'Un autre page',
            'hidden'   => null,
            'content'  => '<p>Autre contenu</p>'
        ]);
    }

    public function testDeletePage()
    {
        $page = factory(\App\Droit\Page\Entities\Page::class)->create();

        $response = $this->call('DELETE','admin/page/'.$page->id);

        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
            'deleted_at' => null
        ]);
    }

    public function testAddBlocContent()
    {
        $page = factory(\App\Droit\Page\Entities\Page::class)->create();

        $data = [
            'title'    => 'Un titre bloc',
            'content'  => '<p>Un bloc de contenu</p>',
            'page_id'  => $page->id,
            'type'     => 'text'
        ];

        $this->call('POST', 'admin/pagecontent', $data);

        $this->assertDatabaseHas('contents', [
            'title'    => 'Un titre bloc',
            'content'  => '<p>Un bloc de contenu</p>',
            'page_id'  => $page->id,
            'type'     => 'text',
        ]);

    }

    public function testMenuCreate()
    {
        $data = [
            'title'    => 'Un menu',
            'position' => 'main',
            'site_id'  => 1
        ];

        $this->call('POST', 'admin/menu', $data);

        $this->assertDatabaseHas('menus', [
            'title'       => 'Un menu',
            'position'    => 'main',
            'site_id'     => 1
        ]);
    }

    public function testUpdateMenu()
    {
        $menu = factory(\App\Droit\Menu\Entities\Menu::class)->create();

        $data = [
            'id'       => $menu->id,
            'title'    => 'Un menu',
            'position' => 'sidebar',
        ];

        $this->call('PUT', 'admin/menu/'.$menu->id, $data);

        $this->assertDatabaseHas('menus', [
            'id'          => $menu->id,
            'title'       => 'Un menu',
            'position'    => 'sidebar',
            'site_id'     => $menu->site_id
        ]);
    }

    public function testDeleteMenu()
    {
        $menu = factory(\App\Droit\Menu\Entities\Menu::class)->create();

        $response = $this->call('DELETE','admin/menu/'.$menu->id);

        $this->assertDatabaseMissing('menus', [
            'id' => $menu->id,
        ]);
    }

    public function testAuthorCreate()
    {
        $data = [
            'first_name' => 'Cindy',
            'last_name'  => 'Leschaud',
            'occupation' => 'Webmaster',
            'bio'        => 'Une bio'
        ];

        $this->call('POST', 'admin/author', $data);

        $this->assertDatabaseHas('authors', [
            'first_name' => 'Cindy',
            'last_name'  => 'Leschaud',
            'occupation' => 'Webmaster',
            'bio'        => 'Une bio'
        ]);
    }

    public function testAuthorUpdate()
    {
        $author = factory(\App\Droit\Author\Entities\Author::class)->create();

        $nbr = rand(1,5);

        $data = [
            'id'         => $author->id,
            'occupation' => 'Informaticienne', //original factory Webmaster
            'bio'        => 'Une autre '.$nbr.' bio'
        ];

        $this->call('PUT', 'admin/author/'.$author->id, $data);

        $this->assertDatabaseHas('authors', [
            'first_name' => $author->first_name,
            'last_name'  => $author->last_name,
            'occupation' => 'Informaticienne',
            'bio'        => 'Une autre '.$nbr.' bio'
        ]);
    }

    public function testAuthorDelete()
    {
        $author = factory(\App\Droit\Author\Entities\Author::class)->create();

        $response = $this->call('DELETE','admin/author/'.$author->id);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function testCompteCreate()
    {
        $response = $this->call('POST', 'admin/compte', [
            'motif'    => 'Un compte',
            'adresse'  => 'Adresse',
            'centre'   => '1234',
            'compte'   => '234-131-2',
        ]);

        $this->assertDatabaseHas('comptes', [
            'motif'    => 'Un compte',
            'adresse'  => 'Adresse',
            'centre'   => '1234',
            'compte'   => '234-131-2'
        ]);
    }

    public function testUpdateCompte()
    {
        $compte = factory(\App\Droit\Compte\Entities\Compte::class)->create();

        $response = $this->call('PUT', 'admin/compte/'.$compte->id, [
            'id'       => $compte->id,
            'motif'    => 'Un autre compte',
            'adresse'  => '<p>Autre Adresse</p>',
            'centre'   => '1234',
            'compte'   => '20-4130-2',
        ]);

        $this->assertDatabaseHas('comptes', [
            'id'       => $compte->id,
            'motif'    => 'Un autre compte',
            'centre'   => '1234',
            'adresse'  => '<p>Autre Adresse</p>',
            'compte'   => '20-4130-2',
        ]);
    }

    public function testDeleteCompte()
    {
        $compte = factory(\App\Droit\Compte\Entities\Compte::class)->create();

        $response = $this->call('DELETE','admin/compte/'.$compte->id);

        $this->assertDatabaseMissing('comptes', [
            'id' => $compte->id,
        ]);
    }

    public function testLocationCreate()
    {
        $response = $this->call('POST', 'admin/location', [
            'name'    => 'Un lieux',
            'adresse' => '<p>Une adresse</p>',
        ]);

        $this->assertDatabaseHas('locations', [
            'name'    => 'Un lieux',
            'adresse' => '<p>Une adresse</p>',
        ]);
    }

    public function testUpdateLocation()
    {
        $location = factory(\App\Droit\Location\Entities\Location::class)->create();

        $response = $this->call('PUT', 'admin/location/'.$location->id, [
            'id'      => $location->id,
            'name'    => 'Un autre lieux',
            'adresse' => $location->adresse,
        ]);

        $this->assertDatabaseHas('locations', [
            'name'    => 'Un autre lieux',
            'adresse' => $location->adresse,
        ]);
    }

    public function testDeleteLocation()
    {
        $location = factory(\App\Droit\Location\Entities\Location::class)->create();

        $response = $this->call('DELETE','admin/location/'.$location->id);

        $this->assertDatabaseMissing('locations', [
            'id' => $location->id,
        ]);
    }

    public function testDomainCreate()
    {
        $response = $this->call('POST', 'admin/domain', ['title' => 'Cindy']);

        $this->assertDatabaseHas('domains', ['title' => 'Cindy']);
    }

    public function testDomainUpdate()
    {
        $domain = factory(\App\Droit\Domain\Entities\Domain::class)->create();

        $response = $this->call('PUT', 'admin/domain/'.$domain->id, [
            'id'    => $domain->id,
            'title' => 'Webmaster'
        ]);

        $this->assertDatabaseHas('domains', [
            'id'    => $domain->id,
            'title' => 'Webmaster'
        ]);
    }

    public function testDomainDelete()
    {
        $domain = factory(\App\Droit\Domain\Entities\Domain::class)->create(['title' => 'New']);

        $response = $this->call('DELETE','admin/domain/'.$domain->id);

        $this->assertDatabaseMissing('domains', ['id' => $domain->id, 'deleted_at' => null]);
    }

    public function testCalculetteCreate()
    {
        $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

        $response = $this->call('POST', 'admin/calculette/ipc', [
            'indice'    => 4.5,
            'start_at'  => $date
        ]);

        $this->assertDatabaseHas('calculette_ipc', [
            'indice'    => 4.5,
            'start_at'  => $date
        ]);
    }

    public function testUpdateCalculetteIpc()
    {
        $ipc = factory(\App\Droit\Calculette\Entities\Calculette_ipc::class)->create(); // canton => be, ipc => 3
        $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

        $response = $this->call('PUT', 'admin/calculette/ipc/'.$ipc->id, [
            'id'        => $ipc->id,
            'indice'    => 4.5,
            'start_at'  => $date
        ]);

        $this->assertDatabaseHas('calculette_ipc', [
            'indice'    => 4.5,
            'start_at'  => $date
        ]);
    }

    public function testDeleteCalculetteIpc()
    {
        $ipc = factory(\App\Droit\Calculette\Entities\Calculette_ipc::class)->create(); //ipc => 3

        $response = $this->call('DELETE','admin/calculette/ipc/'.$ipc->id);

        $this->assertDatabaseMissing('calculette_ipc', ['id' => $ipc->id]);
    }

    public function testCalculetteTauxCreate()
    {
        $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

        $canton = array_rand(config('calculette.cantons'));

        $response = $this->call('POST', 'admin/calculette/taux', [
            'taux'      => 4.5,
            'start_at'  => $date,
            'canton'    => $canton
        ]);

        $this->assertDatabaseHas('calculette_taux', [
            'taux'      => 4.5,
            'start_at'  => $date,
            'canton'    => $canton
        ]);
    }

    public function testUpdateCalculetteTaux()
    {
        $taux = factory(\App\Droit\Calculette\Entities\Calculette_taux::class)->create(); // canton => be, taux => 3
        $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

        $response = $this->call('PUT', 'admin/calculette/taux/'.$taux->id, [
            'id'        => $taux->id,
            'taux'      => 4.5,
            'start_at'  => $date,
            'canton'    => 'vd'
        ]);

        $this->assertDatabaseHas('calculette_taux', [
            'id'        => $taux->id,
            'taux'      => 4.5,
            'start_at'  => $date,
            'canton'    => 'vd'
        ]);
    }

    public function testDeleteCalculetteTaux()
    {
        $taux = factory(\App\Droit\Calculette\Entities\Calculette_taux::class)->create(); // canton => be, taux => 3

        $response = $this->call('DELETE','admin/calculette/taux/'.$taux->id);

        $this->assertDatabaseMissing('calculette_taux', [
            'id' => $taux->id,
        ]);
    }
}
