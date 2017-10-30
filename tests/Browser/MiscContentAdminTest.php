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
}
