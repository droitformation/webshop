<div class="arret">
    <img style="margin-top: 30px;" src="{{ secure_asset(config('newsletter.path.upload').$bloc->image) }}" />
    @if(!empty($bloc->titre))
        <h2>{{ $bloc->titre }}</h2>
    @endif
    {!! $bloc->clean_content !!}
</div><hr>
