<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportListTest extends DuskTestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @group import
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {

            $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->make(['list_id' => 1]);

            $file       = dirname(__DIR__).'/Feature/excel/test.xlsx';

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('/build/import')
                ->pause(1000)
                ->select('newsletter_id',$newsletter->id)
                ->attach('file', $file)
                ->click('#importList');

            $browser->visit('build/subscriber')
                ->pause(3000)
                ->assertSee('cindy.leschaud@gmail.com')
                ->assertSee('prunturt@yahoo.fr');

        });
    }

    /**
     * @group import
     */
    public function testStoreListeNoNewsletter()
    {
        \DB::table('newsletter_lists')->truncate();

        $this->browse(function (Browser $browser) {

            $file = dirname(__DIR__).'/Feature/excel/test.xlsx';

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit('/build/liste')
                ->pause(1000)
                ->type('title','Un titre 6543')
                ->attach('file', $file)
                ->click('#importList');

            $browser->visit('/build/liste')->pause(3000)->assertSee('Un titre 6543');

            $this->assertDatabaseHas('newsletter_lists', [
                'title' => 'Un titre 6543'
            ]);

        });
    }
}
