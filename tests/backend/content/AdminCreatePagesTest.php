<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminCreatePagesTest extends BrowserKitTest {

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
	public function testIndexDomain()
	{
        $this->visit('admin/domain/create')->see('Ajouter une collection');
	}

    /**
     * @return void
     */
    public function testIndexAuthor()
    {
        $this->visit('admin/author/create')->see('Ajouter un auteur');
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
    
}
