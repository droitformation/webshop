<div class="arret">
    <img style="max-width: 560px;" src="{{ secure_asset(config('newsletter.path.upload').$bloc->image) }}" />

    {{ secure_asset(config('newsletter.path.upload').$bloc->image) }}
    @if(!empty($bloc->titre))
        <h2>{{ $bloc->titre }}</h2>
    @endif
    {!! $bloc->contenu !!}

</div><hr>
