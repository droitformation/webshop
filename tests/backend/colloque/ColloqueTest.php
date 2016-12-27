<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ColloqueTest extends TestCase {

    use DatabaseTransactions;

    protected $colloque;

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

	public function testIntersectAnnexes()
	{
        $annexes = ['bon','facture','bv'];
        $result  = (count(array_intersect($annexes, ['bon','facture'])) == count(['bon','facture']) ? true : false);

        $this->assertTrue($result);
	}

    public function testListCurrentColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $make->colloque(); // colloque active
        $make->colloque(); // colloque active
        $make->colloque(\Carbon\Carbon::now()->subMonths(2), \Carbon\Carbon::now()->subMonth()); // colloque finished
        
        $this->visit('admin/colloque');
        $this->assertViewHas('colloques');

        $content   = $this->response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(2, $colloques->count());
    }

    public function testListArchiveColloques()
    {
        $make = new \tests\factories\ObjectFactory();

        $make->colloque(\Carbon\Carbon::createFromDate(2015, 5, 21), \Carbon\Carbon::createFromDate(2015, 5, 31)); // colloque finished

        $this->visit('admin/colloque/archive/2015');
        $this->assertViewHas('colloques');

        $content   = $this->response->getOriginalContent();
        $content   = $content->getData();
        $colloques = $content['colloques'];

        $this->assertEquals(1, $colloques->count());
    }

    public function testCreateNewColloque()
    {
        $start    = \Carbon\Carbon::now()->addDays(5)->toDateTimeString();
        $register = \Carbon\Carbon::now()->toDateTimeString();

        $this->visit('admin/colloque/create');

        $this->type('Un beau colloque', 'titre');
        $this->type('Un beau sous-titre', 'soustitre');
        $this->type('Un sujet', 'sujet');
        $this->type('Cindy', 'organisateur');
        $this->type($start, 'start_at');
        $this->type($register, 'registration_at');
        $this->press('Envoyer');
    }

    public function testColloqueEditPage()
    {

    }
    
    public function testDeleteColloque()
    {

    }

}
