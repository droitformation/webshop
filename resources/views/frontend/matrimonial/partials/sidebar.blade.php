<!-- Sidebar -->
<div class="sidebar-app">

    <!-- Bloc search -->
    @include('frontend.matrimonial.sidebar.search')

    <!-- Bloc inscription newsletter -->
    @include('frontend.matrimonial.sidebar.newsletter')

    @if(isset($menu_sidebar) && !$menu_sidebar->pages->isEmpty())
        @foreach($menu_sidebar->pages as $page)

            @if($page->template == 'newsletter')
                @include('frontend.partials.list', ['page' => $page, 'lists' => $campagnes->where('status','envoyé')->pluck('sujet','id')])
            @else
                <h5><a href="{{ url($site->slug.'/page/'.$page->slug) }}">{{ $page->menu_title }} <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
            @endif

        @endforeach
    @endif

    <!-- Bloc pages -->
    @include('frontend.matrimonial.sidebar.page')

</div><!-- End sidebar div-->
<!-- End sidebar -->