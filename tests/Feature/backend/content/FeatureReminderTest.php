<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;
use Tests\TestFlashMessages;

class FeatureReminderTest extends TestCase
{
    use RefreshDatabase,ResetTbl,TestFlashMessages;

    protected $user;

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

    public function testReminderNoDates()
    {
        // Make a product
        $make    = new \tests\factories\ObjectFactory();
        $product = $make->product();

        $data = [
            'start'    => 'send_at',
            'title'    => 'Rappel pour le livre',
            'text'     => 'Dapit',
            'type'     => 'product',
            'duration' => 'month',
            'model_id' => $product->id,
            'model'    => 'App\Droit\Shop\Product\Entities\Product',
        ];

        $response = $this->call('POST', '/admin/reminder', $data);

        $response->isRedirect('admin/reminder/create/product');
        $this->assertCount(1, $this->flashMessagesForLevel('danger'));
    }

    public function testReminderUpdateNoDates()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Colloque reminder with
        $reminder = factory(\App\Droit\Reminder\Entities\Reminder::class)->create([
            'model_id' => $colloque->id,
        ]);
        
        $data = [
            'id'       => $reminder->id,
            'start'    => 'send_at',
            'type'     => 'colloque',
            'model_id' => $colloque->id,
            'model'    => 'App\Droit\Colloque\Entities\Colloque',
        ];

        $response = $this->call('PUT', '/admin/reminder/'.$reminder->id, $data);

        // Error message because date is not good
        $response->isRedirect('admin/reminder/'.$reminder->id);
        $this->assertCount(1, $this->flashMessagesForLevel('danger'));

        $data = [
            'id'       => $reminder->id,
            'start'    => 'send_at',
            'type'     => 'colloque',
            'model_id' => $colloque->id +1,
            'model'    => 'App\Droit\Colloque\Entities\Colloque',
        ];

        $response = $this->call('PUT', '/admin/reminder/'.$reminder->id, $data);

        // Error message because model not found
        $response->isRedirect('admin/reminder/'.$reminder->id);
        $this->assertCount(1, $this->flashMessagesForLevel('warning'));
    }

    public function testDeleteReminder()
    {
        $make    = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        // Colloque reminder with
        $reminder = factory(\App\Droit\Reminder\Entities\Reminder::class)->create([
            'model_id' => $colloque->id,
        ]);

        $response = $this->call('DELETE','admin/reminder/'.$reminder->id, [] ,['id' => $reminder->id]);

        $this->assertDatabaseMissing('reminders', [
            'id' => $reminder->id,
            'deleted_at' => null
        ]);
    }
}
