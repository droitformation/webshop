<?php

class PageTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('pages')->truncate();

		$pages = array(
			array('id' => '2','parent_id' => '0','_lft' => '3','_rgt' => '4','depth' => '0','title' => 'Home','content' => '','slug' => 'index','menu_title' => 'Home','rang' => '0','menu_id' => '1','hidden' => NULL,'site_id' => '2','template' => 'index','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-16 18:41:20','updated_at' => '2016-02-02 16:47:07'),
			array('id' => '3','parent_id' => '0','_lft' => '5','_rgt' => '6','depth' => '0','title' => 'Droit en vigueur','content' => '','slug' => 'lois','menu_title' => 'Lois','rang' => '1','menu_id' => '1','hidden' => NULL,'site_id' => '2','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-17 14:08:39','updated_at' => '2016-02-02 16:47:07'),
			array('id' => '4','parent_id' => '0','_lft' => '7','_rgt' => '8','depth' => '0','title' => 'Instances cantonales en matière de bail','content' => '<p>La présente page donne le lien vers les différentes autorités en matière
 de bail à loyer si celles-ci sont mentionnées par les sites cantonaux.</p>','slug' => 'autorites','menu_title' => 'Autorités','rang' => '2','menu_id' => '1','hidden' => NULL,'site_id' => '2','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-17 14:33:34','updated_at' => '2016-02-02 16:47:07'),
			array('id' => '5','parent_id' => '0','_lft' => '9','_rgt' => '10','depth' => '0','title' => 'Liens utiles','content' => '<h3>Renseignements généraux</h3><p>La brochure Le logement en Suisse est disponible en allemand, français, 
italien, albanais, anglais, croate, portugais, serbe, espagnol, tamoul 
et turc. On peut la commander gratuitement auprès de <a href="http://www.bwo.admin.ch/" target="_blank" class="external-link-new-window">l\'Office fédéral du logement</a> ou la télécharger en tapant <a href="http://www.bwo.admin.ch/" target="_blank" class="external-link-new-window">http://www.bwo.admin.ch/</a>.
 On trouvera en outre sur ce site conseils et informations 
complémentaires. La tabelle d\'amortissement paritaire pour la Suisse 
romande (commune aux associations de bailleurs et de locataires) est 
disponible en cliquant <a href="http://www.asloca.ch/sites/default/files/documents/Tabelle_avertissement.pdf" target="_blank" class="external-link-new-window">ici</a> (asloca) ou<a href="http://www.uspi.ch/fileadmin/user_upload/images/tabelle.pdf" target="_blank" class="external-link-new-window"> ici</a> (USPI).</p><h3>Sites à consulter</h3>','slug' => 'liens','menu_title' => 'Liens','rang' => '4','menu_id' => '1','hidden' => NULL,'site_id' => '2','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-17 16:28:25','updated_at' => '2016-02-02 16:47:07'),
			array('id' => '6','parent_id' => '0','_lft' => '11','_rgt' => '12','depth' => '0','title' => 'Questions / Réponses','content' => '','slug' => 'faq','menu_title' => 'FAQ','rang' => '3','menu_id' => '1','hidden' => NULL,'site_id' => '2','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-17 16:43:15','updated_at' => '2016-02-02 16:47:07'),
			array('id' => '7','parent_id' => '0','_lft' => '13','_rgt' => '14','depth' => '0','title' => 'Droit du bail à loyer - Commentaire pratique','content' => '<p><img style="float: right; margin: 0px 0px 10px 10px;" alt="" src="http://shop.local/files/droit-bail-a-loyer-01.jpg"></p><p><img alt="" style="float: right; margin: 0px 0px 10px 10px;" src="http://shop.local/files/uploads/images/droit-bail-a-loyer-01.jpg"></p><p>L’ouvrage, édité en collaboration avec le 
Séminaire sur le droit du bail de l’Université de Neuchâtel, a pour but 
de répondre rapidement et de manière concrète aux problèmes rencontrés 
dans la pratique. Une place importante est ainsi accordée aux questions 
de procédure et aux différents «pièges» qui peuvent se présenter dans 
des cas concrets. Dans un domaine juridique aussi polarisé que le droit 
du bail, les éditeurs ont souhaité publier un commentaire qui puisse 
servir d’outil de travail et de référence tant aux représentants des 
bailleurs qu’à ceux des locataires.</p><p>L’ouvrage comprend:</p><p>• un commentaire systématique et pratique des 
art. 253 ss CO ainsi que des dispositions pénales et de droit 
international privé (LDIP, CL et CLrév.) concernant le droit du bail;</p><p>• le texte légal des dispositions commentées dans les trois langues officielles avec une traduction anglaise;</p><p>• de nombreuses aides de travail intégrées au 
commentaire (schémas, tableaux, listes, exemples de formulations ou de 
calculs, check-lists, etc.);</p><p>• des annexes comprenant des textes légaux ou 
réglementaires complémentaires (notamment l’OBLF, l’ordonnance sur le 
taux hypothécaire et les contrats-cadres) ainsi que des tabelles de 
référence (taux hypothécaires, IPC, amortissement des installations).</p><p>Vous pouvez commander cet ouvrage en ligne <a href="http://www.helbing.ch/detail/ISBN-9783719026905/Aubert-Carole-Avocat-Barrelet-Muriel-Bieri-Isabelle-Bise-Michel-Avocat-Bohnet-Fran%C3%A7ois-Prof.-Dr.-iur.-LL.M.-Bouverat-David-Broquet-Julien-Avocat-Conod-Philippe-Dr.-iur.-Dietschy-Patricia-Dr.-Guillaume-Florence-Prof.-Dr.-iur.-Marchand-Sylvain-Prof.-Dr.-Montini-Marino-Montini-Michel-Avocat-Planas-Aur%C3%A9lie-Sandoz-Bastien-Wahlen-Carole-Wessner-Pierre-Prof./Commentaire-pratique-Droit-du-bail-%C3%A0-loyer" target="_blank" class="external-link-new-window">en cliquant ici</a>.</p><p><br></p>','slug' => 'commentaire-pratique','menu_title' => 'Commentaire pratique','rang' => '5','menu_id' => '2','hidden' => NULL,'site_id' => '2','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-23 17:42:11','updated_at' => '2016-02-02 16:59:19'),
			array('id' => '8','parent_id' => '0','_lft' => '15','_rgt' => '16','depth' => '0','title' => 'Bibliographie 1989 - 2014','content' => '<p>La bibliographie jointe en format pdf est tirée des volumes annuels de la <a href="http://www.bail.ch/index.php?id=97" title="Revue Droit du bail" class="internal-link">Revue Droit du bail</a> et ventilée par thèmes :</p>','slug' => 'bibliographie','menu_title' => 'Bibliographie','rang' => '4','menu_id' => '2','hidden' => NULL,'site_id' => '2','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-24 08:25:39','updated_at' => '2016-02-02 16:59:19'),
			array('id' => '9','parent_id' => '0','_lft' => '17','_rgt' => '18','depth' => '0','title' => 'Commentaire pratique','content' => '<p><img style="float: right; margin: 0px 0px 10px 10px;" data-thumb="http://shop.local/files/uploads/images/commentaire_pratique.jpg" src="http://shop.local/files/uploads/images/commentaire_pratique.jpg" alt="commentaire_pratique.jpg"></p><p>Ce nouveau commentaire a pour but de 
répondre rapidement et de manière concrète aux problèmes rencontrés dans
 la pratique du droit matrimonial.<br><br>Chaque disposition du droit 
matrimonial de fond et de procédure est analysée de manière efficiente 
et détaillée. Les aspects complexes de droit international privé, qui 
ont pris une place grandissante dans la pratique, sont également traités
 dans une annexe. Il en va de même des questions relevant des assurances
 sociales et du droit fiscal.<br><br>Nouveau volume des Commentaires 
pratiques, ce titre est fidèle à l’esprit de la série qui allie niveau 
scientifique et efficacité.<br><br>L’ouvrage est édité en collaboration avec la Newsletter DroitMatrimonial.ch de la faculté de droit de l’Université de Neuchâtel.
</p><p><strong>Contenu</strong></p><ul><li>Commentaire détaillé des dispositions du Code civil sur le droit matrimonial</li><li>Commentaire détaillé des dispositions du Code de procédure civile sur la procédure de droit matrimonial</li><li>Partie consacrée au droit international privé en matière matrimoniale</li><li>Partie consacrée aux aspects du droit des assurances sociales en matière matrimoniale</li><li>Partie consacrée à la problématique fiscale en matière matrimoniale</li><li>Bibliographie fouillée pour chaque partie</li></ul><p>Vous pouvez commander cet ouvrage en ligne <a href="http://www.helbing.ch/detail/ISBN-9783719032166/Commentaire-pratique-Droit-matrimonial?bpmarid=&amp;bpmlang=fr" target="_blank" class="external-link-new-window">en cliquant ici</a>.</p>','slug' => 'commentaire-pratique','menu_title' => 'Commentaire pratique','rang' => '4','menu_id' => '3','hidden' => NULL,'site_id' => '3','template' => 'page','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-01 09:23:40','updated_at' => '2016-02-01 09:34:33'),
			array('id' => '10','parent_id' => '0','_lft' => '21','_rgt' => '22','depth' => '0','title' => 'Home','content' => '','slug' => 'index','menu_title' => 'Home','rang' => '1','menu_id' => '3','hidden' => NULL,'site_id' => '3','template' => 'index','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-16 18:41:20','updated_at' => '2016-01-16 18:41:20'),
			array('id' => '11','parent_id' => '0','_lft' => '19','_rgt' => '20','depth' => '0','title' => 'Newsletter','content' => '','slug' => 'newsletter','menu_title' => 'Newsletter','rang' => '2','menu_id' => '3','hidden' => NULL,'site_id' => '3','template' => 'newsletter','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-01 11:57:27','updated_at' => '2016-02-01 13:14:12'),
			array('id' => '12','parent_id' => '0','_lft' => '23','_rgt' => '24','depth' => '0','title' => 'Jurisprudence','content' => '','slug' => 'jurisprudence','menu_title' => 'Jurisprudence','rang' => '3','menu_id' => '3','hidden' => NULL,'site_id' => '3','template' => 'jurisprudence','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-01-16 18:41:20','updated_at' => '2016-01-16 18:41:20'),
			array('id' => '13','parent_id' => '0','_lft' => '25','_rgt' => '26','depth' => '0','title' => 'Jurisprudence','content' => '','slug' => 'jurisprudence','menu_title' => 'Jurisprudence','rang' => '1','menu_id' => '2','hidden' => NULL,'site_id' => '2','template' => 'jurisprudence','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-02 11:22:21','updated_at' => '2016-02-02 16:59:18'),
			array('id' => '14','parent_id' => '0','_lft' => '27','_rgt' => '28','depth' => '0','title' => 'Newsletter','content' => '','slug' => 'newsletter','menu_title' => 'Newsletter','rang' => '0','menu_id' => '2','hidden' => NULL,'site_id' => '2','template' => 'newsletter','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-02 11:23:41','updated_at' => '2016-02-02 16:59:18'),
			array('id' => '15','parent_id' => '0','_lft' => '29','_rgt' => '30','depth' => '0','title' => 'Articles de doctrine ','content' => '','slug' => 'doctrine','menu_title' => 'Articles de doctrine ','rang' => '2','menu_id' => '2','hidden' => NULL,'site_id' => '2','template' => 'doctrine','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-02 12:01:01','updated_at' => '2016-02-02 16:59:18'),
			array('id' => '16','parent_id' => '0','_lft' => '31','_rgt' => '32','depth' => '0','title' => 'Revue juridique sur le Droit du bail','content' => '<h3>Qu\'est-ce que la revue Droit du bail</h3><p><strong></strong></p><p><strong>Droit du bail</strong> est une revue juridique qui paraît une fois par année, en décembre. Elle est actuellement éditée par <a href="http://www.helbing.ch/" target="_blank" class="external-link-new-window">Helbing &amp; Lichtenhahn</a>. </p><p>Le
 premier numéro est paru en 1989. Dans ce domaine, cette publication est
 la seule en Suisse qui émane d\'une université. Elle comprend des 
informations bibliographiques et surtout des résumés commentés des 
principales décisions d\'autorités judiciaires, notamment du Tribunal 
fédéral. A partir de 2004, les chapeaux des décisions sont énoncés dans 
les trois langues nationales.</p><p>La liste des arrêts commentés dans Droit du 
bail,  classés selon l\'ordre chronologique de la date du jugement, peut 
être téléchargée <a href="http://www.bail.ch/fileadmin/media/pdf/db/Arrets_commentes_dans_DB_.pdf" class="download">en cliquant ici</a>.</p><p>Le comité de rédaction est composé de professeurs qui enseignent cette matière et d\'avocat(e)s qui la pratiquent régulièrement.</p><p>L\'abonnement annuel, pour un numéro, coûte CHF 35.00. Pour toute commande ou demande de renseignements, <a href="http://www.publications-droit.ch/publications/details/produits/bail/melanges-en-lhonneur-de-carlo-augusto-cannata.html" target="_blank" class="external-link-new-window">cliquer ici</a>.</p>','slug' => 'revues','menu_title' => 'Revue','rang' => '3','menu_id' => '2','hidden' => NULL,'site_id' => '2','template' => 'revue','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-02 12:01:43','updated_at' => '2016-02-02 16:59:19'),
			array('id' => '17','parent_id' => '0','_lft' => '33','_rgt' => '34','depth' => '0','title' => 'Jurisprudence','content' => '','slug' => 'jurispudence','menu_title' => 'Jurisprudence','rang' => '2','menu_id' => '4','hidden' => NULL,'site_id' => '3','template' => 'jurisprudence','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-02 17:17:56','updated_at' => '2016-02-02 17:18:55'),
			array('id' => '18','parent_id' => '0','_lft' => '35','_rgt' => '36','depth' => '0','title' => 'Newsletter','content' => '','slug' => 'newsletter','menu_title' => 'Newsletter','rang' => '1','menu_id' => '4','hidden' => NULL,'site_id' => '3','template' => 'newsletter','url' => NULL,'isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-02 17:18:19','updated_at' => '2016-02-02 17:18:39'),
			array('id' => '1','parent_id' => NULL,'_lft' => '1','_rgt' => '2','depth' => '0','title' => 'BIENVENUE sur publications-droit.ch','content' => '<p>Site des publications de la faculté de droit de l\'Université de&nbsp;Neuchâtel</p>','slug' => 'accueil','menu_title' => 'Accueil','rang' => '1','menu_id' => '5','hidden' => '1','site_id' => '1','template' => 'index','url' => '','isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-09-12 08:23:55','updated_at' => '2016-09-12 08:23:55'),
			array('id' => '19','parent_id' => '0','_lft' => '37','_rgt' => '38','depth' => '0','title' => '','content' => '','slug' => 'bachelors-en-droit','menu_title' => 'Bachelors en droit','rang' => '2','menu_id' => '5','hidden' => NULL,'site_id' => '1','template' => 'page','url' => 'http://www2.unine.ch/droit/bachelor','isExternal' => '1','deleted_at' => NULL,'created_at' => '2016-02-27 13:00:44','updated_at' => '2016-02-27 13:13:46'),
			array('id' => '20','parent_id' => '0','_lft' => '39','_rgt' => '40','depth' => '0','title' => '','content' => '','slug' => 'masters','menu_title' => 'Masters','rang' => '3','menu_id' => '5','hidden' => NULL,'site_id' => '1','template' => 'page','url' => 'http://www2.unine.ch/droit/master','isExternal' => '1','deleted_at' => NULL,'created_at' => '2016-02-27 13:17:00','updated_at' => '2016-02-27 13:17:50'),
			array('id' => '21','parent_id' => '0','_lft' => '41','_rgt' => '42','depth' => '0','title' => '','content' => '','slug' => 'casmasdas','menu_title' => 'CAS/MAS/DAS','rang' => '4','menu_id' => '5','hidden' => NULL,'site_id' => '1','template' => 'page','url' => 'http://www2.unine.ch/droit/formation_continue','isExternal' => '1','deleted_at' => NULL,'created_at' => '2016-02-27 13:17:36','updated_at' => '2016-02-27 13:17:41'),
			array('id' => '22','parent_id' => '0','_lft' => '43','_rgt' => '44','depth' => '0','title' => 'Contactez-nous','content' => '','slug' => 'contact','menu_title' => 'Contact','rang' => '5','menu_id' => '5','hidden' => '1','site_id' => '1','template' => 'contact','url' => '','isExternal' => NULL,'deleted_at' => NULL,'created_at' => '2016-02-27 17:34:19','updated_at' => '2016-02-27 18:44:02'),
		);

		// Uncomment the below to run the seeder
		DB::table('pages')->insert($pages);
	}

}
