<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminCreateTest extends BrowserKitTest {

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
        \Mockery::close();
    }
    
    /**
     * @return void
     */
    public function testAdmin1Pages()
    {
        $this->visit('admin/page/create/1')->see('Ajouter une page');
        $this->visit('admin/menu/create/1')->see('Ajouter un menu');
        $this->visit('admin/arret/create/1')->see('Créer arrêt');
        $this->visit('admin/analyse/create/1')->see('Créer analyse');
        $this->visit('admin/bloc/create/1')->see('Ajouter un bloc');
    }

    /**
     * @return void
     */
    public function testAdmin2Pages()
    {
        $this->visit('admin/page/create/2')->see('Ajouter une page');
        $this->visit('admin/menu/create/2')->see('Ajouter un menu');
        $this->visit('admin/arret/create/2')->see('Créer arrêt');
        $this->visit('admin/analyse/create/2')->see('Créer analyse');
        $this->visit('admin/bloc/create/2')->see('Ajouter un bloc');
    }

    /**
     * @return void
     */
    public function testAdmin3Pages()
    {
        $this->visit('admin/page/create/3')->see('Ajouter une page');
        $this->visit('admin/menu/create/3')->see('Ajouter un menu');
        $this->visit('admin/arret/create/3')->see('Créer arrêt');
        $this->visit('admin/analyse/create/3')->see('Créer analyse');
        $this->visit('admin/bloc/create/3')->see('Ajouter un bloc');
    }
}
