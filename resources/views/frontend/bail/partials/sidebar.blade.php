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

        <!-- sidebar -->
        <div id="mainSidebar">

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
                @if( Request::is('bail/jurisprudence') )
                    <div id="masterFilter"><!--END jusriprudence-->

                        <div class="widget list categories clear">
                            <h3 class="title"><i class="icon-tasks"></i> &nbsp;Catégories</h3>

                            @if(!empty($categories))
                                <select id="arret-chosen" name="category_check" data-placeholder="Choisir une ou plusieurs catégories" style="width:100%" multiple class="chosen-select category">
                                    @foreach($categories as $categorie)
                                        <option value="c{{ $categorie->id }}">{{ $categorie->title }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div><!--END WIDGET-->

                        <div class="widget list annees clear">
                            <h3 class="title"><i class="icon-calendar"></i> &nbsp;Années</h3>
                            @if(!empty($years))
                                <ul id="arret-annees" class="list annees clear">
                                    @foreach($years as $year)
                                        <li><a rel="y{{ $year }}" href="#">Paru en {{ $year }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div><!--END WIDGET-->

                    </div><!--END jusriprudence-->
                @endif

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

                @if(!$menus->isEmpty())
                    <?php $menu = $menus->whereLoose('position','sidebar'); ?>
                    @if(!$menu->isEmpty())
                        <?php $menu = $menu->first()->load('pages'); ?>
                        @if(!$menu->pages->isEmpty())
                            @foreach($menu->pages as $page)
                                <h5><a href="{{ url('bail/page/'.$page->slug) }}">{{ $page->menu_title }} <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                            @endforeach
                        @endif
                    @endif
                @endif

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

                <form action="{{ url('bail/calcul') }}" id="calculette">
                    <label>Votre canton</label>
                    <select class="form-control" name="canton" required id="input-canton">
                        <option value="">Choix</option>
                        @foreach($faqcantons as $canton_id => $canton)
                            <option value="{{ $canton_id }}">{{ $canton }}</option>
                        @endforeach
                    </select>

                    <label>Votre loyer actuel (sans les charges)</label>
                    <input type="text" class="form-control" name="loyer" id="input-loyer" required>

                    <label>Date d\'entrée en vigueur de votre loyer actuel</label>
                    <input type="text" class="form-control" name="date" id="input-datepicker" required>
                    <br>
                    <p><button class="btn btn-danger btn-sm" type="submit">Calculer</button></p>
                </form>

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

        </div><!-- End main sidebar -->

    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->