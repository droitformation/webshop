<!-- Sidebar -->
<div class="col-md-3">
    <div class="sidebar"><!-- Start sidebar div-->

        @include('frontend.matrimonial.sidebar.logo')

        <!-- sidebar -->
        <div id="mainSidebar">

            <!-- Bloc inscription newsletter -->
            @include('frontend.matrimonial.sidebar.newsletter')

            <!-- Bloc search -->
            @include('frontend.matrimonial.sidebar.search')

            @if(isset($menu_sidebar) && !$menu_sidebar->pages->isEmpty())
                @foreach($menu_sidebar->pages as $page)

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

            <!-- Bloc pages -->
            @include('frontend.matrimonial.sidebar.page')

        </div>
        <!-- End main sidebar -->

    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->