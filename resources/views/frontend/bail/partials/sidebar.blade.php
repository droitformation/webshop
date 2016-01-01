<!-- Sidebar -->
<div class="col-md-3">
    <div class="sidebar"><!-- Start sidebar div-->

        <!--Logo unine -->
        <div class="sidebar-bloc header">
            <p class="text-right"><a href="http://www.unine.ch" target="_blank"><img src="{{ asset('/images/bail/unine.png') }}" alt=""></a></p>
        </div>
        <!-- Bloc recherche -->

        <div class="color-bloc form-bloc">
            <form action="bail/search'" method="POST" class="searchform">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Recherche...">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="button"><i class="fa fa-search text-danger"></i></button>
                    </span>
                </div>
            </form>
        </div>

        <!-- Bloc archives newsletter -->

        <div id="rightmenu">

            <h5><a href="#" data-toggle="collapse" href="#newsletterList">Newsletter <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
            <div class="collapse" id="newsletterList">
                <ul class="menu">
                    <li><a href="index.php?id=108&amp;uid=364">Newsletter décembre 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=357">Newsletter novembre 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=354">Newsletter octobre 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=349">Newsletter septembre 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=344">Newsletter août 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=343">Newsletter juillet 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=338">Newsletter juin 2013</a></li>
                    <li><a href="index.php?id=108&amp;uid=330">Newsletter mai 2013</a></li>
                </ul>
            </div>

            <h5><a href="{{ url('bail/jurisprudence') }}">Jurisprudence <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
            <div class="jurisprudence">
                <div class="filtre">
                    <h6>Par catégorie</h6>
                    <div class="list categories clear">
                        <select id="arret-chosen" class="chosen-select category" multiple data-placeholder="Filtrer par catégorie..." name="filter">
                            <?php
                                if(!empty($categories)){
                                    foreach($categories as $categorie)
                                    {
                                        echo '<option value="c'.$categorie->id.'">'.$categorie->title.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <h6>Par année</h6>
                    <ul id="arret-annees" class="list annees clear">
                        <?php
                            if(!empty($years)){
                                foreach($years as $year){
                                    echo '<li><a rel="y'.$year.'" href="#">Paru en '.$year.'</a></li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <h5><a href="{{ url('bail/doctrine') }}" title="Articles de doctrine">Articles de doctrine <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
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
                                if(!$authors->isEmpty())
                                {
                                    foreach($authors as $author)
                                    {
                                        echo '<option value="a'.$author->id.'">'.$author->name.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <h5><a href="#" data-toggle="collapse" href="#revueList">Revues <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
            <div class="collapse" id="revueList">
                <ul class="menu">
                    <li><a href="index.php?id=97#DB1989">DB 1/1989</a></li>
                    <li><a href="index.php?id=97#DB1990">DB 2/1990</a></li>
                    <li><a href="index.php?id=97#DB1991">DB 3/1991</a></li>
                    <li><a href="index.php?id=97#DB1992">DB 4/1992</a></li>
                    <li><a href="index.php?id=97#DB1993">DB 5/1993</a></li>
                    <li><a href="index.php?id=97#DB1994">DB 6/1994</a></li>
                    <li><a href="index.php?id=97#DB1995">DB 7/1995</a></li>
                    <li><a href="index.php?id=97#DB1996">DB 8/1996</a></li>
                    <li><a href="index.php?id=97#DB1997">DB 9/1997</a></li>
                    <li><a href="index.php?id=97#DB1998">DB 10/1998</a></li>
                    <li><a href="index.php?id=97#DB1999">DB 11/1999</a></li>
                    <li><a href="index.php?id=97#DB2000">DB 12/2000</a></li>
                    <li><a href="index.php?id=97#DB2001">DB 13/2001</a></li>
                </ul>
            </div>

            <h5><a href="{{ url('bail/bibliographie') }}" title="Bibliographie">Bibliographie <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
            <h5><a href="{{ url('bail/commentaire') }}" title="Commentaire pratique">Commentaire pratique <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>

        </div>

        <!-- Bloc Soutiens -->
        <h5 class="color-bloc">Avec le soutien de</h5>
        <div class="sidebar-bloc">
            <a href="http://www.helbing.ch/" target="_blank"><img src="{{ asset('/images/bail/HLV_Logo.png') }}" alt=""></a>
        </div>

        <!-- Bloc inscription newsletter -->
        <h5 class="color-bloc">Calculateur</h5>
        <div class="sidebar-bloc calculator">

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
        <h5 class="color-bloc">Inscription à la newsletter</h5>
        <div class="sidebar-bloc">

            <form method="post" action="{{ url('bail/subscribe') }}">
                <input type="hidden" name="list_id" value="2">
                <div class="input-group">
                    <input name="email" type="email" class="form-control" placeholder="Votre adresse email">
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="submit">Inscription</button>
                    </span>
                </div>
            </form>
        </div>

    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->