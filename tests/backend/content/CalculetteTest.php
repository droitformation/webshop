<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CalculetteTest extends TestCase {

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
	public function testCalculetteList()
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

        $this->type('Un calculette', 'taux')
            ->type('Un calculette', 'start_at')
            ->select($canton, 'canton')
            ->press('Envoyer');

        $this->seeInDatabase('calculettes', [
            'taux'      => 4.5,
            'start_at'  => $date,
            'canton'    => $canton
        ]);
    }

    /* public function testUpdateCalculette()
     {
         $calculette = factory(App\Droit\Calculette\Entities\Calculette::class)->create();

         $this->visit('admin/calculette/'.$calculette->id)->see($calculette->title);

         $this->type('Un calculette', 'title')->select('sidebar', 'position')->press('Envoyer');

         $this->seeInDatabase('calculettes', [
             'title'       => 'Un calculette',
             'position'    => 'sidebar',
             'site_id'     => $calculette->site_id
         ]);
     }

     public function testDeleteCalculette()
     {
         $calculette = factory(App\Droit\Calculette\Entities\Calculette::class)->create();

         $this->visit('admin/calculette/'.$calculette->id)->see($calculette->title);

         $response = $this->call('DELETE','admin/calculette/'.$calculette->id);

         $this->notSeeInDatabase('calculettes', [
             'id' => $calculette->id,
         ]);
     }*/
}
