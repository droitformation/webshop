<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductLabelTest extends TestCase {

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
    
    public function testProductAddItem()
    {
        $items = ['categories','authors','domains'];
        
        $product   = factory(App\Droit\Shop\Product\Entities\Product::class)->create();
        $categorie = factory(App\Droit\Shop\Categorie\Entities\Categorie::class)->create();
        $author    = factory(App\Droit\Author\Entities\Author::class)->create();
        $domain    = factory(App\Droit\Domain\Entities\Domain::class)->create();

        $this->visit('admin/product/'.$product->id);
        $this->see($categorie->title);
        $this->see($author->name);
        $this->see($domain->title);
        
        $this->select($categorie->id, 'type_id[]')->press('addCategories');
        $this->select($author->id, 'type_id[]')->press('addAuthors');
        $this->select($domain->id, 'type_id[]')->press('addDomains');

        $product->load('categories','authors','domains');
        
        $this->assertTrue($product->categories->contains('id',$categorie->id));
        $this->assertTrue($product->authors->contains('id',$author->id));
        $this->assertTrue($product->domains->contains('id',$domain->id));
    }

    public function testProductDeleteItem()
    {
        $product   = factory(App\Droit\Shop\Product\Entities\Product::class)->create();
        $categorie = factory(App\Droit\Shop\Categorie\Entities\Categorie::class)->create();
        $author    = factory(App\Droit\Author\Entities\Author::class)->create();
        $domain    = factory(App\Droit\Domain\Entities\Domain::class)->create();

        $product->categories()->attach($categorie->id);
        $product->authors()->attach($author->id);
        $product->domains()->attach($domain->id);

        $this->visit('admin/product/'.$product->id);
        $this->see($categorie->title);
        $this->see($author->name);
        $this->see($domain->title);

        $this->press('deleteCategories_'.$categorie->id);
        $this->press('deleteAuthors_'.$author->id);
        $this->press('deleteDomains_'.$domain->id);

        $product->load('categories','authors','domains');

        $this->assertTrue(!$product->categories->contains('id',$categorie->id));
        $this->assertTrue(!$product->authors->contains('id',$author->id));
        $this->assertTrue(!$product->domains->contains('id',$domain->id));
    }

}
