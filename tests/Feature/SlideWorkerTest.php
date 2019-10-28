<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class SlideWorkerTest extends TestCase
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

    public function testSendAndCreateMarketingEmail()
    {
        \Queue::fake();

        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(2);

        $reponse = $this->post('admin/slide/send', ['colloque_id' => $colloque->id]);

        $emails = $colloque->inscriptions->map(function ($inscription, $key) {
            return $inscription->inscrit->email;
        })->toArray();

        $colloque = $colloque->fresh();
        // list has send_at date
        $this->assertNotNull($colloque->liste->send_at);
        // list has emails from inscriptions
        $this->assertEquals(2,count($emails));
        // 2 jobs send
        \Queue::assertPushed(\App\Jobs\SendSlide::class, function ($job) use ($emails ,$colloque) {
            return $job->colloque->id === $colloque->id && $emails[0] === $job->email;
        });

        \Queue::assertPushed(\App\Jobs\SendSlide::class, function ($job) use ($emails ,$colloque) {
            return $job->colloque->id === $colloque->id && $emails[1] === $job->email;
        });
    }
}
