@if($adresse)
    <ul id="user">
        {!! (!empty($adresse->company) ? '<li>'.$adresse->company.'</li>' : '') !!}
        <li>{!! $adresse->civilite_title.' '.$adresse->name !!}</li>
        <li>{{ $adresse->adresse }}</li>
        {!! (!empty($adresse->complement) ? '<li>'.$adresse->complement.'</li>' : '') !!}
        {!! (!empty($adresse->cp) ? '<li>'.$adresse->cp_trim.'</li>' : '') !!}
        <li>{{ $adresse->npa.' '.$adresse->ville }}</li>
    </ul>
@endif