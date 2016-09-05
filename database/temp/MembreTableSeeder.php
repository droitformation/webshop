<?php

class MembreTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('members')->truncate();

		$members = array(
			array('id' => '1','title' => 'Membre OAN'),
			array('id' => '2','title' => 'Membre chambre des notaires NE'),
			array('id' => '4','title' => 'Membre de l\'UNINE'),
			array('id' => '5','title' => 'Membre OA BE'),
			array('id' => '7','title' => 'Membre ODA GE'),
			array('id' => '8','title' => 'Membre OA Vaud'),
			array('id' => '10','title' => 'Membre OA ZH'),
			array('id' => '11','title' => 'Membre ODA FR'),
			array('id' => '12','title' => 'Membre OA VS'),
			array('id' => '13','title' => 'Membre OA Tessin')
		);

		// Uncomment the below to run the seeder
		DB::table('members')->insert($members);
	}

}
