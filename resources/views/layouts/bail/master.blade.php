<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
	<title>Bail</title>
	
	<meta name="description" content="Bail">
	<meta name="author" content="Cindy Leschaud | @DesignPond">

    <!-- CSS Files
    ================================================== -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo asset('css/jquery-ui.min.css'); ?>" type="text/css"  />
	<link rel="stylesheet" type="text/css" href="<?php echo asset('backend/css/chosen.css');?>">
	<link rel="stylesheet" href="<?php echo asset('css/bail/main.css');?>">
	
    <!-- Javascript Files
    ================================================== -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
	<script src="<?php echo asset('backend/js/validation/messages_fr.js');?>"></script>
	<script type="text/javascript" src="<?php echo asset('backend/js/vendor/chosen/chosen.jquery.js');?>"></script>
    <script src="<?php echo asset('js/bail/main.js');?>"></script>
    <script src="<?php echo asset('js/bail/bail.js');?>"></script>
    <script src="<?php echo asset('js/bail/arrets.js');?>"></script>
    <script src="<?php echo asset('js/bail/seminaires.js');?>"></script>
	<base id="base" href="http://pubdroit.local/bail/" />
	</head>
	<body>
        <div id="main" class="container">
            <div class="row">
                <!-- Contenu principal -->
                <div class="maincontent col-md-9">

                    <!-- Entête et menu -->
                    <header id="header" class="header">
                        <div class="row">
                            <h1 class="col-md-3"><a class="" href=""><img src="{{ asset('/images/bail/logo.png') }}" alt="Logo Bail.ch"></a></h1>
                            <nav class="col-md-9" id="menu-principal">

                                <a class="{{ Request::is('bail') ? 'active' : '' }}" href="{{ url('bail') }}">Home</a>
                                <a class="{{ Request::is('bail/loi') ? 'active' : '' }}" href="{{ url('bail/lois') }}">Lois</a>
                                <a class="{{ Request::is('bail/autorites') ? 'active' : '' }}" href="{{ url('bail/autorites') }}">Autorités</a>
                                <a class="{{ Request::is('bail/liens') ? 'active' : '' }}" href="{{ url('bail/liens') }}">Liens utiles</a>
                                <a class="{{ Request::is('bail/faq') ? 'noborder active' : 'noborder' }}" href="{{ url('bail/faq') }}">FAQ</a>

                            </nav>
                        </div>
                    </header>

                    <!-- Fil d'ariane -->
                    <section id="breadcrumbs" class="colorBlock min-inner colorSection">Home <a href=""> &gt; Newsletter</a></section>

                    <!-- Contenu -->

                    @yield('content')

                    <!-- Fin contenu -->

                    <footer class="colorBlock inner">
                        © 2013 - bail.ch<br/>
                        Université de Neuchâtel, Faculté de droit, Av. du 1er mars 26, 2000 Neuchâtel<br/>
                        <a href="mailto:seminaire.bail@unine.ch">seminaire.bail@unine.ch</a>
                    </footer>

                </div>
                <!-- Fin contenu principal -->

                <!-- Sidebar -->
                <div class="sidebar col-md-3">

                    <!--Logo unine -->

                    <p class="min-inner header text-right"> <a href="http://www.unine.ch" target="_blank"><img src="{{ asset('/images/bail/unine.png') }}" alt=""></a></p>

                    <!-- Bloc recherche -->

                    <div class="colorBlock min-inner colorSection searchBg">
                       <form action="bail/search'" method="POST" class="searchform">
                           <input type="text" name="q" placeholder="Recherche...">
                           <button type="submit" class="">ok</button>
                       </form>
                    </div>

                    <!-- Bloc archives newsletter -->

                    <div class="upMarge">
                        <div id="rightmenu">

                            <h4 class="accordion"><a href="#"><span>Newsletter</span></a></h4>
                                <div class="newsletterMenu accordionContent">
                                    <ul class="menu">
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

                                                <?php

                                                    if(!empty($bacategories)){
                                                        foreach($bacategories as $bacategorie)
                                                        {
                                                            echo '<option value="c'.$bacategorie['id'].'">'.$bacategorie['title'].'</option>';
                                                        }
                                                    }

                                                ?>

                                            </select>
                                        </div>
                                        <h6>Par année</h6>
                                        <ul id="arret-annees" class="list annees clear">
                                            <?php

                                                if(!empty($bayears)){
                                                    foreach($bayears as $bayear){
                                                        echo '<li><a rel="y'.$bayear.'" href="#">Paru en '.$bayear.'</a></li>';
                                                    }
                                                }

                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <h4 class="accordionPart seminaire"><a href="{{ url('bail/doctrine') }}" title="Articles de doctrine"><span>Articles de doctrine</span></a></h4>
                                <div class="accordionContentPart accordionContent seminaire">

                                    <div class="filtre">
                                        <h6>Par catégorie</h6>
                                        <div class="list categories clear">

                                            <select id="seminaire-chosen" class="seminaire-chosen chosen-select category" multiple data-placeholder="Filtrer par catégorie..." name="filter">
                                                <?php

                                                    if(!empty($bscategories)){
                                                        foreach($bscategories as $bscategorie)
                                                        {
                                                            echo '<option value="c'.$bscategorie['id'].'">'.$bscategorie['title'].'</option>';
                                                        }
                                                    }

                                                ?>
                                            </select>

                                        </div>
                                        <h6>Par année</h6>
                                        <ul id="seminaireannees" class="list annees clear">
                                            <?php

                                                if(!empty($bsyears)){
                                                    foreach($bsyears as $id => $bsyear){
                                                        echo '<li><a rel="y'.$id.'" href="#">Paru en '.$bsyear.'</a></li>';
                                                    }
                                                }

                                            ?>
                                        </ul>
                                        <h6>Par auteur</h6>
                                        <div class="list auteurs clear">
                                            <select class="seminaire-chosen chosen-select author"  multiple data-placeholder="Filtrer par auteur..." name="filter">
                                                <?php

                                                    if(!empty($authors))
                                                    {
                                                        $auteurs = $authors->lists('name','id');
                                                        foreach($auteurs as $id => $auteur)
                                                        {
                                                            echo '<option value="a'.$id.'">'.$auteur.'</option>';
                                                        }
                                                    }

                                                ?>
                                            </select>
                                        </div>
                                </div>
                            </div>
                            <h4 class="accordion"><a href="#"><span>Revues</span></a></h4>
                                <div class="revueMenu accordionContent">
                                    <ul class="menu">
                                        <li><a href="index.php?id=97#DB1989" >DB 1/1989</a></li>
                                        <li><a href="index.php?id=97#DB1990" >DB 2/1990</a></li>
                                        <li><a href="index.php?id=97#DB1991" >DB 3/1991</a></li>
                                        <li><a href="index.php?id=97#DB1992" >DB 4/1992</a></li>
                                        <li><a href="index.php?id=97#DB1993" >DB 5/1993</a></li>
                                        <li><a href="index.php?id=97#DB1994" >DB 6/1994</a></li>
                                        <li><a href="index.php?id=97#DB1995" >DB 7/1995</a></li>
                                        <li><a href="index.php?id=97#DB1996" >DB 8/1996</a></li>
                                        <li><a href="index.php?id=97#DB1997" >DB 9/1997</a></li>
                                        <li><a href="index.php?id=97#DB1998" >DB 10/1998</a></li>
                                        <li><a href="index.php?id=97#DB1999" >DB 11/1999</a></li>
                                        <li><a href="index.php?id=97#DB2000" >DB 12/2000</a></li>
                                        <li><a href="index.php?id=97#DB2001" >DB 13/2001</a></li>
                                    </ul>
                                </div>
                            <h4><a href="{{ url('bail/bibliographie') }}" title="Bibliographie"><span>Bibliographie</span></a></h4>
                            <div class="accordionContentPart"></div>
                            <h4><a href="{{ url('bail/commentaire') }}" title="Commentaire pratique"><span>Commentaire pratique</span></a></h4>
                            <div class="accordionContentPart"></div>

                        </div>
                    </div>

                    <!-- Bloc Soutiens -->

                    <h5 class="min-inner colorBlock upMarge blockTitle">Avec le soutien de</h5>
                    <div class="inner text-right">
                        <a href="http://www.helbing.ch/" target="_blank"><img src="{{ asset('/images/bail/HLV_Logo.png') }}" alt=""></a>
                    </div>

                    <!-- Bloc inscription newsletter -->
                    <h5 class="min-inner colorBlock blockTitle">Calculateur</h5>
                    <div class="inner calculator">

                        <p>Calculez les hausses et baisses de loyer en un clic</p>

                    {{--	{{ Form::open(array( 'action' => 'BailController@calcul', 'id' => 'calculette')) }}
                            {{ Form::label('Votre canton', '' ) }}
                            {{ Form::select('canton', array(
                                                            '' => 'Choisir dans la liste',
                                                            'be'=>'Berne',
                                                            'fr'=>'Fribourg',
                                                            'ge'=>'Genève',
                                                            'ju'=>'Jura',
                                                            'ne'=>'Neuchâtel',
                                                            'vs'=>'Valais',
                                                            'vd'=>'Vaud'
                                                        ) , array('id' => 'input-canton' , 'required' => 'required' )) }}

                            {{ Form::label('Votre loyer actuel (sans les charges)', '' ) }}
                            {{ Form::text('loyer', '', array('id' => 'input-loyer', 'required' => 'required')) }}

                            {{ Form::label('Date d\'entrée en vigueur de votre loyer actuel', '' ) }}
                            {{ Form::text('date', '', array('id' => 'input-datepicker', 'required' => 'required')) }}

                            {{ Form::submit('Envoyer', array('class' => 'button tiny colorBlock')) }}
                        {{ Form::close() }}
                        --}}
                        <div id="calculatorResult"></div>

                    </div>
                    <!-- Bloc inscription newsletter -->
                    <h5 class="min-inner colorBlock blockTitle">Inscription à la newsletter</h5>
            {{--    	<div class="inner">
                        {{ Form::open(array( 'action' => 'NewsletterController@add', 'class' => '')) }}
                            {{ Form::text('email', 'Votre adresse email' , array('class' => '')) }}
                            {{ Form::hidden('list_id', '2') }}
                            {{ Form::submit('Inscription', array('class' => 'button tiny colorBlock')) }}
                        {{ Form::close() }}
                    </div>
                               --}}
                </div>

                <!-- End sidebar -->
            </div>
	    </div>
    	
	</body>
</html>