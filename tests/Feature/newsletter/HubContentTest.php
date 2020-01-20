<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class HubContentTest extends TestCase
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

    public function testGetArretsForSite()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $arrets     = factory(\App\Droit\Arret\Entities\Arret::class,5)->create(['site_id' => $site->id]);
        $arret_no   = factory(\App\Droit\Arret\Entities\Arret::class)->create(['site_id' => $site->id]);
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['site_id' => $site->id]);
        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id,]);

        $contents = collect([]);

        foreach ($arrets as $arret){
            $content = factory(\App\Droit\Newsletter\Entities\Newsletter_contents::class)->create([
                'arret_id' => $arret->id, 'newsletter_campagne_id' => $campagne->id,
            ]);

            $contents->push($content);
        }

        $response = $this->call('POST', 'hub/arrets', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);
        $this->assertEquals(1,count($response->json('data')));
    }

    public function testGetAnalyseForSite()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $arret1     = factory(\App\Droit\Arret\Entities\Arret::class)->create(['site_id' => $site->id]);
        $arret2     = factory(\App\Droit\Arret\Entities\Arret::class)->create(['site_id' => $site->id]);
        $analyse1   = factory(\App\Droit\Analyse\Entities\Analyse::class)->create(['site_id' => $site->id]);
        $analyse2   = factory(\App\Droit\Analyse\Entities\Analyse::class)->create(['site_id' => $site->id]);
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['site_id' => $site->id]);
        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id]);

        $contents = collect([]);

        $arret1->analyses()->attach($analyse1->id);
        $arret2->analyses()->attach($analyse2->id);

        // arret 1 hidden in newsletter brouillon
        $content1 = factory(\App\Droit\Newsletter\Entities\Newsletter_contents::class)->create(['arret_id' => $arret1->id, 'newsletter_campagne_id' => $campagne->id]);

        $contents->push($content1);

        $response = $this->call('POST', 'hub/analyses', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);
        $this->assertEquals(1,count($response->json('data')));
    }

    public function testCategoriesForSite()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $categories = factory(\App\Droit\Categorie\Entities\Categorie::class,5)->create(['site_id' => $site->id]);

        $response = $this->call('POST', 'hub/categories', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);

        $this->assertEquals(5,count($response->json('data')['categories']));
    }

    public function testYearsForSite()
    {
        $year1 = \Carbon\Carbon::now()->year;
        $year2 = \Carbon\Carbon::now()->addYears(1)->year;

        $site    = factory(\App\Droit\Site\Entities\Site::class)->create();
        $arrets1 = factory(\App\Droit\Arret\Entities\Arret::class,2)->create(['site_id' => $site->id, 'pub_date' => \Carbon\Carbon::now()]);
        $arrets2 = factory(\App\Droit\Arret\Entities\Arret::class,2)->create(['site_id' => $site->id, 'pub_date' => \Carbon\Carbon::now()->addYears(1)]);

        $response = $this->call('POST', 'hub/years', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);

        $this->assertEquals([$year2,$year1], $response->json('data'));
    }

    public function testGetHomepage()
    {
        $site = factory(\App\Droit\Site\Entities\Site::class)->create();
        $page = factory(\App\Droit\Page\Entities\Page::class)->create([
            'title'    => 'INDEX',
            'template' => 'index',
            'site_id'  => $site->id,
        ]);

        $response = $this->call('POST', 'hub/homepage', ['params' => ['site_id' => $site->id]]);
        $response->assertStatus(200);

        $this->assertEquals('INDEX', $response->json('data')['title']);
    }

    public function testGetPage()
    {
        $site  = factory(\App\Droit\Site\Entities\Site::class)->create();
        $page1 = factory(\App\Droit\Page\Entities\Page::class)->create(['site_id'=> $site->id]);
        $page2 = factory(\App\Droit\Page\Entities\Page::class)->create([
            'title'    => 'Second',
            'template' => 'page',
            'site_id'  => $site->id,
        ]);

        $response = $this->call('POST', 'hub/page', ['params' => ['id' => $page2->id]]);

        $response->assertStatus(200);

        $this->assertEquals('Second', $response->json('data')['title']);
    }

    public function testGetMenu()
    {
        $site = factory(\App\Droit\Site\Entities\Site::class)->create();
        $menu = factory(\App\Droit\Menu\Entities\Menu::class)->create(['site_id'=> $site->id]);
        $page = factory(\App\Droit\Page\Entities\Page::class)->create(['site_id'=> $site->id, 'menu_id' => $menu->id]);

        $response = $this->call('POST', 'hub/menu', ['params' => ['site_id'=> $site->id, 'position' => 'main']]);

        $response->assertStatus(200);

        $this->assertEquals($menu->title, $response->json('data')['title']);
    }

    public function testAuthorsForSite()
    {
        $site  = factory(\App\Droit\Site\Entities\Site::class)->create();
        for ($x = 0; $x <= 4; $x++) {
            $author   = factory(\App\Droit\Author\Entities\Author::class)->create();
            $author->sites()->attach($site->id);
        }

        $response = $this->call('POST', 'hub/authors', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);

        $this->assertEquals(5,count($response->json('data')));
    }

    public function testGetCampagneforSite()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['site_id' => $site->id]);

        $campagnes = collect([]);

        for ($x = 0; $x <= 4; $x++) {
            $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
                'newsletter_id' => $newsletter->id,
                'status'        => 'Envoyé',
                'send_at'       => \Carbon\Carbon::now()->subDays($x),
            ]);

            $campagnes->push($campagne);
        }

        $response = $this->call('POST', 'hub/campagne', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);
        $this->assertEquals($campagnes->first()->sujet,$response->json('data')['campagne']['sujet']);
    }

    public function testGetArchivesCampagneforSite()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['site_id' => $site->id]);

        $campagnes = collect([]);

        for ($x = 1; $x <= 4; $x++) {
            $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
                'newsletter_id' => $newsletter->id,
                'status'        => 'Envoyé',
                'send_at'       => \Carbon\Carbon::now()->subDays($x),
            ]);

            $campagnes->push($campagne);
        }

        $response = $this->call('POST', 'hub/archives', ['params' => ['site_id' => $site->id]]);

        $response->assertStatus(200);

        $this->assertEquals($campagnes->count(), count($response->json('data')));
    }

    public function testMajDateAfterSendCampagn()
    {
        $response = $this->call('GET', 'hub/maj');
        $response->assertStatus(200);

        $this->assertEquals('2020-01-19',$response->json('date'));
    }
}
