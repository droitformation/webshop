{!! !empty($adresse->civilite_title) ? $adresse->civilite_title.'<br>' : '' !!}
{{ $adresse->first_name }} {{ $adresse->last_name }}<br>
{!! !empty($adresse->company) ? $adresse->company.'<br>' : '' !!}
{{ $adresse->adresse }}<br>
{!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
{!! !empty($adresse->cp) ? 'CP '.$adresse->cp.'<br>' : '' !!}
{{ $adresse->npa }} {{ $adresse->ville }}<br>
{{ $adresse->pays->id != 208 ? $adresse->pays->title : '' }}