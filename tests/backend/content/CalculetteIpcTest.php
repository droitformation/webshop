<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CalculetteIpcTest extends TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

	/**
	 * @return void
	 */
	public function testCalculetteIpcList()
	{
        $this->visit('admin/calculette/ipc')->see('Calculette IPC');
        $this->assertViewHas('ipcs');
	}
    
   public function testCalculetteCreate()
    {
        $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

        $this->visit('admin/calculette/ipc')->click('addCalculetteIpc');

        $this->type('4.5', 'indice')
            ->type($date, 'start_at')
            ->press('Envoyer');

        $this->seeInDatabase('calculette_ipc', [
            'indice'    => 4.5,
            'start_at'  => $date
        ]);
    }

    public function testUpdateCalculetteIpc()
     {
         $ipc = factory(App\Droit\Calculette\Entities\Calculette_ipc::class)->create(); // canton => be, ipc => 3
         $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

         $this->visit('admin/calculette/ipc/'.$ipc->id);

         $this->type('4.5', 'indice')
             ->type($date, 'start_at')
             ->press('Envoyer');

         $this->seeInDatabase('calculette_ipc', [
             'indice'    => 4.5,
             'start_at'  => $date
         ]);
     }

    public function testDeleteCalculetteIpc()
    {
        $ipc = factory(App\Droit\Calculette\Entities\Calculette_ipc::class)->create(); //ipc => 3

        $this->visit('admin/calculette/ipc/'.$ipc->id);

        $response = $this->call('DELETE','admin/calculette/ipc/'.$ipc->id);
    
        $this->notSeeInDatabase('calculette_ipc', ['id' => $ipc->id]);
    }
}
