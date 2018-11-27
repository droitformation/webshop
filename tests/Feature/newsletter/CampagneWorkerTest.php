<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class CampagneWorkerTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
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

    public function testExcludedArrets()
    {
        $site       = factory(\App\Droit\Site\Entities\Site::class)->create();
        $arrets     = factory(\App\Droit\Arret\Entities\Arret::class,5)->create(['site_id' => $site->id]);
        $arret_no   = factory(\App\Droit\Arret\Entities\Arret::class)->create(['site_id' => $site->id]);
        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['site_id' => $site->id]);
        $campagne   = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id,]);

        $contents = collect([]);

        foreach ($arrets as $arret){
            $content = factory(\App\Droit\Newsletter\Entities\Newsletter_contents::class)->create([
                'arret_id'  => $arret->id, 'newsletter_campagne_id' => $campagne->id,
            ]);

            $contents->push($content);
        }

        $worker = \App::make('App\Droit\Newsletter\Worker\CampagneWorker');
        $ids = $worker->excludeArrets([$newsletter->id]);

        $this->assertEquals(5,count($ids));
    }
}
