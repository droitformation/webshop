<?php

class InscriptionTest extends TestCase {

    protected $mock;
    protected $colloque;
    protected $groupe;
    protected $interface;
    protected $worker;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->mock);

        $this->groupe = Mockery::mock('App\Droit\Inscription\Repo\GroupeInterface');
        $this->app->instance('App\Droit\Inscription\Repo\GroupeInterface', $this->groupe);

        $this->worker = Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

        $this->colloque = Mockery::mock('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->app->instance('App\Droit\Colloque\Repo\ColloqueInterface', $this->colloque);

        $user = App\Droit\User\Entities\User::find(710);

        $this->actingAs($user);
    }

    public function tearDown()
    {
         \Mockery::close();
    }

    /**
	 *
	 * @return void
	 */
	public function testRegisterNewInscription()
	{
        $this->WithoutEvents();
        $this->withoutJobs();

        $input = ['type' => 'simple', 'colloque_id' => 39, 'user_id' => 710, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make();

        $this->worker->shouldReceive('register')->once()->andReturn($inscription);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('POST', '/admin/inscription', $input);

        $this->assertRedirectedTo('/admin/inscription/colloque/39');

	}

    /**
     *
     * @return void
     */
    public function testRegisterMultipleNewInscription()
    {
        $this->WithoutEvents();
        $this->withoutJobs();

        $input = ['type' => 'multiple', 'colloque_id' => 39, 'user_id' => 1, 'participant' => ['Jane Doe', 'John Doa'], 'price_id' => [290, 290] ];

        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make();

        $this->worker->shouldReceive('registerGroup')->once()->andReturn($group);
        $this->worker->shouldReceive('makeDocuments')->once();

        $response = $this->call('POST', '/admin/inscription',$input);

        $this->assertRedirectedTo('/admin/inscription/colloque/39');

    }

    public function testLastInscriptions()
    {
        $inscriptions = factory(\App\Droit\Inscription\Entities\Inscription::class, 2)->make(['user_id' => 710]);
        $paginator = new \Illuminate\Pagination\Paginator($inscriptions, 2, 1);

        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 39]);

        $this->colloque->shouldReceive('find')->once()->andReturn($colloque);
        $this->mock->shouldReceive('getByColloque')->once()->andReturn($paginator);

        $response = $this->call('GET', 'admin/inscription/colloque/39');

        $this->assertViewHas('inscriptions');
    }

    /**
     * Inscription from frontend
     * @return void
     */
    public function testRegisterInscription()
    {
        $this->WithoutEvents();
        $this->withoutJobs();

        $input = ['type' => 'simple', 'colloque_id' => 39, 'user_id' => 710, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make();

        $this->worker->shouldReceive('register')->once()->andReturn($inscription);

        $response = $this->call('POST', 'registration', $input);

        $this->assertRedirectedTo('/');
    }


    /**
     * Inscription update from admin
     * @return void
     */
    public function testUpdateInscription()
    {
        $input = ['id' => 3, 'colloque_id' => 39, 'user_id' => 710, 'inscription_no' => '71-2015/1', 'price_id' => 290];

        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make($input);

        $this->mock->shouldReceive('update')->once()->andReturn($inscription);
        $this->worker->shouldReceive('makeDocuments')->once();

        $this->visit('/admin/user/710');

        $response = $this->call('PUT', 'admin/inscription/3', $input);

        $this->assertRedirectedTo('/admin/user/710');
    }

    /**
     * Send Inscription from admin
     * @return void
     */
    public function testSendInscription()
    {
        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make([
            'id' => 3, 'colloque_id' => 39, 'user_id' => 710, 'inscription_no' => '71-2015/1', 'price_id' => 290
        ]);

        $this->mock->shouldReceive('find')->once()->andReturn($inscription);
        $this->worker->shouldReceive('sendEmail')->once();

        $this->visit('/admin/user/710');

        $response = $this->call('POST', 'admin/inscription/send', ['id' => 3, 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertRedirectedTo('/admin/user/710');
    }

    /**
     * Send Group inscription from admin
     * @return void
     */
    public function testSendGroupInscription()
    {
        $group = factory(App\Droit\Inscription\Entities\Groupe::class)->make();

        $this->groupe->shouldReceive('find')->once()->andReturn($group);
        $this->worker->shouldReceive('sendEmail')->once();

        $this->visit('/admin/user/710');

        $response = $this->call('POST', 'admin/inscription/send', ['id' => 3, 'group_id' => 3 ,'email' => 'cindy.leschaud@gmail.com']);

        $this->assertRedirectedTo('/admin/user/710');
    }

    public function testGenerateDoc()
    {
        $annexes = ['bon','facture', 'bv'];

        $inscription        = factory(App\Droit\Inscription\Entities\Inscription::class)->make();
        $inscription->price = factory(App\Droit\Price\Entities\Price::class)->make(['price' => 10000]);

        // make all documents
        foreach($annexes as $annexe)
        {
            $result = $this->make($annexe,$inscription);
            $this->assertTrue($result);
        }
    }

    public function testGenerateDocFree()
    {
        $inscription        = factory(App\Droit\Inscription\Entities\Inscription::class)->make();
        $inscription->price = factory(App\Droit\Price\Entities\Price::class)->make(['price' => 0]);

        // No need to make facture or bv if the price is 0

        $result = $this->make('bon',$inscription);
        $this->assertTrue($result);

        $result = $this->make('facture',$inscription);
        $this->assertFalse($result);

        $result = $this->make('bv',$inscription);
        $this->assertFalse($result);
    }

    public function make($annexe,$inscription)
    {
        if($annexe == 'bon' || ($inscription->price_cents > 0 && ($annexe == 'facture' || $annexe == 'bv')))
        {
            return true;
        }

        return false;
    }
}
