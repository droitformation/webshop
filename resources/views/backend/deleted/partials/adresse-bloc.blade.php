<div class="{{ $adresse->trashed() ? 'isTrashed' : 'isNotTrashed' }}">
    <p><strong>{{ $adresse->name }}</strong></p>
    <p><i>{{ $adresse->email }}</i></p>
    <p>{{ $adresse->adresse }}</p>
    {!! !empty($adresse->complement) ? '<p>'.$adresse->complement.'</p>' : '' !!}
    {!! !empty($adresse->cp) ? '<p>'.$adresse->cp_trim.'</p>' : '' !!}
    <p>{{ $adresse->npa }} {{ $adresse->ville }}</p>
    {!! isset($adresse->pays) ? '<p>'.$adresse->pays->title.'</p>' : '' !!}
</div>