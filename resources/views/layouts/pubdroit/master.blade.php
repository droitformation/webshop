<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
<head>
    <meta charset="utf-8" />
	<title>Publications droit</title>
	
	<meta name="description" content="Publications droit">
	<meta name="author" content="Cindy Leschaud">
	<meta name="viewport" content="width=device-width">
	
    <!-- CSS Files
    ================================================== -->
	<link rel="stylesheet" href="<?php echo asset('css/foundation.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/normalize.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/style.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/pubdroit/main.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/pubdroit/vendor/produits.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/pubdroit/vendor/select2.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/smoothness/jquery-ui-1.10.3.custom.css'); ?>" />	

    <!-- Javascript Files
    ================================================== -->        
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo asset('js/jquery-ui.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/json2.js');?>"></script>  
    <script src="<?php echo asset('js/pubdroit/jquery.isotope.min.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/jquery.tinyscrollbar.min.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/vendor/select2.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/main.js');?>"></script>

    <script src="<?php echo asset('js/pubdroit/vendor/produits.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/vendor/menu.js');?>"></script>    
    <script src="<?php echo asset('js/pubdroit/vendor/jquery.stickem.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/vendor/spin.min.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/vendor/sammy.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/vendor/swfobject.js');?>"></script>
    <script src="<?php echo asset('js/pubdroit/vendor/produits.js');?>"></script>

	</head>
	<body>
        <div id="container" class="container">
        
	        <header id="header">
	        
	        	<!-- Entête -->
	        	<div id="entete">
			 	 	<div class="logo colorBlock">
				 	 	<a href="">{{HTML::image('/images/pubdroit/logo.png')}}</a>
			 	 	</div>
			 	 	<div class="illustration">
				 	 	{{HTML::image('/images/pubdroit/headerImg.jpg')}}
			 	 	</div>
			 	 	<div class="master colorBlock text-right">
				 	 	<a href="http://www2.unine.ch/droit/masters" target="_blank">SEMESTRE DE PRINTEMPS<br/> nos masters</a>
			 	 	</div>
			 	 	<div class="profileBlock text-right">
				 	 	<a href="index.php?id=254&amp;return_url=5&amp;type=110" class="loginBtn" title="login">login</a><br/>
				 	 	<a href="index.php?id=59&amp;type=110" class="newsletterBtn" title="newsletter">newsletter</a>
			 	 	</div>
			 	</div>
	        	
		        <div id="menu">
		        	<div class="row">
			        	<!-- Menu -->
						<nav id="menu-principal" class="large-9 columns">
							{{ link_to('pubdroit', 'Publications' , array('class' => Request::is( 'pubdroit') ? 'active' : '') ) }}
							{{ link_to('pubdroit', 'Offre spéciale' , array('class' => Request::is( 'pubdroit/offres') ? 'active' : '') ) }}
							{{ link_to('pubdroit', 'Divers' , array('class' => Request::is( 'pubdroit/divers') ? 'active' : '') ) }}
							{{ link_to('pubdroit/event', 'Evénements' , array('class' => Request::is( 'pubdroit/event') ? 'active' : '') ) }}
						</nav>
						<div class="large-3 columns">						
						    <div class="searchform" id="search">
						        {{ Form::text('search', '' ,array('class' => 'search') ) }}
								{{ Form::submit('ok', array('class' => '') ) }}
							</div>					    
					    </div>
				    </div>		    
		        </div>
				
				<nav id="subMenu">
					<ul id="filters">
						<li>
							<div class="reset select2-container">
							     <a href="javascript:void(0)" class="reset select2-choice select2-default">Réinitialiser</a>
							</div>
						</li>
						<li> 
							<select id="col" class="select2 filters-categories" style="width:150px;">
								<option></option>
								<option data-filter="abreges" value="abreges">Abrégés</option>
								<option data-filter="bail" value="bail">Bail</option>
								<option data-filter="cemaj" value="cemaj">CEMAJ</option>
								<option data-filter="droit_de_la_sante" value="droit_de_la_sante">Droit de la santé</option>
								<option data-filter="melanges" value="melanges">Mélanges</option>
								<option data-filter="nouveautes" value="nouveautes">Nouveautés</option>
								<option data-filter="ouvrages" value="ouvrages">Ouvrages</option>
								<option data-filter="recueils" value="recueils">Recueils</option>
								<option data-filter="rjn" value="rjn">RJN</option>
								<option data-filter="theses" value="theses">Thèses</option>
							</select>
						</li>
						<li> 
							<select id="aut" class="select2 filters-authors" style="width:150px;">
								<option></option>
								<option data-filter="leonor_ackerhernandez" value="leonor_ackerhernandez">Leonor Acker-Hernández</option>
								<option data-filter="vanessa_alarcon_duvanel" value="vanessa_alarcon_duvanel">Vanessa Alarcón Duvanel</option>
								<option data-filter="raphael_allimann" value="raphael_allimann">Raphaël Allimann</option>
								<option data-filter="cesla_amarelle" value="cesla_amarelle">Cesla Amarelle</option>
								<option data-filter="laura_amey" value="laura_amey">Laura Amey</option>
								<option data-filter="alain_barbezat" value="alain_barbezat">Alain Barbezat</option>
								<option data-filter="anne_benoit" value="anne_benoit">Anne Benoit</option>
								<option data-filter="laurent_bieri" value="laurent_bieri">Laurent Bieri</option>
								<option data-filter="francois_bohnet" value="francois_bohnet">François Bohnet</option>
								<option data-filter="bohnetwessner_edit" value="bohnetwessner_edit"> Bohnet-Wessner (édit.)</option>
								<option data-filter="fanny_brossard" value="fanny_brossard">Fanny Brossard</option>
								<option data-filter="stephane_brumann" value="stephane_brumann">Stéphane Brumann</option>
								<option data-filter="nicolas_brugger" value="nicolas_brugger">Nicolas Brügger</option>
								<option data-filter="nicolas_bueno" value="nicolas_bueno">Nicolas Bueno</option>
								<option data-filter="sabrina_burgat" value="sabrina_burgat">Sabrina Burgat</option>
								<option data-filter="theodor_buhler" value="theodor_buhler">Theodor Bühler</option>
								<option data-filter="blaise_carron" value="blaise_carron">Blaise Carron</option>
								<option data-filter="alison_carty" value="alison_carty">Alison Carty</option>
								<option data-filter="alain_chablais" value="alain_chablais">Alain Chablais</option>
								<option data-filter="olivier_chappuis" value="olivier_chappuis">Olivier Chappuis</option>
								<option data-filter="jeanluc_chenaux" value="jeanluc_chenaux">Jean-Luc Chenaux</option>
								<option data-filter="nathalie_christen" value="nathalie_christen">Nathalie Christen</option>
								<option data-filter="rachel_christinat" value="rachel_christinat">Rachel Christinat</option>
								<option data-filter="evelyne_clerc" value="evelyne_clerc">Evelyne Clerc</option>
								<option data-filter="marlene_collette" value="marlene_collette">Marlène Collette</option>
								<option data-filter="vincent_corpataux" value="vincent_corpataux">Vincent Corpataux</option>
								<option data-filter="sibilla_giselda_cretti" value="sibilla_giselda_cretti">Sibilla Giselda Cretti</option>
								<option data-filter="robert_j_danon" value="robert_j_danon">Robert J. Danon</option>
								<option data-filter="daniel_de_vries_reilingh" value="daniel_de_vries_reilingh">Daniel de Vries Reilingh</option>
								<option data-filter="beatrice_despland" value="beatrice_despland">Béatrice Despland</option>
								<option data-filter="marie_deveaudpledran" value="marie_deveaudpledran">Marie Deveaud-Plédran</option>
								<option data-filter="patricia_dietschy" value="patricia_dietschy">Patricia Dietschy</option>
								<option data-filter="zachary_douglas" value="zachary_douglas">Zachary Douglas</option>
								<option data-filter="gaetan_droz" value="gaetan_droz">Gaétan Droz</option>
								<option data-filter="julien_dubois" value="julien_dubois">Julien Dubois</option>
								<option data-filter="camille_dubois" value="camille_dubois">Camille Dubois</option>
								<option data-filter="lorraine_ducommun" value="lorraine_ducommun">Lorraine Ducommun</option>
								<option data-filter="jeanphilippe_dunand" value="jeanphilippe_dunand">Jean-Philippe Dunand</option>
								<option data-filter="annesylvie_dupont" value="annesylvie_dupont">Anne-Sylvie Dupont</option>
								<option data-filter="bastien_durel" value="bastien_durel">Bastien Durel</option>
								<option data-filter="bernd_ehle" value="bernd_ehle">Bernd Ehle</option>
								<option data-filter="ursula_elsener" value="ursula_elsener">Ursula Elsener</option>
								<option data-filter="alice_fadda" value="alice_fadda">Alice Fadda</option>
								<option data-filter="brigitte_fasel" value="brigitte_fasel">Brigitte Fasel</option>
								<option data-filter="yvan_fauchere" value="yvan_fauchere">Yvan Fauchère</option>
								<option data-filter="daniele_favalli" value="daniele_favalli">Daniele Favalli</option>
								<option data-filter="manon_fellrath" value="manon_fellrath">Manon Fellrath</option>
								<option data-filter="yann_ferolles" value="yann_ferolles">Yann Férolles</option>
								<option data-filter="ludivine_ferreira" value="ludivine_ferreira">Ludivine Ferreira</option>
								<option data-filter="christian_flueckiger" value="christian_flueckiger">Christian Flueckiger</option>
								<option data-filter="alejandro_follonierayala" value="alejandro_follonierayala">Alejandro Follonier-Ayala</option>
								<option data-filter="leila_ghassemi" value="leila_ghassemi">Leila Ghassemi</option>
								<option data-filter="olivier_gonin" value="olivier_gonin">Olivier Gonin</option>
								<option data-filter="luc_gonin" value="luc_gonin">Luc Gonin</option>
								<option data-filter="josiane_grand" value="josiane_grand">Josiane Grand</option>
								<option data-filter="florence_guillaume" value="florence_guillaume">Florence Guillaume</option>
								<option data-filter="olivier_guillod" value="olivier_guillod">Olivier Guillod</option>
								<option data-filter="guillodmuller_edit" value="guillodmuller_edit"> Guillod-Müller (édit.)</option>
								<option data-filter="ch_guyecabert" value="ch_guyecabert">Ch. Guy-Ecabert</option>
								<option data-filter="ulrich_haas" value="ulrich_haas">Ulrich Haas</option>
								<option data-filter="philipp_habegger" value="philipp_habegger">Philipp Habegger</option>
								<option data-filter="yann_hafner" value="yann_hafner">Yann Hafner</option>
								<option data-filter="olivier_hari" value="olivier_hari">Olivier Hari</option>
								<option data-filter="erika_hasler" value="erika_hasler">Erika Hasler</option>
								<option data-filter="yasemin_hazinedar" value="yasemin_hazinedar">Yasemin Hazinedar</option>
								<option data-filter="jan_heiner_nedden" value="jan_heiner_nedden">Jan Heiner Nedden</option>
								<option data-filter="agnes_hertigpea" value="agnes_hertigpea">Agnès Hertig-Pea</option>
								<option data-filter="christoph_hurni" value="christoph_hurni">Christoph Hurni</option>
								<option data-filter="lino_hanni" value="lino_hanni">Lino Hänni</option>
								<option data-filter="eloi_jeannerat" value="eloi_jeannerat">Eloi Jeannerat</option>
								<option data-filter="yvan_jeanneret" value="yvan_jeanneret">Yvan Jeanneret</option>
								<option data-filter="sylvaingeorges_kablan" value="sylvaingeorges_kablan">Sylvain-Georges Kablan</option>
								<option data-filter="daniel_kraus" value="daniel_kraus">Daniel Kraus</option>
								<option data-filter="andre_kuhn" value="andre_kuhn">André Kuhn</option>
								<option data-filter="fanny_kunz" value="fanny_kunz">Fanny Kunz</option>
								<option data-filter="stefanie_tamara_kurt" value="stefanie_tamara_kurt">Stefanie Tamara Kurt</option>
								<option data-filter="la_boite" value="la_boite"> La boîte</option>
								<option data-filter="leslie_la_sala" value="leslie_la_sala">Leslie La Sala</option>
								<option data-filter="guy_longchamp" value="guy_longchamp">Guy Longchamp</option>
								<option data-filter="melanie_mader" value="melanie_mader">Mélanie Mader</option>
								<option data-filter="melissa_magliana" value="melissa_magliana">Melissa Magliana</option>
								<option data-filter="pascal_mahon" value="pascal_mahon">Pascal Mahon</option>
								<option data-filter="pascal_mahon" value="pascal_mahon">Pascal Mahon</option>
								<option data-filter="mahonnguyen_edit" value="mahonnguyen_edit"> Mahon-Nguyen (édit.)</option>
								<option data-filter="sylvain_marchand" value="sylvain_marchand">Sylvain Marchand</option>
								<option data-filter="vincent_martenet" value="vincent_martenet">Vincent Martenet</option>
								<option data-filter="natassia_martinez" value="natassia_martinez">Natassia Martinez</option>
								<option data-filter="estelle_mathiszwygart" value="estelle_mathiszwygart">Estelle Mathis-Zwygart</option>
								<option data-filter="fanny_matthey" value="fanny_matthey">Fanny Matthey</option>
								<option data-filter="valerie_maurer" value="valerie_maurer">Valérie Maurer</option>
								<option data-filter="sylvain_metille" value="sylvain_metille">Sylvain Métille</option>
								<option data-filter="vincent_mignon" value="vincent_mignon">Vincent Mignon</option>
								<option data-filter="m_montini" value="m_montini">M. Montini</option>
								<option data-filter="mike_morgan" value="mike_morgan">Mike Morgan</option>
								<option data-filter="christoph_mueller" value="christoph_mueller">Christoph Mueller</option>
								<option data-filter="christoph_muller" value="christoph_muller">Christoph Müller</option>
								<option data-filter="nour_ahmad_nazim" value="nour_ahmad_nazim">Nour Ahmad Nazim</option>
								<option data-filter="minh_son_nguyen" value="minh_son_nguyen">Minh Son Nguyen</option>
								<option data-filter="jeanluc_niklaus" value="jeanluc_niklaus">Jean-Luc Niklaus</option>
								<option data-filter="thierry_obrist" value="thierry_obrist">Thierry Obrist</option>
								<option data-filter="denis_oswald" value="denis_oswald">Denis Oswald</option>
								<option data-filter="p_zenruffinen_edit" value="p_zenruffinen_edit"> P. Zen-Ruffinen (édit.)</option>
								<option data-filter="alexandre_papaux" value="alexandre_papaux">Alexandre Papaux</option>
								<option data-filter="nadia_pascale" value="nadia_pascale">Nadia Pascale</option>
								<option data-filter="nicolas_pellaton" value="nicolas_pellaton">Nicolas Pellaton</option>
								<option data-filter="benedicte_pessotto" value="benedicte_pessotto">Bénédicte Pessotto</option>
								<option data-filter="aurelie_planas" value="aurelie_planas">Aurélie Planas</option>
								<option data-filter="bernhard_pulver" value="bernhard_pulver">Bernhard Pulver</option>
								<option data-filter="joelle_racine" value="joelle_racine">Joëlle Racine</option>
								<option data-filter="daniel_de_vries_reilingh" value="daniel_de_vries_reilingh">Daniel de Vries Reilingh</option>
								<option data-filter="valentin_retornaz" value="valentin_retornaz">Valentin Rétornaz</option>
								<option data-filter="antonio_rigozzi" value="antonio_rigozzi">Antonio Rigozzi</option>
								<option data-filter="olivier_riske" value="olivier_riske">Olivier Riske</option>
								<option data-filter="david_p_roney" value="david_p_roney">David P. Roney</option>
								<option data-filter="heloise_rosello" value="heloise_rosello">Héloïse Rosello</option>
								<option data-filter="friedrich_rosenfeld" value="friedrich_rosenfeld">Friedrich Rosenfeld</option>
								<option data-filter="charlotte_rossat" value="charlotte_rossat">Charlotte Rossat</option>
								<option data-filter="laurie_roth" value="laurie_roth">Laurie Roth</option>
								<option data-filter="nicolas_rouiller" value="nicolas_rouiller">Nicolas Rouiller</option>
								<option data-filter="pierreemmanuel_ruedin" value="pierreemmanuel_ruedin">Pierre-Emmanuel Ruedin</option>
								<option data-filter="jessica_salom" value="jessica_salom">Jessica Salom</option>
								<option data-filter="vincent_salvade" value="vincent_salvade">Vincent Salvadé</option>
								<option data-filter="roxane_schaller" value="roxane_schaller">Roxane Schaller</option>
								<option data-filter="roxane_schmidgall" value="roxane_schmidgall">Roxane Schmidgall</option>
								<option data-filter="manon_simeoni" value="manon_simeoni">Manon Simeoni</option>
								<option data-filter="dieyla_sow" value="dieyla_sow">Dieyla Sow</option>
								<option data-filter="dominique_sprumont" value="dominique_sprumont">Dominique Sprumont</option>
								<option data-filter="beatrice_stirner" value="beatrice_stirner">Beatrice Stirner</option>
								<option data-filter="alain_thevenaz" value="alain_thevenaz">Alain Thévenaz</option>
								<option data-filter="m_tissot" value="m_tissot">M. Tissot</option>
								<option data-filter="nathalie_tissot" value="nathalie_tissot">Nathalie Tissot</option>
								<option data-filter="micael_totaro" value="micael_totaro">Micael Totaro</option>
								<option data-filter="celine_tritten_helbling" value="celine_tritten_helbling">Céline Tritten Helbling</option>
								<option data-filter="nicholas_turin" value="nicholas_turin">Nicholas Turin</option>
								<option data-filter="melanie_van_leeuwen" value="melanie_van_leeuwen">Melanie van Leeuwen</option>
								<option data-filter="mari_viro_moser" value="mari_viro_moser">Mari Viro Moser</option>
								<option data-filter="katherine_von_der_weid" value="katherine_von_der_weid">Katherine von der Weid</option>
								<option data-filter="sophie_weerts" value="sophie_weerts">Sophie Weerts</option>
								<option data-filter="pierre_wessner" value="pierre_wessner">Pierre Wessner</option>
								<option data-filter="valerie_wyssbrod" value="valerie_wyssbrod">Valérie Wyssbrod</option>
								<option data-filter="piermarco_zenruffinen" value="piermarco_zenruffinen">Piermarco Zen-Ruffinen</option>
								<option data-filter="zenruffinenauer_edit" value="zenruffinenauer_edit"> Zen-Ruffinen/Auer (édit.)</option>
								<option data-filter="tobias_zuberbuhler" value="tobias_zuberbuhler">Tobias Zuberbühler</option>
								<option data-filter="estelle_mathis_zwygart" value="estelle_mathis_zwygart">Estelle Mathis Zwygart</option>
							</select>
						</li>
						<li> 
							<select id="dom" class="select2 filters-themes" style="width: 150px;">
								<option></option>
								<option data-filter="arbitrage" value="arbitrage">Arbitrage</option>
								<option data-filter="assurances_sociales" value="assurances_sociales">Assurances sociales</option>
								<option data-filter="circulation_routiere" value="circulation_routiere">Circulation routière</option>
								<option data-filter="criminologie" value="criminologie">Criminologie</option>
								<option data-filter="doctrine" value="doctrine">Doctrine</option>
								<option data-filter="droit_administratif" value="droit_administratif">Droit administratif</option>
								<option data-filter="droit_civil_compare" value="droit_civil_compare">Droit civil comparé</option>
								<option data-filter="droit_commercial" value="droit_commercial">Droit commercial</option>
								<option data-filter="droit_constitutionnel" value="droit_constitutionnel">Droit constitutionnel</option>
								<option data-filter="droit_de_lavocat" value="droit_de_lavocat">Droit de l'avocat</option>
								<option data-filter="droit_de_linternet" value="droit_de_linternet">Droit de l'Internet</option>
								<option data-filter="droit_de_la_concurrence" value="droit_de_la_concurrence">Droit de la concurrence</option>
								<option data-filter="droit_de_la_consommation_et_de_la_distribution" value="droit_de_la_consommation_et_de_la_distribution">
								Droit de la consommation et de la distribution</option>
								<option data-filter="droit_de_la_famille" value="droit_de_la_famille">Droit de la famille</option>
								<option data-filter="droit_de_la_sante" value="droit_de_la_sante">Droit de la santé</option>
								<option data-filter="droit_des_assurances" value="droit_des_assurances">Droit des assurances</option>
								<option data-filter="droit_des_contrats" value="droit_des_contrats">Droit des contrats</option>
								<option data-filter="droit_des_marches_publics_renvoi" value="droit_des_marches_publics_renvoi">Droit des marchés publics (renvoi)</option>
								<option data-filter="droit_des_migrations" value="droit_des_migrations">Droit des migrations</option>
								<option data-filter="droit_des_obligations_et_des_contrats" value="droit_des_obligations_et_des_contrats">Droit des obligations et des contrats</option>
								<option data-filter="droit_des_papiersvaleurs" value="droit_des_papiersvaleurs">Droit des papiers-valeurs</option>
								<option data-filter="droit_des_personnes" value="droit_des_personnes">Droit des personnes</option>
								<option data-filter="droit_des_societes" value="droit_des_societes">Droit des sociétés</option>
								<option data-filter="droit_des_successions" value="droit_des_successions">Droit des successions</option>
								<option data-filter="droit_du_bail" value="droit_du_bail">Droit du bail</option>
								<option data-filter="droit_du_juge" value="droit_du_juge">Droit du juge</option>
								<option data-filter="droit_du_notaire" value="droit_du_notaire">Droit du notaire</option>
								<option data-filter="droit_du_sport" value="droit_du_sport">Droit du sport</option>
								<option data-filter="droit_du_travail" value="droit_du_travail">Droit du travail</option>
								<option data-filter="droit_fiscal" value="droit_fiscal">Droit fiscal</option>
								<option data-filter="droit_international_prive" value="droit_international_prive">Droit international privé</option>
								<option data-filter="droit_international_public" value="droit_international_public">Droit international public</option>
								<option data-filter="droit_penal" value="droit_penal">Droit pénal</option>
								<option data-filter="droit_romain" value="droit_romain">Droit romain</option>
								<option data-filter="droit_social" value="droit_social">Droit social</option>
								<option data-filter="droits_reels" value="droits_reels">Droits réels</option>
								<option data-filter="execution_forcee" value="execution_forcee">Exécution forcée</option>
								<option data-filter="jurisprudence" value="jurisprudence">Jurisprudence</option>
								<option data-filter="le_droit_de_replique" value="le_droit_de_replique">Le droit de réplique</option>
								<option data-filter="legislation" value="legislation">Législation</option>
								<option data-filter="materiel" value="materiel">Matériel</option>
								<option data-filter="mediation" value="mediation">Médiation</option>
								<option data-filter="pocedure_penale" value="pocedure_penale">Pocédure pénale</option>
								<option data-filter="poursuites_et_faillites" value="poursuites_et_faillites">Poursuites et faillites</option>
								<option data-filter="procedure_administrative" value="procedure_administrative">Procédure administrative</option>
								<option data-filter="procedure_civile" value="procedure_civile">Procédure civile</option>
								<option data-filter="procedure_penale" value="procedure_penale">Procédure pénale</option>
								<option data-filter="propriete_intellectuelle" value="propriete_intellectuelle">Propriété intellectuelle</option>
								<option data-filter="protection_des_donnees" value="protection_des_donnees">Protection des données</option>
								<option data-filter="responsabilite_civile" value="responsabilite_civile">Responsabilité civile</option>
							</select>
						</li>
					</ul>
				</nav>
				
		    </header>  
      	
	      	<!-- Contenu -->
	      	
            @yield('content')
            
            <!-- Fin contenu -->
	            
	    </div>
    	
	</body>
</html>