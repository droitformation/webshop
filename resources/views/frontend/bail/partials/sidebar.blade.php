<!-- Sidebar -->
<div class="col-md-3">
    <div class="sidebar"><!-- Start sidebar div-->

        <!--Logo unine -->
        <div class="sidebar-bloc header">
            <p class="text-right"><a href="http://www.unine.ch" target="_blank"><img src="{{ asset('/images/bail/unine.png') }}" alt=""></a></p>
        </div>
        <!-- Bloc recherche -->

        <div class="color-bloc form-bloc">
            <form action="bail/search" method="POST" class="searchform">
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

                <h5><a href="{{ url('bail/page/doctrine') }}" title="Articles de doctrine">Articles de doctrine <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                @if( Request::is('bail/page/doctrine') )
                <div class="seminaire">

                    <div class="filtre">
                        <h6>Par catégorie</h6>
                        <div class="list categories clear selectList">
                            <select style="width: 230px;" id="seminaire-chosen" class="seminaire-chosen chosen-select category" multiple data-placeholder="Filtrer par catégorie..." name="filter">
                                <?php
                                    if(!empty($order)){
                                        foreach($order as $key => $categorie)
                                        {
                                            echo '<option value="c'.$key.'">'.ucfirst($categorie).'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <h6>Par année</h6>
                        <ul id="seminaireannees" class="list annees clear">
                            <?php
                                if(!empty($annees))
                                {
                                    foreach($annees as $annee)
                                    {
                                        echo '<li><a rel="y'.$annee.'" href="#">Paru en '.$annee.'</a></li>';
                                    }
                                }
                            ?>
                        </ul>
                        <h6>Par auteur</h6>
                        <div class="list auteurs clear selectList">
                            <select style="width: 230px;" class="seminaire-chosen chosen-select author"  multiple data-placeholder="Filtrer par auteur..." name="filter">
                                <?php
                                    if(!$auteurs->isEmpty())
                                    {
                                        foreach($auteurs as $key => $author)
                                        {
                                            echo '<option value="a'.$key.'">'.$author.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                @if(!$menus->isEmpty())
                    <?php $menu = $menus->whereLoose('position','sidebar')->sortBy('rang'); ?>
                    @if(!$menu->isEmpty())
                        <?php $menu = $menu->first()->load('pages'); ?>
                        @if(!$menu->pages->isEmpty())
                            @foreach($menu->pages as $page)

                                @if($page->template == 'newsletter')
                                    @include('frontend.partials.list', ['page' => $page, 'lists' => $newsletters->first()->campagnes->pluck('sujet','id')])

                                @elseif($page->template == 'revue')
                                    @include('frontend.partials.list', ['page' => $page, 'lists' => $revues])

                                @elseif($page->template == 'jurisprudence')

                                    <h5><a href="{{ url($site->slug.'/page/jurisprudence') }}">Jurisprudence <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                                    @if( Request::is($site->slug.'/page/jurisprudence') )
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
                                @else
                                    <h5><a href="{{ url($site->slug.'/page/'.$page->slug) }}">{{ $page->menu_title }} <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                                @endif

                            @endforeach
                        @endif
                    @endif
                @endif

            </div>

            <!-- Bloc Soutiens -->
            @if(!$page->blocs->isEmpty())
                @foreach($page->blocs as $bloc)
                    <div class="sidebar-content-bloc">
                        @include('frontend.partials.bloc', ['bloc' => $bloc])
                    </div>
                @endforeach
            @endif

            <!-- Bloc inscription newsletter -->
            <h5 class="color-bloc">Calculateur</h5>
            <div class="sidebar-bloc calculator">

                <p>Calculez les hausses et baisses de loyer en un clic</p>

                <form action="{{ url('bail/calcul') }}" id="calculette">
                    {!! csrf_field() !!}
                    <label>Votre canton</label>
                    <select class="form-control" name="canton" id="input-canton" required>
                        <option value="">Choix</option>
                        @foreach($faqcantons as $canton_id => $canton)
                            <option value="{{ $canton_id }}">{{ $canton }}</option>
                        @endforeach
                    </select>

                    <label>Votre loyer actuel (sans les charges)</label>
                    <input type="text" class="form-control" name="loyer" id="input-loyer" required>

                    <label>Date d'entrée en vigueur de votre loyer actuel</label>
                    <input type="text" class="form-control datepicker" name="date" id="input-datepicker" required>
                    <br>
                    <p><button class="btn btn-danger btn-sm" type="submit">Calculer</button></p>
                </form>

                <div id="calculatorResult"></div>

            </div>

            <!-- Bloc inscription newsletter -->
            <h5 class="color-bloc">Inscription à la newsletter</h5>
            <div class="sidebar-bloc">

                @foreach($newsletters as $newsletter)
                    @include('newsletter::Frontend.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'bail'])
                @endforeach

            </div>

        </div><!-- End main sidebar -->

    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->