{!! !empty(trim($adresse->civilite_title)) ? $adresse->civilite_title.'<br>' : '' !!}

{{ !empty($adresse->first_name) ? $adresse->first_name : '' }} {{ !empty($adresse->last_name) ? $adresse->last_name : '' }}
{!! !empty($adresse->first_name) || !empty($adresse->last_name) ? '<br>' : '' !!}

{!! !empty($adresse->company) ? $adresse->company.'<br>' : '' !!}
{!! $adresse->adresse !!}<br>
{!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
{!! !empty($adresse->cp) ? 'Case postale '.$adresse->cp.'<br>' : '' !!}
{{ $adresse->npa }} {{ $adresse->ville }}<br>
{{ $adresse->pays->id != 208 ? $adresse->pays->title : '' }}