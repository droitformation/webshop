<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterTest extends TestCase
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

    public function testTrackingPing()
    {
        $events = [
            [
                "event"          => "sent",
                "time"           => 1502957607,
                "MessageID"      => 19421777835146490,
                "email"          => "api@mailjet.com",
                "mj_campaign_id" => 7257,
                "mj_contact_id"  => 4,
                "customcampaign" => "",
                "mj_message_id"  => "19421777835146490",
                "smtp_reply"     => "sent (250 2.0.0 OK 1433333948 fa5si855896wjc.199 - gsmtp)",
                "CustomID"       => 23,
                "Payload"        => ""
            ],
            [
                "event"          => "sent",
                "time"           => 1502957607,
                "MessageID"      => 20421355835146490,
                "email"          => "api@mailjet.com",
                "mj_campaign_id" => 757,
                "mj_contact_id"  => 5,
                "customcampaign" => "",
                "mj_message_id"  => "1942277835146490",
                "smtp_reply"     => "sent 250",
                "CustomID"       => 12,
                "Payload"        => ""
            ]
        ];

        $response = $this->call('POST', url('tracking'), $events);

        $tracking = \App\Droit\Newsletter\Entities\Newsletter_tracking::all();

        $this->assertEquals(2,$tracking->count());
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
            'send_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

    }
}
