<h2>{{ $bloc->titre }}</h2>
{!! $bloc->contenu !!}
<img width="130px" style="max-width: 130px; max-height: 220px;" src="{{ secure_asset(config('newsletter.path.categorie').$bloc->image.'') }}" />
