@if(!$newsletters->isEmpty())
    <div class="widget clear">
        <h3 class="title">Inscription à la newsletter</h3>
        @foreach($newsletters as $newsletter)
            @include('frontend.newsletter.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'matrimonial'])
        @endforeach

        <p>Je souhaite me <a href="{{ url('matrimonial/unsubscribe') }}">désinscrire</a></p>
    </div>
@endif