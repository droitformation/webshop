<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminCreateTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $user = App\Droit\User\Entities\User::find(710);

        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
    }
    
    /**
     * @return void
     */
    public function testAdminPages()
    {
        $sites = [1,2,3];

        foreach($sites as $site)
        {
            $this->visit('admin/page/create/'.$site)->see('Ajouter une page');
            $this->visit('admin/menu/create/'.$site)->see('Ajouter un menu');
            $this->visit('admin/arret/create/'.$site)->see('Créer arrêt');
            $this->visit('admin/analyse/create/'.$site)->see('Créer analyse');
            $this->visit('admin/bloc/create/'.$site)->see('Ajouter un bloc');
        }
    }
}
