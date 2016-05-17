<?php

class ExportInscriptionTest extends TestCase {

    protected $mock;
    protected $colloque;
    protected $groupe;
    protected $interface;
    protected $worker;

    protected $export_inscription;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->app->instance('App\Droit\Inscription\Repo\InscriptionInterface', $this->mock);

        $this->worker = Mockery::mock('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $this->app->instance('App\Droit\Inscription\Worker\InscriptionWorkerInterface', $this->worker);

        $this->colloque = Mockery::mock('App\Droit\Colloque\Repo\ColloqueInterface');
        $this->app->instance('App\Droit\Colloque\Repo\ColloqueInterface', $this->colloque);

        $this->export_inscription = Mockery::mock('App\Droit\Generate\Excel\ExcelInscriptionInterface');
        $this->app->instance('App\Droit\Generate\Excel\ExcelInscriptionInterface', $this->export_inscription);

        $user = App\Droit\User\Entities\User::find(710);

        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
    
	public function testTextUserOption()
	{
		$result = new \App\Droit\Generate\Excel\ExcelInscription();

		$inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make(['colloque_id' => '12']);

        $option1 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['id' => 1,'title' => 'Option checkbox', 'type' => 'checkbox']);
        $option2 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['id' => 2, 'title' => 'Option choix', 'type' => 'choix']);

        $group1 = factory(App\Droit\Option\Entities\OptionGroupe::class)->make(['id' => 1, 'colloque_id' => 12, 'option_id' => '2', 'text' => 'The text']);

        $user_option1 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['user_id' => 1, 'option_id' => 1, 'inscription_id'=> 1, 'groupe_id' => null, 'reponse' => '']);
        $user_option2 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['user_id' => 1, 'option_id' => 2, 'inscription_id'=> 1, 'groupe_id' => 1, 'reponse' => '']);

        $user_option1->option = $option1;
        $user_option2->option = $option2;
        $user_option2->option_groupe = $group1;

        $inscription->user_options = new Illuminate\Database\Eloquent\Collection([$user_option1,$user_option2]);

        $expect = 'Option checkbox;Option choix:The text';
        $html   = $result->userOptionsHtml($inscription);

        $this->assertEquals($expect, $html);

    }

    public function testExportInscriptions()
    {
        $colloque = factory(App\Droit\Colloque\Entities\Colloque::class)->make(['id' => 1]);
        $inscriptions = factory(\App\Droit\Inscription\Entities\Inscription::class, 2)->make(['user_id' => 710]);

        $this->colloque->shouldReceive('find')->once()->andReturn($colloque);
        $this->mock->shouldReceive('getByColloque')->once()->andReturn($inscriptions);

        $this->export_inscription->shouldReceive('exportInscription')->once();

        $response = $this->call('POST', 'admin/export/inscription');
    }

}
