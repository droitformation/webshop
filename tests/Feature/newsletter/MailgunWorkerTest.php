<?php

namespace Tests\Feature\newsletter;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class MailgunWorkerTest extends TestCase
{
    use ResetTbl;

    protected $mailgun;
    protected $campagne;
    protected $newsletter;
    protected $html;
    protected $recipients;
    protected $response_ok;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->mailgun = \Mockery::mock('\Mailgun\Mailgun');
        $this->app->instance('\Mailgun\Mailgun', $this->mailgun);

        $newsletter =  new \stdClass();
        $newsletter->from_email = 'info@droitne.ch';
        $newsletter->from_name = 'DroitNe';

        $campagne = new \stdClass();
        $campagne->id         = 1982;
        $campagne->titre      = 'My Campagne';
        $campagne->sujet      = 'This is the draft';
        $campagne->newsletter = $newsletter;

        $this->newsletter = $newsletter;
        $this->campagne = $campagne;

        $this->html =  '<html><head><title>Test</title></head><body><h3>Hello!</h3><p><a href="https://google.ch">Link</a></p></body></html>';

        $this->recipients = ['cindy.leschaud@gmail.com','info@leschaud.ch'];

        $response = new \stdClass();
        $http_response_body = new \stdClass();

        $response->http_response_code = 200;
        $http_response_body->id       = 123;
        $response->http_response_body = $http_response_body;

        $this->response_ok = $response;

    }

    public function tearDown(): void
    {
        \Mockery::close();
    }
    
    /**
     *
     * @return void
     */
    public function testSetSender()
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

        $worker->setSender('cindy.leschaud@gmail.com','Cindy Leschaud');

        $this->assertEquals('Cindy Leschaud <cindy.leschaud@gmail.com>', $worker->getSender());
    }

    /**
     *
     * @return void
     */
    public function testSetAndPrepareRecipients()
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

        $worker->setRecipients($this->recipients);

        $this->assertEquals($this->recipients, $worker->getRecipients());

        $prepared = [
            'cindy.leschaud@gmail.com' => ['id' => 1],
            'info@leschaud.ch' => ['id' => 2],
        ];

        $this->assertEquals($prepared, $worker->prepareRecipients());
    }

    public function testPrepareEmailSimpleMessage()
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

        $prepared = [
            'cindy.leschaud@gmail.com' => ['id' => 1],
            'info@leschaud.ch' => ['id' => 2],
        ];

        $sujet = 'The subject';

        $expected = [
            'from'                => 'Cindy Leschaud <cindy.leschaud@gmail.com>',
            "subject"             => $sujet,
            'to'                  => $this->recipients,
            "html"                => $this->html,
            "text"                => strip_tags($this->html,'<a>'),
            "recipient-variables" => json_encode($prepared), // Required for batch sending, matches to recipient details
            'o:tag'               => ['transactionnal']
        ];

        // Assert
        $worker->setSender('cindy.leschaud@gmail.com','Cindy Leschaud')
            ->setRecipients($this->recipients)
            ->setHtml($this->html);

        $results = $worker->prepareEmail($sujet);

        $this->assertEquals($expected, $results);
    }

    public function testPrepareEmailCampagne()
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

        $toSend = \Carbon\Carbon::now()->addMinutes(2)->toRfc2822String();

        $prepared = [
            'cindy.leschaud@gmail.com' => ['id' => 1],
            'info@leschaud.ch' => ['id' => 2],
        ];

        $sujet = $this->campagne->sujet;

        $expected = [
            'from'                => $this->newsletter->from_name.' <'.$this->newsletter->from_email.'>',
            "subject"             => $sujet,
            'to'                  => $this->recipients,
            "html"                => $this->html,
            "text"                => strip_tags($this->html,'<a>'),
            "recipient-variables" => json_encode($prepared), // Required for batch sending, matches to recipient details
            "v:messageId"         => 'message_'. $this->campagne->id , // Custom variable used for webhooks
            'o:deliverytime'      => $toSend,
            'o:tag'               => ['message_'. $this->campagne->id]
        ];

        // Assert

        $worker->setSender($this->newsletter->from_email,$this->newsletter->from_name)
            ->setRecipients($this->recipients)
            ->setSendDate($toSend)
            ->setHtml($this->html);

        $this->assertEquals($expected, $worker->prepareEmail($sujet, $this->campagne->id));
    }

    public function testSendSimpleEmail()
    {
        $mailgun = \Mockery::mock('\Mailgun\Mailgun');
        $worker = new \App\Droit\Newsletter\Worker\MailgunService($mailgun);

        $worker->setSender($this->newsletter->from_email,$this->newsletter->from_name)
            ->setRecipients($this->recipients)
            ->setHtml($this->html);

        $mailgun->shouldReceive('sendMessage')->once()->andReturn($this->response_ok);

        $result = $worker->sendTransactional('A message');

        $this->assertEquals(123, $result);
    }

    public function testSendCampagne()
    {
        $mailgun = \Mockery::mock('\Mailgun\Mailgun');
        $worker = new \App\Droit\Newsletter\Worker\MailgunService($mailgun);

        $toSend = \Carbon\Carbon::now()->addMinutes(2)->toRfc2822String();

        $worker->setSender($this->newsletter->from_email,$this->newsletter->from_name)
            ->setRecipients($this->recipients)
            ->setSendDate($toSend)
            ->setHtml($this->html);

        $mailgun->shouldReceive('sendMessage')->once()->andReturn($this->response_ok);

        $result = $worker->sendCampagne($this->campagne);

        $this->assertEquals(123, $result);
    }
}
