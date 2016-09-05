<?php

class BlocPageTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('bloc_pages')->truncate();

		$bloc_pages = array(
			array('id' => '1','bloc_id' => '1','page_id' => '9'),
			array('id' => '2','bloc_id' => '1','page_id' => '10'),
			array('id' => '3','bloc_id' => '1','page_id' => '11'),
			array('id' => '4','bloc_id' => '1','page_id' => '12'),
			array('id' => '5','bloc_id' => '2','page_id' => '9'),
			array('id' => '6','bloc_id' => '2','page_id' => '10'),
			array('id' => '7','bloc_id' => '2','page_id' => '11'),
			array('id' => '8','bloc_id' => '2','page_id' => '12'),
			array('id' => '9','bloc_id' => '3','page_id' => '2'),
			array('id' => '10','bloc_id' => '3','page_id' => '3'),
			array('id' => '11','bloc_id' => '3','page_id' => '4'),
			array('id' => '12','bloc_id' => '3','page_id' => '5'),
			array('id' => '13','bloc_id' => '3','page_id' => '6'),
			array('id' => '14','bloc_id' => '3','page_id' => '7'),
			array('id' => '15','bloc_id' => '3','page_id' => '8'),
			array('id' => '16','bloc_id' => '4','page_id' => '2'),
			array('id' => '17','bloc_id' => '4','page_id' => '3'),
			array('id' => '18','bloc_id' => '4','page_id' => '4'),
			array('id' => '19','bloc_id' => '4','page_id' => '5'),
			array('id' => '20','bloc_id' => '4','page_id' => '6'),
			array('id' => '21','bloc_id' => '4','page_id' => '7'),
			array('id' => '22','bloc_id' => '4','page_id' => '8'),
			array('id' => '23','bloc_id' => '4','page_id' => '13'),
			array('id' => '24','bloc_id' => '4','page_id' => '14'),
			array('id' => '25','bloc_id' => '4','page_id' => '15'),
			array('id' => '26','bloc_id' => '4','page_id' => '16'),
			array('id' => '27','bloc_id' => '1','page_id' => '17'),
			array('id' => '28','bloc_id' => '1','page_id' => '18'),
			array('id' => '29','bloc_id' => '2','page_id' => '17'),
			array('id' => '30','bloc_id' => '2','page_id' => '18')
		);

		// Uncomment the below to run the seeder
		DB::table('bloc_pages')->insert($bloc_pages);
	}

}
