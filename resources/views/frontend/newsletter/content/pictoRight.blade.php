<div class="row">
    <div class="col-md-9">
        <div class="bloc-content">
            <h2>{{ $bloc->titre }}</h2>
            {!! $bloc->contenu !!}
        </div><!--END POST-->
    </div>
    <div class="col-md-3">
        <a target="_blank" href="{{ isset($bloc->lien) && !empty($bloc->lien) ? $bloc->link_or_url : url('/') }}">
            <img width="130px" style="max-width: 130px; max-height: 220px;" alt="{{ $bloc->titre ?? '' }}" src="{{ secure_asset(config('newsletter.path.categorie').$bloc->image.'') }}" />
        </a>
    </div>
</div>
