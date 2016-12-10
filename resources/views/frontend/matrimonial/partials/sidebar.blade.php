<!-- Sidebar -->
<div class="sidebar-app">

        <!-- Bloc inscription newsletter -->
        @include('frontend.matrimonial.sidebar.newsletter')

        <!-- Bloc search -->
        @include('frontend.matrimonial.sidebar.search')

        @if(isset($menu_sidebar) && !$menu_sidebar->pages->isEmpty())
            @foreach($menu_sidebar->pages as $page)

                @if($page->template == 'newsletter')

                    <div class="widget clear sidebar-list">
                        @include('frontend.partials.list', ['page' => $page, 'lists' => $campagnes->where('status','envoyé')->pluck('sujet','id')])
                    </div>

                @else
                    <h5><a href="{{ url($site->slug.'/page/'.$page->slug) }}">{{ $page->menu_title }} <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
                @endif

            @endforeach
        @endif

        <!-- Bloc pages -->
        @include('frontend.matrimonial.sidebar.page')

</div><!-- End sidebar div-->
<!-- End sidebar -->