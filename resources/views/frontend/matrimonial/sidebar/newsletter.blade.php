@if(!$newsletters->isEmpty())
    <div class="widget clear">
        <h3 class="title">Inscription à la newsletter</h3>
        @foreach($newsletters as $newsletter)
            @include('frontend.newsletter.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'matrimonial'])
        @endforeach
    </div>
@endif