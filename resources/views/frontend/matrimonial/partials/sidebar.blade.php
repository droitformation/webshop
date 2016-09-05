<!-- Sidebar -->
<div class="col-md-3">
    <div class="sidebar"><!-- Start sidebar div-->

        <!--Logo unine -->
        <div class="sidebar-bloc">
            <p class="text-right">
                <a href="http://www.unine.ch" target="_blank">
                    <img height="60" src="{{ asset('/logos/unine-matrimonial.svg') }}" alt="">
                </a>
            </p>
        </div>
        <!-- Bloc recherche -->

        <!-- sidebar -->
        <div id="mainSidebar">

            <!-- Bloc inscription newsletter -->
            <div class="color-bloc">
                <h4>Inscription à la newsletter</h4>
                @foreach($newsletters as $newsletter)
                    @include('newsletter::Frontend.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'matrimonial'])
                @endforeach
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

            @if(!$menus->isEmpty())
                <?php $menu = $menus->where('position','sidebar')->sortBy('rang'); ?>
                @if(!$menu->isEmpty())
                    <?php $menu = $menu->first()->load('pages'); ?>
                    @if(!$menu->pages->isEmpty())
                        @foreach($menu->pages as $page)

                            @if($page->template == 'newsletter')
                                @include('frontend.partials.list', ['page' => $page, 'lists' => $campagnes->where('status','envoyé')->pluck('sujet','id')])

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

            @if(!$page->blocs->isEmpty())
                @foreach($page->blocs as $bloc)
                    <div class="sidebar-content-bloc">
                        @include('frontend.partials.bloc', ['bloc' => $bloc])
                    </div>
                @endforeach
            @endif

        </div>
        <!-- End main sidebar -->


    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->