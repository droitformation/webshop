<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
<head>
    <meta charset="utf-8" />
	<title>Matrimonial</title>
	
	<meta name="description" content="Droit matrimonial">
	<meta name="author" content="Cindy Leschaud">
	<meta name="viewport" content="width=device-width">

    <!-- CSS Files
    ================================================== -->
	<link rel="stylesheet" href="<?php echo asset('css/foundation.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/normalize.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/style.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/chosen.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/matrimonial/main.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/matrimonial/smoothness/jquery-ui-1.10.3.custom.css'); ?>" />	
	
    <!-- Javascript Files
    ================================================== -->        
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
    <script src="<?php echo asset('js/matrimonial/jquery-ui.js');?>"></script>
    <script src="<?php echo asset('js/jquery.validate.min.js');?>"></script>
    <script src="<?php echo asset('js/localization/messages_fr.js');?>"></script>
    <script src="<?php echo asset('js/chosen.jquery.js');?>"></script>
    <script src="<?php echo asset('js/matrimonial/main.js');?>"></script>
    <script src="<?php echo asset('js/bail/bail.js');?>"></script>
    <script src="<?php echo asset('js/bail/arrets.js');?>"></script>

	</head>
	<body>
        <div id="main" class="container">
        
        	<!-- Contenu principal -->
    		<div class="maincontent">
    			<!-- Entête et menu -->
		        <header id="header" class="inner"> 
		        	<div class="row">
		        		<h1 class="large-4 columns noPadding"><a class="" href="">{{HTML::image('/images/matrimonial/logo.png')}}</a></h1>
						<nav class="large-8 columns" id="menu-principal">
						   <a href="<?php //echo action('AppController@getIndex');?>">Home</a>
						   <a href="<?php //echo action('AppController@getIndex');?>">Newsletter</a>
						   <a href="<?php //echo action('AppController@getIndex');?>">Jurisprudence</a>
						</nav>
		        	</div>
			    </header> 
			     
			    <!-- Illustration -->
			    <section id="photo">{{HTML::image('/images/matrimonial/photo.jpg')}}</section>
			    
			    <!-- Fil d'ariane -->
		      	<section id="breadcrumbs" class="colorSection">Home <a href=""> &gt; Newsletter</a></section>
		      	
		      	<!-- Contenu -->
		      	
	            @yield('content')
	            
	            <!-- Fin contenu -->
            </div> 
            <!-- Fin contenu principal -->
            
            <!-- Sidebar --> 
            <div class="sidebar">	
            
            	<!--Logo unine --> 
            	
            	<p class="inner">{{HTML::image('/images/matrimonial/unine.png')}}</p>

            	<!-- Bloc inscription newsletter --> 
            	            	
            	<div class="colorBlock inner">            	
	            	<h5>Inscription à la newsletter</h5>
					<p>Entrez votre adresse e-mail</p>
					
					{{ Form::open(array( 'action' => 'NewsletterController@add', 'class' => '')) }}						
						{{ Form::text('email', '' , array('class' => '')) }}
						{{ Form::hidden('list_id', '2') }}
						{{ Form::submit('Valider', array('class' => 'button tiny')) }}
					{{ Form::close() }}					
            	</div>
            	
            	<!-- Bloc recherche --> 
            	
            	<div class="inner">            	
            		{{ Form::open(array( 'url' => 'matrimonial/search', 'class' => 'searchform')) }}						
				        {{ Form::text('search', '' ) }}
						{{ Form::submit('ok', array('class' => '')) }}
				    {{ Form::close() }}				     
            	</div>
            	
            	<!-- Bloc archives newsletter --> 
            	
				<div class="">
             		<div id="rightmenu">	
             		
	             		<div class="accordion">
					 		<h4 class="accordion"><a href="#"><span>Newsletter</span></a></h4>
							<div class="newsletterMenu accordionContent">
								<ul class="menu newsletter">
									<li><a href="index.php?id=108&amp;uid=364" >Newsletter décembre 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=357" >Newsletter novembre 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=354" >Newsletter octobre 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=349" >Newsletter septembre 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=344" >Newsletter août 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=343" >Newsletter juillet 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=338" >Newsletter juin 2013</a></li>
									<li><a href="index.php?id=108&amp;uid=330" >Newsletter mai 2013</a></li>
								</ul>
							</div>
							<h4 class="accordionPart jurisprudence"><a href="{{ url('bail/jurisprudence') }}" title="Jurisprudence"><span>Jurisprudence</span></a></h4>
							<div class="accordionContentPart accordionContent jurisprudence">
								<div class="filtre">
									<h6>Par catégorie</h6>
									<div class="list categories clear">
										
										<select id="arret-chosen" class="chosen-select category" multiple data-placeholder="Filtrer par catégorie..." name="filter">
											<option value="c22">Thème du mois</option>
											<option value="c23">Revenu hypothétique</option>
											<option value="c24">Arrêts commentés</option>
											<option value="c25">Mariage</option>
											<option value="c26">Etranger</option>
											<option value="c27">Liquidation du régime matrimonial</option>
											<option value="c28">Domicile conjugal</option>
											<option value="c29">Mesures protectrices</option>
											<option value="c30">Droit de garde</option>
											<option value="c31">Autorité parentale</option>
											<option value="c32">Entretien</option>
											<option value="c33">Divorce</option>
											<option value="c34">Procédure</option>
											<option value="c35">Droit de visite</option>
											<option value="c36">Avis débiteur</option>
											<option value="c37">Partage prévoyance</option>
											<option value="c38">Législation</option>
											<option value="c39">Publication prévue</option>
											<option value="c40">Garde des enfants</option>
											<option value="c41">Modification du jugement de divorce</option>
											<option value="c42">Couple non marié</option>
											<option value="c43">Thème du mois</option>
											<option value="c46">Protection de l'enfant</option>
											<option value="c47">Thème du mois</option>
											<option value="c48">Analyse</option>
											<option value="c49">Partenariat</option>
											<option value="c50">Couple</option>
											<option value="c51">Nom de famille</option>
											<option value="c52">Violence conjugale</option>
											<option value="c53">S.O.S.</option>
											<option value="c55">Séquestre</option>
											<option value="c56">Audition enfant</option>
											<option value="c57">Contrat</option>
											<option value="c58">Partage des biens</option>
											<option value="c60">NoelMatrimonial</option>
										</select>
										
									</div>
									<h6>Par année</h6>
									<ul id="arret-annees" class="list annees clear">
										<li><a rel="y2013" href="#">Paru en 2013</a></li>
										<li><a rel="y2012" href="#">Paru en 2012</a></li>
										<li><a rel="y2011" href="#">Paru en 2011</a></li>
										<li><a rel="y2010" href="#">Paru en 2010</a></li>
									</ul>
								</div>
							</div>
							
						</div>

					</div>          	
            	</div>
				
				<!-- Bloc Soutiens --> 
				 
            	<div class="colorBlock upMarge inner">
            		<h5>Avec le soutien de</h5>
            		<a href="http://www.helbing.ch/" target="_blank">{{HTML::image('/images/matrimonial/logo_helbing.png')}}</a>
            	</div>
            	
            </div>  
            
            <!-- End sidebar --> 
             
			<div class="clearall"></div>
			
			<!-- Pied de page et adresse -->
            
            <footer class="colorSection inner">
	            © 2013 - droitmatrimonial.ch<br/>
				Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
				<a href="mailto:droit.matrimonial@unine,ch">droit.matrimonial(at)unine(dot)ch</a>
            </footer>
	            
	    </div>
    	
	</body>
</html>