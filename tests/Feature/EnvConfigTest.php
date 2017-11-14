<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnvConfigTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMailjetServiceDependenciesAreMocked()
    {
        $mailjet  = \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

        $client   = $mailjet->getClient();
        $resource = $mailjet->getResource();

        $return = [0 => ['ID' => 1234]];

        $client->shouldReceive('get')->once()->andReturn($resource);
        $resource->shouldReceive('success')->times(1)->andReturn(true);
        $resource->shouldReceive('getData')->times(1)->andReturn($return);

        $mailjet->getAllLists();
    }

    public function testMailgunIsMocked()
    {
        $mailgun  = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

        $mailgun->setSender('Info@domain.com');
        $mailgun->setRecipients(['cindy.leschaud@gmail.com','info@leschaud.ch']);
        $mailgun->setHtml('<html><head><title>Test</title></head><body><h3>Hello!</h3></body></html>');

        $client   = $mailgun->getClient();

        $response = new \stdClass();
        $http_response_body = new \stdClass();
        $response->http_response_code = 200;
        $http_response_body->id       = 123;
        $response->http_response_body = $http_response_body;

        $data = [
            'from'                => 'Info@domain.com',
            "subject"             => 'Test',
            'to'                  => ['cindy.leschaud@gmail.com','info@leschaud.ch'],
            "html"                => '<html><head><title>Test</title></head><body><h3>Hello!</h3></body></html>',
            "text"                => 'test',
            "recipient-variables" => json_encode([['infogdomain.com' => ['id' => 1]]]), // Required for batch sending, matches to recipient details
            "v:messageId"         => null, // Custom variable used for webhooks
            'o:deliverytime'      => null,
            'o:tag'               => null
        ];

        $client->shouldReceive('sendMessage')->once()->andReturn($response);

        $mailgun->send($data);

    }
}
