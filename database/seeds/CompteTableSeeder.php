<?php

class CompteTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('comptes')->truncate();

		$comptes = array(
			array('id' => '1','motif' => '<p>U. 00908<br> FoCo avocats 2015</p>','adresse' => '<p>Université de Neuchâtel <br /> Service des fonds de tiers <br /> 2000 Neuchâtel</p>','compte' => '20-4130-2'),
			array('id' => '3','motif' => '<p>Journée PPE - 25 septembre 2015</p>','adresse' => '<p>Université de Neuchâtel<br />Séminaire sur le droit du bail<br />2000 Neuchâtel</p>','compte' => '20-5711-2'),
			array('id' => '4','motif' => '<p>18e Séminaire sur le droit du bail<br>17 &amp; 18 octobre 2014 (2e édition)</p>','adresse' => '<p>Université de Neuchâtel<br />Séminaire sur le droit du bail<br />2000 Neuchâtel</p>','compte' => '20-5711-2'),
			array('id' => '5','motif' => '<p>U.01262<br>CEMAJ - GEMME 6.05.2015<br></p','adresse' => '<p>Université de Neuchâtel<br />Service des fonds de tiers<br />2000 Neuchâtel</p>','compte' => '20-4130-2'),
			array('id' => '6','motif' => '<p>U.01952 CDM<br>Pratiques en droit des migrations<br></p>','adresse' => '<p>Université de Neuchâtel<br />Service des fonds de tiers<br />2000 Neuchâtel</p>','compte' => '20-4130-2'),
			array('id' => '7','motif' => '<p>U.01262<br>Colloque Arbitrage 13.11.2015</p>','adresse' => '<p>Université de Neuchâtel<br />Service des fonds de tiers<br />2000 Neuchâtel </p>','compte' => '20-4130-2'),
			array('id' => '9','motif' => '<p>U.00888 Journ&eacute;e droit de la sant&eacute;</p>','adresse' => '<p>Universit&eacute; de Neuch&acirc;tel<br />Service des Fonds de tiers<br />2000 Neuch&acirc;tel</p>','compte' => '20-4130-2'),
			array('id' => '10','motif' => '<p>U.02165<br />Cycle Conf&eacute;rences - droit des migrations</p>','adresse' => '<p>Universit&eacute; de Neuch&acirc;tel<br />Service des fonds de tiers<br />2000 Neuch&acirc;tel</p>','compte' => '20-4130-2'),
			array('id' => '12','motif' => '<p>U.02196<br />CNA [PI]<sup>2<br /><br /></sup></p>','adresse' => '<p>Universit&eacute; de Neuch&acirc;tel<br />Service des fonds de tiers<br />2000 Neuch&acirc;tel</p>','compte' => '20-4130-2'),
			array('id' => '15','motif' => '<p>U.01777<br>CIDECR - colloque 19 juin </p>','adresse' => '<p>Université de Neuchâtel<br />Service des fonds de tiers<br />2000 Neuchâtel</p>','compte' => '20-4130-2'),
			array('id' => '16','motif' => '<p>U.01419<br>FDIS CERT - colloque du 01 &amp; 02.10.2015<br></p>','adresse' => '<p>Université de Neuchâtel<br />Service des fonds de tiers<br />2000 Neuchâtel</p>','compte' => '20-4130-2'),
			array('id' => '17','motif' => '<p>U.02280<br />Colloque gest. d&eacute;chets en droit interna.</p>','adresse' => '<p>Universit&eacute; de Neuch&acirc;tel<br />Service des fonds de tiers<br />2000 Neuch&acirc;tel</p>','compte' => '20-4130-2'),
			array('id' => '18','motif' => '<p>U.02410<br>Colloque "fiscalité immobilière"</p>','adresse' => '<p>Université de Neuchâtel<br />Service des fonds de tiers<br />2000 Neuchâtel</p>','compte' => '20-4130-2'),
			array('id' => '19','motif' => '<p>U.02358<br>Colloques - O. Hari<br></p>','adresse' => '<p>Université de Neuchâtel<br>Service des fonds de tiers<br>2000 Neuchâtel<br></p>','compte' => '20-4130-2'),
			array('id' => '20','motif' => '<p>CNA U.00698<br>Journée du 24 mars 2015<br></p>','adresse' => '<p>Université de Neuchâtel<br>Service des fonds de tiers<br>2000 Neuchâtel<br></p>','compte' => '20-4130-2'),
			array('id' => '21','motif' => '<p>U.01578<br>RJN - Matinée du 23 avril 2015<br></p>','adresse' => '<p>Université de Neuchâtel <br> Service des fonds de tiers <br> 2000 Neuchâtel<br></p>','compte' => '20-4130-2'),
			array('id' => '22','motif' => '<p>U.00836 gestion des conflits - colloque LAT<br></p>','adresse' => '<p>Université de Neuchâtel <br>Service des fonds de tiers <br>2000 Neuchâtel<br></p>','compte' => '20-4130-2'),
			array('id' => '23','motif' => '<p>U.00836 gestion des conflits - Colloque S\'écouter 30.10.2015<br></p>','adresse' => '<p>Université de Neuchâtel<br>Service des fonds de tiers<br>2000 Neuchâtel<br></p>','compte' => '20-4130-2')
		);

		// Uncomment the below to run the seeder
		DB::table('comptes')->insert($comptes);
	}

}
