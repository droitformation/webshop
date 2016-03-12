<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminCreatePagesTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $user = App\Droit\User\Entities\User::find(710);

        $this->actingAs($user);

    }

	/**
	 * @return void
	 */
	public function testIndexDomain()
	{
        $this->visit('admin/domain/create')->see('Ajouter une collection');
	}

    /**
     * @return void
     */
    public function testIndexTheme()
    {
        $this->visit('admin/theme/create')->see('Ajouter un thème');
    }

    /**
     * @return void
     */
    public function testIndexShipping()
    {
        $this->visit('admin/shipping/create')->see('Ajouter frais de port');
    }

    /**
     * @return void
     */
    public function testIndexCoupon()
    {
        $this->visit('admin/coupon/create')->see('Ajouter coupon');
    }

    /**
     * @return void
     */
    public function testIndexSpecialisation()
    {
        $this->visit('admin/specialisation/create')->see('Ajouter une spécialisation');
    }

    /**
     * @return void
     */
    public function testIndexMembre()
    {
        $this->visit('admin/member/create')->see('Ajouter un type de membre');
    }

    /**
     * @return void
     */
    public function testIndexAttribut()
    {
        $this->visit('admin/attribut/create')->see('Ajouter un attribut');
    }

    /**
     * @return void
     */
    public function testIndexColloque()
    {
        $this->visit('admin/colloque/create')->see('Ajouter un colloque');
    }

    /**
     * @return void
     */
    public function testIndexOrganisateur()
    {
        $this->visit('admin/organisateur/create')->see('Ajouter un organisateur');
    }

    /**
     * @return void
     */
    public function testIndexLocation()
    {
        $this->visit('admin/location/create')->see('Ajouter un lieu');
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
