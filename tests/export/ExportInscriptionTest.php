<?php

class ExportInscriptionTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
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

}
