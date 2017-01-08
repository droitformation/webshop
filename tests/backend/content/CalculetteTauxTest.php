<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CalculetteTauxTest extends TestCase {

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
	public function testCalculetteTauxList()
	{
        $this->visit('admin/calculette/taux')->see('Calculette taux');
        $this->assertViewHas('taux');

        $this->visit('admin/calculette/ipc')->see('Calculette IPC');
        $this->assertViewHas('ipcs');
	}
    
   public function testCalculetteCreate()
    {
        $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

        $canton = array_rand(config('calculette.cantons'));

        $this->visit('admin/calculette/taux')->click('addCalculetteTaux');

        $this->type('4.5', 'taux')
            ->type($date, 'start_at')
            ->select($canton, 'canton')
            ->press('Envoyer');

        $this->seeInDatabase('calculette_taux', [
            'taux'      => 4.5,
            'start_at'  => $date,
            'canton'    => $canton
        ]);
    }

    public function testUpdateCalculetteTaux()
     {
         $taux = factory(App\Droit\Calculette\Entities\Calculette_taux::class)->create(); // canton => be, taux => 3
         $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

         $this->visit('admin/calculette/taux/'.$taux->id);

         $this->type('4.5', 'taux')
             ->type($date, 'start_at')
             ->select('vd', 'canton')
             ->press('Envoyer');

         $this->seeInDatabase('calculette_taux', [
             'taux'      => 4.5,
             'start_at'  => $date,
             'canton'    => 'vd'
         ]);
     }

    public function testDeleteCalculetteTaux()
    {
        $taux = factory(App\Droit\Calculette\Entities\Calculette_taux::class)->create(); // canton => be, taux => 3

        $this->visit('admin/calculette/taux/'.$taux->id);

        $response = $this->call('DELETE','admin/calculette/taux/'.$taux->id);

        $this->notSeeInDatabase('calculette_taux', [
            'id' => $taux->id,
        ]);
    }
}
