<strong>{{ $user->adresse_contact->civilite->title }} {{ $user->adresse_contact->first_name }} {{ $user->adresse_contact->last_name }}</strong><br>
{!! !empty($user->adresse_contact->company) ? $user->adresse_contact->company.'<br>' : '' !!}
{{ $user->adresse_contact->adresse }}<br>
{!! !empty($user->adresse_contact->complement) ? $user->adresse_contact->complement.'<br>' : '' !!}
{!! !empty($user->adresse_contact->cp) ? $user->adresse_contact->cp.'<br>' : '' !!}
{{ $user->adresse_contact->npa }} {{ $user->adresse_contact->ville }}<br>
{{ $user->adresse_contact->pays->title }}