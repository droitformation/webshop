<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatsTest extends BrowserKitTest
{
    protected $charts;
    
    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);
        
        $this->charts = new \App\Droit\Newsletter\Worker\Charts();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCompileStat()
    {
        $stats['DeliveredCount'] = 5;
        $stats['ClickedCount']   = 2;
        $stats['OpenedCount']    = 3;
        $stats['BouncedCount']   = 1;

        $data['total']     = 5;
        $data['clicked']   = 40.0;
        $data['opened']    = 60.0;
        $data['bounced']   = 20.0;
        $data['nonopened'] = 20.0;

        $actual = $this->charts->compileStats($stats);

        $this->assertEquals($data, $actual);

    }
}
