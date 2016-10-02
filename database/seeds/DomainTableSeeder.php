<?php

class DomainTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('domains')->truncate();

		$domains = array(
			array('id' => '1','title' => 'Droit du bail','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '2','title' => 'Procédure civile','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '3','title' => 'Droit des obligations et des contrats','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '4','title' => 'Droit des successions','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '5','title' => 'Droit du travail','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '6','title' => 'Droit de la santé','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '7','title' => 'Droit commercial','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '8','title' => 'Droit fiscal','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '9','title' => 'Droit pénal','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '10','title' => 'Arbitrage','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '11','title' => 'Droit de la famille','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '12','title' => 'Droits réels','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '13','title' => 'Droit administratif','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '14','title' => 'Droit de l\'avocat','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '15','title' => 'Droit du notaire','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '16','title' => 'Médiation','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '17','title' => 'Procédure pénale','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '18','title' => 'Exécution forcée','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '19','title' => 'Droit international privé','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '20','title' => 'Droit constitutionnel','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '21','title' => 'Droit international public','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '22','title' => 'Droit de la concurrence','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '23','title' => 'Droit des assurances','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '24','title' => 'Matériel','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '25','title' => 'Droit des personnes','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '26','title' => 'Droit du juge','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '27','title' => 'Droit du sport','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '28','title' => 'Protection des données','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '29','title' => 'Propriété intellectuelle','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '30','title' => 'Droit romain','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '31','title' => 'Droit des papiers-valeurs','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '32','title' => 'Droit social','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '33','title' => 'Pocédure pénale','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '34','title' => 'Droit des migrations','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '35','title' => 'Procédure administrative','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '36','title' => 'Criminologie','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '37','title' => 'Droit civil comparé','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '38','title' => 'Droit des sociétés','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '39','title' => 'Législation','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '40','title' => 'Doctrine','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '41','title' => 'Jurisprudence','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '42','title' => 'Droit de l\'Internet','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '43','title' => 'Assurances sociales','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '44','title' => 'Circulation routière','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '45','title' => 'Droit des marchés publics (renvoi)','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '46','title' => 'Droit de la consommation et de la distribution','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '47','title' => 'Responsabilité civile','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '48','title' => 'Droit des contrats','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '49','title' => 'Poursuites et faillites','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '50','title' => 'Le droit de réplique','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '51','title' => 'Droit suisse','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '52','title' => 'Droit français','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '53','title' => 'Convention européenne des droits de l\'homme','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '54','title' => 'Droit des étrangers','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '55','title' => 'Droit privé comparé','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '56','title' => 'Histoire du droit','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '57','title' => 'Procédures en droit public','type' => '0','hidden' => '0','deleted_at' => NULL),
			array('id' => '58','title' => 'Droit du travail et de la fonction publique','type' => '0','hidden' => '0','deleted_at' => NULL)
		);


		// Uncomment the below to run the seeder
		DB::table('domains')->insert($domains);
	}

}
