<?php

class SpecialisationTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('specialisations')->truncate();

		$specialisations = array(
			array('id' => '1','title' => 'Bail'),
			array('id' => '3','title' => 'CERT'),
			array('id' => '4','title' => 'CCFI'),
			array('id' => '5','title' => 'CEMAJ'),
			array('id' => '6','title' => 'CITU'),
			array('id' => '7','title' => 'IDS'),
			array('id' => '8','title' => 'CIDECR'),
			array('id' => '9','title' => 'CIES'),
			array('id' => '10','title' => 'CDM'),
			array('id' => '11','title' => 'Lycée'),
			array('id' => '12','title' => 'Arbitrage'),
			array('id' => '13','title' => 'Tribunal'),
			array('id' => '14','title' => 'Université'),
			array('id' => '15','title' => 'Bibliothèque'),
			array('id' => '16','title' => 'Librairie'),
			array('id' => '17','title' => 'Protection juridique'),
			array('id' => '18','title' => 'Association sportive'),
			array('id' => '19','title' => 'Protection des données'),
			array('id' => '20','title' => 'Politiciens'),
			array('id' => '22','title' => 'Médiation'),
			array('id' => '24','title' => 'Autorité tutélaire'),
			array('id' => '25','title' => 'RJN'),
			array('id' => '26','title' => 'Liste Ruedin PE'),
			array('id' => '27','title' => 'FoCo avocats'),
			array('id' => '28','title' => 'Grandes études GE'),
			array('id' => '29','title' => 'Kraus'),
			array('id' => '30','title' => 'Solar'),
			array('id' => '31','title' => 'FSRM'),
			array('id' => '32','title' => 'ASAS'),
			array('id' => '33','title' => 'Centres de compétences de l\'intégration'),
			array('id' => '34','title' => 'Comité intégration'),
			array('id' => '35','title' => 'Coordinateurs cantonaux réfugiés statutaires'),
			array('id' => '36','title' => 'Délégués à l\'intégration'),
			array('id' => '37','title' => 'Instances cantonales intégration'),
			array('id' => '38','title' => 'Service migrations NE'),
			array('id' => '39','title' => 'FSU sa.'),
			array('id' => '40','title' => 'Arch. dipl.SIA'),
			array('id' => '41','title' => 'Dév. Durable'),
			array('id' => '42','title' => 'AESOP'),
			array('id' => '43','title' => 'APEREAU'),
			array('id' => '44','title' => 'COSAC'),
			array('id' => '45','title' => 'COTER'),
			array('id' => '46','title' => 'DTAP'),
			array('id' => '47','title' => 'FSA'),
			array('id' => '48','title' => 'UVS'),
			array('id' => '49','title' => 'Directeur urbanisme'),
			array('id' => '50','title' => 'FHNW'),
			array('id' => '51','title' => 'FHO'),
			array('id' => '52','title' => 'FSU fr.'),
			array('id' => '53','title' => 'Office fédéral'),
			array('id' => '54','title' => 'CCFI [PI2]'),
			array('id' => '55','title' => 'Juriste conseiller'),
			array('id' => '56','title' => 'Juriste progressiste'),
			array('id' => '57','title' => 'Ministère public'),
			array('id' => '58','title' => 'Banque'),
			array('id' => '59','title' => 'Société immobilière'),
			array('id' => '60','title' => 'Fiduciaire'),
			array('id' => '62','title' => 'Pouvoir judiciaire'),
			array('id' => '63','title' => 'CCFI - Fiscalistes'),
			array('id' => '68','title' => 'Lycée président branche'),
			array('id' => '69','title' => 'Bail Revue'),
			array('id' => '70','title' => 'FISC'),
			array('id' => '71','title' => 'Consommation')
		);

		// Uncomment the below to run the seeder
		DB::table('specialisations')->insert($specialisations);
	}

}
