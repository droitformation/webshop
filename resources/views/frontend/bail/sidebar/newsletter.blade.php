@if(!$newsletters->isEmpty())
    <h5 class="color-bloc">Inscription à la newsletter</h5>
    <div class="sidebar-bloc">
        @foreach($newsletters as $newsletter)
            @include('newsletter::Frontend.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'bail'])
        @endforeach
    </div>
@endif

