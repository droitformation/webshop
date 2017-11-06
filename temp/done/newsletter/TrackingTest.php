<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrackingTest extends BrowserKitTest
{
    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
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

        $tracking = App\Droit\Newsletter\Entities\Newsletter_tracking::all();

        $this->assertEquals(2,$tracking->count());
    }

}
