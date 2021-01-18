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

    public function testSendMessagContactForm()
    {
        $data = [
            'email'    => 'droitformation.web@gmail.com',
            'name'     => 'Test Droitformation',
            'remarque' => 'Test',
            'site'     => 1
        ];

        $response = $this->call('POST', 'sendMessage', $data);

        // The email has been loggued
        $this->assertDatabaseHas('email_log', [
            'from' => 'Test Droitformation <droitformation.web@gmail.com>',
            'to'   => 'droit.formation@unine.ch',
            "subject" => "Message depuis le site Publications-droit.ch"
        ]);

    }
}
