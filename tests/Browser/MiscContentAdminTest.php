<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiscContentAdminTest extends DuskTestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);

        $this->user = $user;
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @group seminaire
     */
    public function testCreateSubject()
    {
        $this->browse(function (Browser $browser) {

            $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();

            $browser->loginAs($this->user)->visit('admin/seminaire')->click('#addSeminaire');

            $browser->type('title','Un sujet')->type('year',2014);
            $browser->select('product_id',$product->id);
            $browser->attach('file',public_path('images/avatar.jpg'));
            $browser->press('Créer un seminaire');

            $this->assertDatabaseHas('seminaires', [
                'title' => 'Un sujet',
                'year'  => '2014',
                'product_id' => $product->id,
                'image' => 'avatar.jpg',
            ]);
        });
    }

    /**
     * @group seminaire
     */
    public function testUpdatSubject()
    {
        $this->browse(function (Browser $browser) {

            $seminaire = factory(\App\Droit\Seminaire\Entities\Seminaire::class)->create();

            $subject1 = factory(\App\Droit\Seminaire\Entities\Subject::class)->create();
            $subject2 = factory(\App\Droit\Seminaire\Entities\Subject::class)->create();

            $seminaire->subjects()->attach([$subject1->id, $subject2->id]);

            $browser->loginAs($this->user)->visit('admin/seminaire/'.$seminaire->id);

            $browser->assertSee($subject1->title);
            $browser->assertSee($subject2->title);

            $browser->type('title','Un autre seminaire');
            $browser->click('#updateSeminaire');

            $this->assertDatabaseHas('seminaires', [
                'id'    => $seminaire->id,
                'title' => 'Un autre seminaire'
            ]);
        });
    }

    /**
     * @group bloc
     */
    public function testBlocList()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)->visit('admin/blocs/1')->assertSee('Blocs de contenus');
        });
    }

    /**
     * @group bloc
     */
    public function testBlocCreate()
    {
        $this->browse(function (Browser $browser) {

            $menu = factory(\App\Droit\Menu\Entities\Menu::class)->create();
            $page = factory(\App\Droit\Page\Entities\Page::class)->create(['menu_id' => $menu->id]);

            $browser->loginAs($this->user)->visit('admin/blocs/2')->click('#addBloc');

            $browser->select('title','Lorem ipsum')
                ->type('content','Amet dolor')
                ->type('rang',1)
                ->select('type','pub')
                ->select('position', 'sidebar')
                ->select('page_id[]',$page->id)
                ->press('Envoyer');

            $this->assertDatabaseHas('blocs', [
                'title'    => 'Lorem ipsum',
                'content'  => 'Amet dolor',
                'type'     => 'pub',
                'rang'     => 1,
                'position' => 'sidebar'
            ]);

        });
    }

    /**
     * @group adminpages
     */
    public function 
    testMiscCreationPagesInAdmin()
    {
        $this->browse(function (Browser $browser) {

            $browser->loginAs($this->user)->visit('admin');

            $browser->visit('admin/author/create')->assertSee('Ajouter un auteur');
            $browser->visit('admin/theme/create')->assertSee('Ajouter un thème');
            $browser->visit('admin/shipping/create')->assertSee('Ajouter frais de port');
            $browser->visit('admin/coupon/create')->assertSee('Ajouter coupon');
            $browser->visit('admin/specialisation/create')->assertSee('Ajouter une spécialisation');
            $browser->visit('admin/member/create')->assertSee('Ajouter un type de membre');
            $browser->visit('admin/attribut/create')->assertSee('Ajouter un attribut');
            $browser->visit('admin/colloque/create')->assertSee('Ajouter un colloque');
            $browser->visit('admin/organisateur/create')->assertSee('Ajouter un organisateur');
            $browser->visit('admin/location/create')->assertSee('Ajouter un lieu');

            $browser->visit('admin/page/create/1')->assertSee('Ajouter une page');
            $browser->visit('admin/menu/create/1')->assertSee('Ajouter un menu');
            $browser->visit('admin/arret/create/1')->assertSee('Créer arrêt');
            $browser->visit('admin/analyse/create/1')->assertSee('Créer analyse');
            $browser->visit('admin/bloc/create/1')->assertSee('Ajouter un bloc');

            $browser->visit('admin/page/create/2')->assertSee('Ajouter une page');
            $browser->visit('admin/menu/create/2')->assertSee('Ajouter un menu');
            $browser->visit('admin/arret/create/2')->assertSee('Créer arrêt');
            $browser->visit('admin/analyse/create/2')->assertSee('Créer analyse');
            $browser->visit('admin/bloc/create/2')->assertSee('Ajouter un bloc');

            $browser->visit('admin/page/create/3')->assertSee('Ajouter une page');
            $browser->visit('admin/menu/create/3')->assertSee('Ajouter un menu');
            $browser->visit('admin/arret/create/3')->assertSee('Créer arrêt');
            $browser->visit('admin/analyse/create/3')->assertSee('Créer analyse');
            $browser->visit('admin/bloc/create/3')->assertSee('Ajouter un bloc');

            $browser->visit('admin/export/view')->assertSee('Exporter');
            $browser->visit('admin/author')->assertSee('Auteurs');
            $browser->visit('admin/theme')->assertSee('Thèmes');
            $browser->visit('admin/shipping')->assertSee('Frais de port');
            $browser->visit('admin/coupon')->assertSee('Coupons rabais');
            $browser->visit('admin/specialisation')->assertSee('Spécialisations');
            $browser->visit('admin/member')->assertSee('Membres');
            $browser->visit('admin/attribut')->assertSee('Attributs des produits');
            $browser->visit('admin/colloque')->assertSee('Colloques');
            $browser->visit('admin/organisateur')->assertSee('Organisateurs');
            $browser->visit('admin/location')->assertSee('Lieux');
            
        });
    }
}
