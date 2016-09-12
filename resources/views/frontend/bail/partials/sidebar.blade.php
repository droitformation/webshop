<!-- Sidebar -->
<div class="col-md-3">
    <div class="sidebar"><!-- Start sidebar div-->

        <!--Logo unine -->
        @include('frontend.bail.sidebar.logo')

        <!-- Bloc search -->
        @include('frontend.bail.sidebar.search')

        <!-- sidebar -->
        <div id="mainSidebar">

            <!-- Bloc archives newsletter -->

            <div id="rightmenu">

                @if(isset($menu_sidebar) && !$menu_sidebar->pages->isEmpty())
                    @foreach($menu_sidebar->pages as $page)

                        @if($page->template == 'newsletter' && !$newsletters->isEmpty())
                            @include('frontend.partials.list', ['page' => $page, 'lists' => $newsletters->first()->campagnes->pluck('sujet','id')])

                        @elseif($page->template == 'revue')
                            @include('frontend.partials.list', ['page' => $page, 'lists' => $revues])

                        @elseif($page->template == 'jurisprudence')
                            @include('frontend.bail.sidebar.jurisprudence')

                        @elseif($page->template == 'doctrine')
                            @include('frontend.bail.sidebar.doctrine')

                        @else
                            <h5><a href="{{ url($site->slug.'/page/'.$page->slug) }}">{{ $page->menu_title }} <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                        @endif

                    @endforeach
                @endif
            </div>

            <!-- Bloc pages -->
            @include('frontend.bail.sidebar.page')

            <!-- Bloc inscription newsletter -->
            @include('frontend.bail.sidebar.calculette')

            <!-- Bloc inscription newsletter -->
            @include('frontend.bail.sidebar.newsletter')

        </div><!-- End main sidebar -->

    </div><!-- End sidebar div-->
</div>

<!-- End sidebar -->