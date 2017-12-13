<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompteTest extends BrowserKitTest {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
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
	public function testCompteList()
	{
        $this->visit('admin/compte')->see('Comptes');
        $this->assertViewHas('comptes');
	}
    
   public function testCompteCreate()
    {
        $this->visit('admin/compte')->click('addCompte');
        $this->seePageIs('admin/compte/create');

        $this->type('Un compte', 'motif')->type('Adresse', 'adresse')->type('234-131-2', 'compte')->press('Envoyer');

        $this->seeInDatabase('comptes', [
            'motif'      => 'Un compte',
            'adresse'    => 'Adresse',
            'compte'     => '234-131-2'
        ]);
    }

    public function testUpdateCompte()
    {
        $compte = factory(App\Droit\Compte\Entities\Compte::class)->create();

        $this->visit('admin/compte/'.$compte->id)->see($compte->motif);

        $response = $this->call('PUT', 'admin/compte/'.$compte->id,
            [
                'id'       => $compte->id,
                'motif'    => 'Un autre compte',
                'adresse'  => '<p>Autre Adresse</p>',
                'compte'   => '20-4130-2',
            ]
        );

        $this->seeInDatabase('comptes', [
            'id'       => $compte->id,
            'motif'    => 'Un autre compte',
            'adresse'  => '<p>Autre Adresse</p>',
            'compte'   => '20-4130-2',
        ]);
    }
  
    public function testDeleteCompte()
    {
        $compte = factory(App\Droit\Compte\Entities\Compte::class)->create();

        $this->visit('admin/compte/'.$compte->id)->see($compte->title);

        $response = $this->call('DELETE','admin/compte/'.$compte->id);

        $this->notSeeInDatabase('comptes', [
            'id' => $compte->id,
        ]);
    }
}
