<!-- Sidebar -->
<div class="sidebar-app">

    <!-- Bloc search -->
    @include('frontend.matrimonial.sidebar.search')

    <!-- Bloc inscription newsletter -->
    @include('frontend.matrimonial.sidebar.newsletter')

    @if( (Request::is('matrimonial/page/newsletter') || Request::is('matrimonial/page/newsletter/*')) && !$newsletters->isEmpty())
        @foreach($newsletters as $newsletter)
            @include('frontend.partials.list', ['page' => $page, 'lists' => $newsletter->campagnes_visibles->pluck('sujet','id')])
        @endforeach
    @endif

    @if(isset($menu_sidebar) && !$menu_sidebar->pages_active->isEmpty())

        <div class="widget clear">
            <h3 class="title">Liens directs</h3>
            @foreach($menu_sidebar->pages_active as $page)
                <a class="link" href="{{ url($site->slug.'/page/'.$page->slug) }}">{{ $page->menu_title }}</a>
            @endforeach
        </div>

    @endif

    <!-- Bloc pages -->
    @include('frontend.matrimonial.sidebar.page')

</div><!-- End sidebar div-->
<!-- End sidebar -->