<div class="{{ $adresse->trashed() ? 'isTrashed' : 'isNotTrashed' }}" style="margin-bottom: 6px;">
    <p><strong>{{ $adresse->name }}</strong></p>
    <p><i>{{ $adresse->email }}</i></p>
    <p>{{ $adresse->adresse }}</p>
    {!! !empty($adresse->complement) ? '<p>'.$adresse->complement.'</p>' : '' !!}
    {!! !empty($adresse->cp) ? '<p>'.$adresse->cp_trim.'</p>' : '' !!}
    <p>{{ $adresse->npa }} {{ $adresse->ville }}</p>
    {!! isset($adresse->pays) ? '<p>'.$adresse->pays->title.'</p>' : '' !!}

    @if(!$adresse->specialisations->isEmpty())
        <span class="label label-default">{!! $adresse->specialisations->implode('title','</span><span class="label label-default">') !!}</span>
    @endif

    @if(!$adresse->members->isEmpty())
        <span class="label label-default">{!! $adresse->members->implode('title','</span><span class="label label-default">') !!}</span>
    @endif

</div>