<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendCampagne;

class CampagneUpdateTest extends BrowserKitTest
{
    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCampagneUnScheduled()
    {
        $process = App::make('App\Droit\Process\Repo\JobInterface');

        $job        = factory(App\Droit\Process\Entities\Job::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $campagne   = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create(['newsletter_id' => $newsletter->id, 'job_id' => $job->id]);

        $response = $this->call('GET', 'build/campagne/cancel/'.$campagne->id);

        $campagne = $campagne->fresh();

        $jobs = $process->getAll();

        $this->assertEquals(0, $jobs->count());
        $this->assertNull($campagne->job_id);
    }
}
