<?php

namespace Tests\Unit;

use Tests\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class PurgeTest extends TestCase
{
    public function testResultPurgeInvalid()
    {
        $result = [
            'debounce' => [
                'email'  => 'benoit.fracheboud@unine.ch',
                'result' => 'Invalid',
            ],
            'success' => 1,
            'balance' => 98
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($result))]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $purger = new \App\Droit\Newsletter\Service\Purger($client);

        $result = $purger->status($result);

        $this->assertEquals($result, 'Invalid');
    }

    public function testResultPurgeValidBalanceLow()
    {
        \Mail::fake();

        $result = [
            'debounce' => [
                'email'  => 'benoit.fracheboud@unine.ch',
                'result' => 'Invalid',
            ],
            'success' => 1,
            'balance' => 4
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($result))]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $purger = new \App\Droit\Newsletter\Service\Purger($client);

        $result = $purger->status($result);

        \Mail::assertSent(\App\Mail\NotifyWebmaster::class);
    }

    public function testResultPurgeNotWorking()
    {
        $result = [
            'debounce' => [
                'email'  => 'benoit.fracheboud@unine.ch',
                'result' => 'Invalid',
            ],
            'success' => 0,
            'balance' => 98
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($result))]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $purger = new \App\Droit\Newsletter\Service\Purger($client);

        $result = $purger->status($result);

        $this->assertEquals($result, null);
    }

    public function testResultPurgeInvalidStatus()
    {
        $result = [
            'debounce' => [
                'email'  => 'benoit.fracheboud@unine.ch',
                'result' => 'Invalid',
            ],
            'success' => 1,
            'balance' => 98
        ];

        // Create a mock and queue two responses.
        $mock = new MockHandler([new Response(200, [], json_encode($result)),]);
        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $purger = new \App\Droit\Newsletter\Service\Purger($client);

        $response = $purger->verify('othera.sawefwef@unidsvvne.ch');
        $result = $purger->status($response);

        $this->assertEquals($result, 'Invalid');
    }

    public function testResultPurgeValidBalanceLowStatus()
    {
        \Mail::fake();

        $result = [
            'debounce' => [
                'email'  => 'othera.sawefwef@unidsvvne.ch',
                'result' => 'Invalid',
            ],
            'success' => 1,
            'balance' => 4
        ];

        $mock = new MockHandler([new Response(402, [], json_encode($result))]);
        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $purger = new \App\Droit\Newsletter\Service\Purger($client);

        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        $response = $purger->verify('othera.sawefwef@unidsvvne.ch');
        $result = $purger->status($response);

        \Mail::assertSent(\App\Mail\NotifyWebmaster::class);
    }

    public function testResultPurgeNotWorkingStatus()
    {
        $result = [
            'debounce' => [
                'email'  => 'othera.sawefwef@unidsvvne.ch',
                'result' => 'Invalid',
            ],
            'success' => 0,
            'balance' => 98
        ];

        $mock = new MockHandler([new Response(403, [], json_encode($result))]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $purger = new \App\Droit\Newsletter\Service\Purger($client);

        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        $response = $purger->verify('othera.sawefwef@unidsvvne.ch');
        $result = $purger->status($response);

        $this->assertEquals($result, null);
    }
}
