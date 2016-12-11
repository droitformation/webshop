<!-- Sidebar -->
<div class="sidebar-app">

    @if(!Request::is('bail/page/index'))
        <!-- Bloc search -->
        @include('frontend.bail.sidebar.search')

        <!-- Bloc inscription newsletter -->
        @include('frontend.bail.sidebar.newsletter')
    @endif

    @if(Request::is('bail/page/newsletter') && !$newsletters->isEmpty())
        @include('frontend.partials.list', ['page' => $page, 'lists' => $newsletters->first()->campagnes->pluck('sujet','id')])
    @endif

    @if(Request::is('bail/page/revues'))
        @include('frontend.partials.list', ['page' => $page, 'lists' => $revues])
    @endif

    @if(Request::is('bail/page/doctrine'))
        @include('frontend.bail.sidebar.doctrine')
    @endif

    @if(isset($menu_sidebar) && !$menu_sidebar->pages->isEmpty())

        <div class="widget clear">
            <h3 class="title">Liens direct</h3>
            @foreach($menu_sidebar->pages as $page)
                <a class="link" href="{{ url($site->slug.'/page/'.$page->slug) }}">{{ $page->menu_title }}</a>
            @endforeach
        </div>

    @endif

    <!-- Bloc pages -->
    @include('frontend.bail.sidebar.page')

    <!-- Bloc inscription newsletter -->
    @include('frontend.bail.sidebar.calculette')

</div><!-- End sidebar div-->
<!-- End sidebar -->