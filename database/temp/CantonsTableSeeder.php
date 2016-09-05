<?php

class CantonsTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('cantons')->truncate();

		$cantons = array(
			array('title' => 'Appenzell Rhodes-Extérieures (AR)'),
			array('title' => 'Appenzell Rhodes-Intérieures (AI)'),
			array('title' => 'Argovie (AG)'),
			array('title' => 'Bâle-Campagne (BL)'),
			array('title' => 'Bâle-Ville (BS)'),
			array('title' => 'Berne (BE)'),
			array('title' => 'Fribourg (FR)'),
			array('title' => 'Genève (GE)'),
			array('title' => 'Glaris (GL)'),
			array('title' => 'Grisons (GR)'),
			array('title' => 'Jura (JU)'),
			array('title' => 'Lucerne (LU)'),
			array('title' => 'Neuchâtel (NE)'),
			array('title' => 'Nidwald (NW)'),
			array('title' => 'Obwald (OW)'),
			array('title' => 'Schaffhouse (SH)'),
			array('title' => 'Schwyz (SZ)'),
			array('title' => 'Soleure (SO)'),
			array('title' => 'St-Gall (SG)'),
			array('title' => 'Tessin (TI)'),
			array('title' => 'Thurgovie (TG)'),
			array('title' => 'Uri (UR)'),
			array('title' => 'Valais (VS)'),
			array('title' => 'Vaud (VD)'),
			array('title' => 'Zoug (ZG)'),
			array('title' => 'Zurich (ZU)')
		);

		// Uncomment the below to run the seeder
		DB::table('cantons')->insert($cantons);
	}

}
