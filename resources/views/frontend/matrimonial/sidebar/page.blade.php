@if(isset($page) && !$page->blocs->isEmpty())
    @foreach($page->blocs as $bloc)
        <div class="widget clear">
            @include('frontend.partials.bloc', ['bloc' => $bloc])
        </div>
    @endforeach
@endif