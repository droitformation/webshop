<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;
use Tests\HubDate;

class MiscTest extends TestCase
{
    use RefreshDatabase,ResetTbl,HubDate;

    protected $helper;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->helper = new \App\Droit\Helper\Helper();
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testUpdateDateOfUpdates()
    {
        $this->setDate('hub');

        $author = factory(\App\Droit\Author\Entities\Author::class)->create();

        $data = ['id'  => $author->id, 'occupation' => 'Informaticienne', 'bio' => 'Une autre bio'];

        $response = $this->call('PUT', 'admin/author/'.$author->id, $data);

        $this->isDate('hub');
    }

    public function testUpdateDateOfUpdatesEvent()
    {
        \Event::fake();

        $author = factory(\App\Droit\Author\Entities\Author::class)->create();

        $data = ['id'  => $author->id, 'occupation' => 'Informaticienne', 'bio' => 'Une autre bio'];

        $response = $this->call('PUT', 'admin/author/'.$author->id, $data);

        \Event::assertDispatched(\App\Events\ContentUpdated::class);
    }
}
