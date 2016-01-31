<!-- Sidebar -->
<div class="col-md-3">
    <div class="sidebar"><!-- Start sidebar div-->

        <!--Logo unine -->
        <div class="sidebar-bloc">
            <p class="text-right"><a href="http://www.unine.ch" target="_blank"><img src="{{ asset('/images/matrimonial/unine.png') }}" alt=""></a></p>
        </div>
        <!-- Bloc recherche -->

        <!-- sidebar -->
        <div id="mainSidebar">

            <!-- Bloc inscription newsletter -->
            <div class="color-bloc">
                <h5>Inscription à la newsletter</h5>
                <form method="post" action="{{ url('bail/subscribe') }}">
                    <input type="hidden" name="list_id" value="2">
                    <div class="input-group">
                        <input name="email" type="email" class="form-control" placeholder="Votre adresse email">
                    <span class="input-group-btn">
                        <button class="btn btn-inverse" type="submit">Inscription</button>
                    </span>
                    </div>
                </form>
            </div>

            <div class="form-bloc">
                <form action="matrimonial/search" method="POST" class="searchform">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Recherche...">
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="button"><i class="fa fa-search text-danger"></i></button>
                        </span>
                    </div>
                </form>
            </div>

            <div class="color-bloc">
                <h5><a href="#" aria-controls="newslettersLists" data-toggle="collapse" href="#newslettersLists">Newsletter <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                <div class="collapse" id="newslettersLists">
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
            </div>
            <div class="color-bloc">
                <h5><a href="{{ url('bail/jurisprudence') }}">Jurisprudence <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                @if( Request::is('bail/jurisprudence') )
                    <div id="masterFilter"><!--END jusriprudence-->

                        <div class="widget list categories clear">
                            <h3 class="title"><i class="icon-tasks"></i> &nbsp;Catégories</h3>
                            @if(!$categories->isEmpty())
                                <select id="arret-chosen" name="chosen-select" data-placeholder="Choisir une ou plusieurs catégories" style="width:100%" multiple class="chosen-select category">
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
            </div>

            @if(!$menus->isEmpty())
                <?php $menu = $menus->whereLoose('position','sidebar')->sortBy('rang'); ?>
                @if(!$menu->isEmpty())
                    <?php $menu = $menu->first()->load('pages'); ?>
                    @if(!$menu->pages->isEmpty())
                        @foreach($menu->pages as $page)
                            <h5><a href="{{ url('matrimonial/page/'.$page->slug) }}">{{ $page->menu_title }} <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                        @endforeach
                    @endif
                @endif
            @endif
        </div>
        <!-- End main sidebar -->

    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->