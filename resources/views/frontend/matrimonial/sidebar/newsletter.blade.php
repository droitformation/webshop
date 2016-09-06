@if(!$newsletters->isEmpty())
    <div class="color-bloc">
        <h4>Inscription à la newsletter</h4>
        @foreach($newsletters as $newsletter)
            @include('newsletter::Frontend.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'matrimonial'])
        @endforeach
    </div>
@endif