<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminIndexPagesTest extends TestCase {

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
        $this->visit('admin/domain')->see('Collections');
        $this->assertViewHas('domains');
	}

    /**
     * @return void
     */
    public function testIndexAuthor()
    {
        $this->visit('admin/author')->see('Auteurs');
        $this->assertViewHas('authors');
    }

    /**
     * @return void
     */
    public function testIndexTheme()
    {
        $this->visit('admin/theme')->see('Thèmes');
        $this->assertViewHas('themes');
    }

    /**
     * @return void
     */
    public function testIndexShipping()
    {
        $this->visit('admin/shipping')->see('Frais de port');
        $this->assertViewHas('shippings');
    }

    /**
     * @return void
     */
    public function testIndexCoupon()
    {
        $this->visit('admin/coupon')->see('Coupons rabais');
        $this->assertViewHas('coupons');
    }

    /**
     * @return void
     */
    public function testIndexSpecialisation()
    {
        $this->visit('admin/specialisation')->see('Spécialisations');
        $this->assertViewHas('specialisations');
    }

    /**
     * @return void
     */
    public function testIndexMembre()
    {
        $this->visit('admin/member')->see('Membres');
        $this->assertViewHas('members');
    }

    /**
     * @return void
     */
    public function testIndexAttribut()
    {
        $this->visit('admin/attribut')->see('Attributs des produits');
        $this->assertViewHas('attributs');
    }

    /**
     * @return void
     */
    public function testIndexColloque()
    {
        $this->visit('admin/colloque')->see('Colloques');
        $this->assertViewHas('colloques');
    }

    /**
     * @return void
     */
    public function testIndexOrganisateur()
    {
        $this->visit('admin/organisateur')->see('Organisateurs');
        $this->assertViewHas('organisateurs');
    }

    /**
     * @return void
     */
    public function testIndexLocation()
    {
        $this->visit('admin/location')->see('Lieux');
        $this->assertViewHas('locations');
    }

    /**
     * @return void
     */
    public function testExportPage()
    {
        $this->visit('admin/export/view')->see('Exporter');
        $this->assertViewHas('cantons');
        $this->assertViewHas('pays');
        $this->assertViewHas('specialisations');
        $this->assertViewHas('members');
    }

    /**
     * @return void
     */
    public function testAdminPages()
    {
        $sites = [1,2,3];

        foreach($sites as $site)
        {
            $this->visit('admin/pages/'.$site)->see('Pages');
            $this->assertViewHas('pages');

            $this->visit('admin/menus/'.$site)->see('Menus');
            $this->assertViewHas('menus');

            $this->visit('admin/arrets/'.$site)->see('Arrêts');
            $this->assertViewHas('arrets');

            $this->visit('admin/analyses/'.$site)->see('Analyses');
            $this->assertViewHas('analyses');

            $this->visit('admin/blocs/'.$site)->see('Blocs de contenu');
            $this->assertViewHas('blocs');
        }

    }
}
