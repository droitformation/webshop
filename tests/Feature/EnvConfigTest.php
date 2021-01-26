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
        $client   = \Mockery::mock('Mailjet\Client');
        $resource = \Mockery::mock('Mailjet\Resources');

        $mailjet = new \App\Droit\Newsletter\Worker\MailjetService($client,$resource);

        $return = [0 => ['ID' => 1234]];

        $client->shouldReceive('get')->once()->andReturn($resource);
        $resource->shouldReceive('success')->times(1)->andReturn(true);
        $resource->shouldReceive('getData')->times(1)->andReturn($return);

        $mailjet->getAllLists();
    }
}
