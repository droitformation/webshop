<?php

class ShopCategorieTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('shop_categories')->truncate();

		$shop_categories = array(
			array('id' => '1','title' => 'SYSTEM','image' => NULL,'parent_id' => '0','rang' => '2560','deleted_at' => NULL,'created_at' => '2009-10-08 20:51:03','updated_at' => '2009-10-08 20:51:03'),
			array('id' => '2','title' => 'Produits','image' => NULL,'parent_id' => '0','rang' => '1280','deleted_at' => NULL,'created_at' => '2009-10-08 20:53:01','updated_at' => '2009-10-14 14:06:43'),
			array('id' => '3','title' => 'Ouvrages','image' => NULL,'parent_id' => '23','rang' => '1888','deleted_at' => NULL,'created_at' => '2009-10-08 20:53:23','updated_at' => '2012-10-26 12:56:18'),
			array('id' => '4','title' => 'Droit du bail','image' => NULL,'parent_id' => '3','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2009-10-12 15:29:24','updated_at' => '2009-10-27 11:13:11'),
			array('id' => '5','title' => 'Nouveautés','image' => NULL,'parent_id' => '23','rang' => '2016','deleted_at' => NULL,'created_at' => '2009-10-13 21:02:54','updated_at' => '2012-10-26 12:56:39'),
			array('id' => '6','title' => 'CEMAJ','image' => NULL,'parent_id' => '3','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2009-10-14 13:33:33','updated_at' => '2009-10-27 11:13:15'),
			array('id' => '7','title' => 'Abrégés','image' => NULL,'parent_id' => '23','rang' => '2048','deleted_at' => NULL,'created_at' => '2009-10-14 14:05:15','updated_at' => '2012-10-26 12:56:50'),
			array('id' => '8','title' => 'RJN','image' => NULL,'parent_id' => '23','rang' => '2304','deleted_at' => NULL,'created_at' => '2009-10-14 14:05:30','updated_at' => '2012-10-26 12:58:12'),
			array('id' => '9','title' => 'Les abrégés','image' => NULL,'parent_id' => '7','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2009-10-16 15:34:58','updated_at' => '2009-10-27 11:13:07'),
			array('id' => '10','title' => 'Recueils','image' => NULL,'parent_id' => '23','rang' => '2112','deleted_at' => NULL,'created_at' => '2009-10-27 11:13:58','updated_at' => '2012-10-26 12:58:02'),
			array('id' => '11','title' => 'Mélanges','image' => NULL,'parent_id' => '23','rang' => '2176','deleted_at' => NULL,'created_at' => '2009-10-27 11:14:49','updated_at' => '2012-10-26 12:58:32'),
			array('id' => '12','title' => 'Test','image' => NULL,'parent_id' => '0','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2009-11-08 19:10:09','updated_at' => '2009-11-08 19:10:44'),
			array('id' => '13','title' => 'Test','image' => NULL,'parent_id' => '0','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2009-11-08 19:10:54','updated_at' => '2009-11-08 19:11:34'),
			array('id' => '14','title' => 'Test','image' => NULL,'parent_id' => '2','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2009-11-08 19:13:09','updated_at' => '2009-11-08 19:38:38'),
			array('id' => '15','title' => 'Bail','image' => NULL,'parent_id' => '23','rang' => '2432','deleted_at' => NULL,'created_at' => '2009-11-09 10:17:37','updated_at' => '2012-10-26 12:58:44'),
			array('id' => '16','title' => 'Divers','image' => NULL,'parent_id' => '2','rang' => '2496','deleted_at' => NULL,'created_at' => '2009-11-20 15:16:30','updated_at' => '2011-08-24 17:31:34'),
			array('id' => '17','title' => 'Thèses','image' => NULL,'parent_id' => '23','rang' => '2240','deleted_at' => NULL,'created_at' => '2010-09-01 10:21:16','updated_at' => '2012-10-26 12:57:40'),
			array('id' => '18','title' => 'Collection neuchâteloise','image' => NULL,'parent_id' => '2','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2010-09-01 13:28:08','updated_at' => '2010-09-01 13:32:08'),
			array('id' => '19','title' => 'test','image' => NULL,'parent_id' => '15','rang' => '1000000000','deleted_at' => '2016-01-07 01:00:00','created_at' => '2010-09-02 13:01:16','updated_at' => '2010-09-02 13:05:26'),
			array('id' => '20','title' => 'Offre spéciale','image' => NULL,'parent_id' => '2','rang' => '2464','deleted_at' => NULL,'created_at' => '2011-08-26 11:03:40','updated_at' => '2011-08-26 11:04:29'),
			array('id' => '21','title' => 'CEMAJ','image' => NULL,'parent_id' => '23','rang' => '160','deleted_at' => NULL,'created_at' => '2012-01-26 09:36:00','updated_at' => '2012-10-31 17:47:48'),
			array('id' => '22','title' => 'Droit de la santé','image' => NULL,'parent_id' => '23','rang' => '320','deleted_at' => NULL,'created_at' => '2012-01-26 09:52:26','updated_at' => '2012-10-31 17:48:02'),
			array('id' => '23','title' => 'Publications','image' => NULL,'parent_id' => '2','rang' => '80','deleted_at' => NULL,'created_at' => '2012-10-26 12:53:41','updated_at' => '2012-10-26 12:54:38'),
			array('id' => '24','title' => 'Les schémas','image' => NULL,'parent_id' => '23','rang' => '40','deleted_at' => NULL,'created_at' => '2014-04-07 11:49:14','updated_at' => '2014-04-07 11:49:14'),
			array('id' => '25','title' => 'Revue Droit du bail','image' => NULL,'parent_id' => '23','rang' => '0','deleted_at' => NULL,'created_at' => '2016-02-02 14:38:09','updated_at' => '2016-02-02 14:38:09')
		);

		// Uncomment the below to run the seeder
		DB::table('shop_categories')->insert($shop_categories);
	}

}
