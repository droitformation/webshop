<tr>
    <td>
        <div class="control-inline">
            <input class="styled-checkbox" name="adresses[]" id="compare_{{ $adresse->id }}" type="checkbox" value="{{ $adresse->id }}">
            <label for="compare_{{ $adresse->id }}">&nbsp;</label>
        </div>
    </td>
    <td><span class="label label-success">Adresse simple</span></td>
    <td>
        <strong>{{ isset($adresse->civilite) ? $adresse->civilite->title : '' }} {{ $adresse->first_name }} {{ $adresse->last_name }}</strong><br>
        {!! !empty($adresse->company) ? '<p>'.$adresse->company.'</p>' : ''  !!}
    </td>
    <td>{{ $adresse->email }}</td>
    <td>
        {{ $adresse->adresse }}<br>
        {!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
        {!! !empty($adresse->cp) ? $adresse->cp_trim.'<br>' : '' !!}
        {{ $adresse->npa }} {{ $adresse->ville }}<br>
        {{ isset($adresse->pays) ? $adresse->pays->title : '' }}
    </td>
    <td>
        @include('backend.deleted.partials.items', ['adresse' => $adresse])
    </td>
</tr>