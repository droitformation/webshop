<strong>{{ $adresse->civilite_title }} {{ $adresse->first_name }} {{ $adresse->last_name }}</strong><br>
{!! !empty($adresse->company) ? $adresse->company.'<br>' : '' !!}
{{ $adresse->adresse }}<br>
{!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
{!! !empty($adresse->cp) ? $adresse->cp.'<br>' : '' !!}
{{ $adresse->npa }} {{ $adresse->ville }}<br>
{{ $adresse->pays->title }}