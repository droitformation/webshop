<?php

class LocationTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('locations')->truncate();

		$locations = array(
			array('id' => '1','name' => 'Aula des Jeunes-Rives','adresse' => '<p>Espace Louis-Agassiz 1, Neuchâtel</p>','url' => '','map' => 'plan-acces.jpg'),
			array('id' => '2','name' => 'Studen ','adresse' => '(Berne)','url' => '','map' => NULL),
			array('id' => '3','name' => 'Aula de la Faculté de droit (salle C46)','adresse' => 'Av. du 1er-Mars 26, Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '4','name' => 'Beau-Rivage Hôtel','adresse' => 'Esplanade du Mont-Blanc 1, Neuchâtel','url' => '','map' => 'beaurivage.jpg'),
			array('id' => '6','name' => 'Neuchâtel','adresse' => '','url' => '','map' => 'neuchatel.jpg'),
			array('id' => '7','name' => 'Cafeteria du bâtiment principal de l\'Université','adresse' => '1er Mars 26, Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '8','name' => 'Université de Neuchâtel, bâtiment principal - salle C45','adresse' => 'Av. du 1er-Mars 26, Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '9','name' => 'Université de Neuchâtel et Université de Lausanne','adresse' => '','url' => '','map' => NULL),
			array('id' => '10','name' => 'SALLE RE48, Espace Louis-Agassiz 1','adresse' => 'Neuchâtel','url' => '','map' => 'carte.jpg'),
			array('id' => '11','name' => 'Institut de langue et civilisation françaises','adresse' => 'Fbg de l\'Hôpital 61-63, Neuchâtel','url' => '','map' => 'fbhopital.jpg'),
			array('id' => '12','name' => 'Universités de Neuchâtel, Lausanne et Genève','adresse' => '','url' => '','map' => NULL),
			array('id' => '13','name' => 'Microcity','adresse' => 'Rue de la Maladière 71-73, Neuchâtel','url' => '','map' => 'microcity.png'),
			array('id' => '14','name' => 'Hôtel Beaulac, Esplanade Léopold-Robert 2','adresse' => 'Neuchâtel','url' => '','map' => 'beaulac.png'),
			array('id' => '15','name' => 'Salle C54','adresse' => 'Av. du 1er-Mars 26, 2000 Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '17','name' => 'Salle D67, 2e étage','adresse' => 'Av. du 1er-Mars 26, 2000 Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '18','name' => 'Université de Neuchâtel, bâtiment principal','adresse' => 'Av. du 1er-Mars 26, Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '19','name' => 'Université de Neuchâtel','adresse' => 'Rue A.-L. Breguet 1','url' => '','map' => 'breguet.JPG'),
			array('id' => '20','name' => 'Salle C47','adresse' => 'Av. du 1er-Mars 26, 2000 Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '21','name' => 'Lausanne, Neuchâtel et Fribourg','adresse' => '','url' => '','map' => NULL),
			array('id' => '22','name' => 'Université de Neuchâtel','adresse' => 'Av. du 1er-Mars 26, Neuchâtel','url' => '','map' => 'faculte.jpg'),
			array('id' => '23','name' => 'Genève - UNI MAIL - Salle MS150','adresse' => '<p>UNI MAIL - Salle MS150<br>40, bd du Pont-d’Arve<br>Genève</p>','url' => '','map' => 'unimail.png'),
			array('id' => '24','name' => 'Lausanne - UNIL','adresse' => '<p>UNIL</p>','url' => '','map' => 'unil.png')
		);

		// Uncomment the below to run the seeder
		DB::table('locations')->insert($locations);
	}

}
