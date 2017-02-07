<!-- Sidebar -->
<div class="sidebar-app">

    @if(!Request::is('bail/page/index'))
        <!-- Bloc search -->
        {{--@include('frontend.bail.sidebar.search')--}}

        <!-- Bloc inscription newsletter -->
        @include('frontend.bail.sidebar.newsletter')
    @endif

    @if( (Request::is('bail/page/newsletter') || Request::is('bail/page/newsletter/*')) && !$newsletters->isEmpty())
        @include('frontend.partials.list', ['page' => $page, 'lists' => $newsletters->first()->campagnes_visibles->pluck('sujet','id')])
    @endif

    @if(Request::is('bail/page/revues') || Request::is('bail/page/revues/*'))
        @include('frontend.partials.list', ['page' => $page, 'lists' => $revues])
    @endif

    @if(Request::is('bail/page/doctrine'))
        @include('frontend.bail.sidebar.doctrine')
    @endif

    @if(isset($menu_sidebar) && !$menu_sidebar->pages_active->isEmpty())
        <div class="widget clear">
            <h3 class="title">Liens directs</h3>
            @foreach($menu_sidebar->pages_active as $active)
                <a class="link" href="{{ url($site->slug.'/page/'.$active->slug) }}">{{ $active->menu_title }}</a>
            @endforeach
        </div>
    @endif

    <!-- Bloc pages -->
    @include('frontend.bail.sidebar.page')

    <!-- Bloc inscription newsletter -->
    @include('frontend.bail.sidebar.calculette')

</div><!-- End sidebar div-->
<!-- End sidebar -->