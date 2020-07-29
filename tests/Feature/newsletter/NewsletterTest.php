<?php

namespace Tests\Feature\newsletter;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class NewsletterTest extends TestCase
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

    public function testStats()
    {
        $charts = new \App\Droit\Newsletter\Worker\Charts();

        $stats['DeliveredCount'] = 5;
        $stats['ClickedCount']   = 2;
        $stats['OpenedCount']    = 3;
        $stats['BouncedCount']   = 1;

        $data['total']     = 5;
        $data['clicked']   = 40.0;
        $data['opened']    = 60.0;
        $data['bounced']   = 20.0;
        $data['nonopened'] = 20.0;

        $actual = $charts->compileStats($stats);

        $this->assertEquals($data, $actual);

    }

    public function testArchiveCampagneNewsletter()
    {
        $site         = factory(\App\Droit\Site\Entities\Site::class)->create();
        $newsletter   = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);
        $campagne     = factory(\App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
            'sujet'           => 'Sujet',
            'auteurs'         => 'Cindy Leschaud',
            'status'          => 'brouillon',
            'newsletter_id'   => $newsletter->id,
            'api_campagne_id' => 1,
            'send_at'         => null,
        ]);

        $response = $this->call('POST', 'build/campagne/archive', ['id' => $campagne->id]);

        $response->assertRedirect('build/newsletter');

        $this->assertDatabaseHas('newsletter_campagnes', [
            'id'      => $campagne->id,
            'status'  => 'envoyé',
        ]);

    }
}
