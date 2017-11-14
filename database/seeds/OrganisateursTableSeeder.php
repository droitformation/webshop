<?php

class OrganisateursTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('organisateurs')->truncate();

		$organisateurs = array(
			array('id' => '1','name' => 'Faculté de droit','description' => 'La Faculté de droit de l\'Université de Neuchâtel','url' => 'http://www2.unine.ch/droit','logo' => 'facdroit.jpg','centre' => '1','tva' => '','adresse' => 'Faculté de droit<br/>Avenue du 1er-Mars 26 <br/>2000 Neuchâtel<br/>Suisse '),
			array('id' => '2','name' => 'CEMAJ','description' => 'Centre de recherche sur les modes amiables et juridictionnels de gestion des conflits','url' => 'https://www2.unine.ch/cemaj','logo' => 'cemaj.png','centre' => '1','tva' => '','adresse' => 'cemaj<br/> av. du 1er Mars 26<br/> 2000 Neuchâtel '),
			array('id' => '3','name' => 'CERT','description' => 'Centre d\'étude des relations de travail','url' => 'https://www2.unine.ch/cert','logo' => 'cert.png','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '4','name' => 'CDM','description' => 'Le Centre de droit des migrations','url' => 'https://www2.unine.ch/ius-migration','logo' => 'cdm.png','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '5','name' => 'IDS','description' => 'Institut de droit de la santé','url' => 'https://www2.unine.ch/ids','logo' => 'ids.png','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '6','name' => 'CIDECR ','description' => 'Centre interdisciplinaire de droit et d\'étude de la circulation routière ','url' => 'https://www2.unine.ch/cidecr','logo' => 'cidecr.png','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '7','name' => 'Bail','description' => 'Séminaire sur le droit du bail','url' => 'http://www2.unine.ch/bail','logo' => 'bail.jpg','centre' => '1','tva' => '','adresse' => 'Séminaire sur le droit du bail<br/> Université de Neuchâtel<br/>  avenue du 1er-Mars 26<br/>2000 Neuchâtel'),
			array('id' => '8','name' => '[PI]2','description' => 'Le Pôle de propriété intellectuelle et de l\'innovation [PI]2','url' => 'http://www2.unine.ch/pi2','logo' => 'pi2.jpg','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '9','name' => 'CCFI','description' => 'Centre de droit commercial, fiscal et de l\'innovation','url' => '','logo' => 'ccfi.png','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '10','name' => 'CITU','description' => 'Centre interdisciplinaire de l\'aménagement du territoire et de l\'urbanisme','url' => '','logo' => 'citu.png','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '11','name' => 'Organisée conjointement entre l\'Université de Neuchâtel et le Département fédéral des affaires étrangères (DFAE)','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '12','name' => 'Chaires de droit et des migrations (UNINE) et de droit des étrangers (UNIL)','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '13','name' => 'CIDECR en collaboration avec DIKE et la Revue','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '14','name' => 'CEMAJ et GEMME - section Suisse','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '15','name' => 'School of Management and Law / Faculté de droit de l\'Université de Neuchâtel','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '16','name' => 'Young IFA Network - Faculté de droit de l\'Université de Neuchâtel','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '17','name' => 'CERT et CJE Sàrl','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '18','name' => 'Faculté de droit avec le soutien de l\'Ordre des avocats neuchâtelois et de la Chambre des notaires neuchâtelois','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '19','name' => 'CCFI [PI]2, EPFL et Le Réseau','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '20','name' => 'Faculté de droit, en collaboration avec la Société suisse de droit international','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '21','name' => 'STOP A LA PIRATERIE','description' => 'STOP A LA PIRATERIE et le Pôle de propriété intellectuelle et de l\'innovation [PI]² de l\'Université de Neuchâtel','url' => '','logo' => 'stoppiracy.jpg','centre' => '1','tva' => '','adresse' => ''),
			array('id' => '22','name' => 'Université de Neuchâtel - Faculté de droit','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '23','name' => 'Université de Neuchâtel','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '24','name' => 'CEMAJ en collaboration avec les juristes progressistes neuchâtelois','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '25','name' => 'Faculté de droit en collaboration avec l\'Association suisse du droit de la concurrence (ASAS)','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '26','name' => 'Centre de droit commercial, fiscal et de l\'innovation - Institut du droit de la santé','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => ''),
			array('id' => '27','name' => 'CCFI - [PI2]','description' => '','url' => '','logo' => '','centre' => NULL,'tva' => '','adresse' => '')
		);
		
		// Uncomment the below to run the seeder
		DB::table('organisateurs')->insert($organisateurs);
	}
}
