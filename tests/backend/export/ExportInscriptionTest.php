<?php

use Laravel\BrowserKitTesting\DatabaseTransactions;

class ExportInscriptionTest extends BrowserKitTest {

    protected $mock;
    protected $colloque;
    protected $groupe;
    protected $interface;
    protected $worker;

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
    
	public function testTextUserOption()
	{
        $exporter = new \App\Droit\Generate\Export\ExportInscription();

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
        $html   = $exporter->userOptionsHtml($inscription);

        $this->assertEquals($expect, $html);

    }

    public function testSortByOptions()
    {
        $inscription = factory(App\Droit\Inscription\Entities\Inscription::class)->make(['colloque_id' => '12']);

        $option1 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['id' => 1,'title' => 'Option choix 1', 'type' => 'choix']);
        $option2 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['id' => 2, 'title' => 'Option choix 2', 'type' => 'choix']);

        $group1 = factory(App\Droit\Option\Entities\OptionGroupe::class)->make(['id' => 1, 'colloque_id' => 12, 'option_id' => '1', 'text' => 'The text 1']);
        $group2 = factory(App\Droit\Option\Entities\OptionGroupe::class)->make(['id' => 2, 'colloque_id' => 12, 'option_id' => '2', 'text' => 'The text 2']);

        $user_option1 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['user_id' => 1, 'option_id' => 1, 'inscription_id'=> 1, 'groupe_id' => 1]);
        $user_option2 = factory(App\Droit\Option\Entities\OptionUser::class)->make(['user_id' => 1, 'option_id' => 2, 'inscription_id'=> 1, 'groupe_id' => 2]);

        $user_option1->option = $option1;
        $user_option2->option = $option2;

        $user_option1->option_groupe = $group1;
        $user_option2->option_groupe = $group2;

        $inscription->user_options = new Illuminate\Database\Eloquent\Collection([$user_option1,$user_option2]);

        $data = ['Name' => 'Cindy Leschaud'];
        
        $exporter = new \App\Droit\Generate\Export\ExportInscription();
        $exporter->options = ['1' => 'Option choix 1', '2' => 'Option choix 2'];
        $exporter->groupes = ['1' => 'The text 1', '2' => 'The text 2'];
        
        $actual = $exporter->sortByOption($inscription, $data, []);
        $expect = [
            1 => [
                1 => [0 => ['Name' => 'Cindy Leschaud']]
            ],
            2 => [
                2 => [0 => ['Name' => 'Cindy Leschaud']]
            ]
        ];

        $this->assertEquals($expect, $actual);

    }

}
