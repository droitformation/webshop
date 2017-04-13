<tr id="adresse_{{ $adresse->id }}" class="{{ $adresse->trashed() ? 'isTrashed' : 'isNotTrashed' }}">
    <td>
        <div class="control-inline">
            <input class="styled-checkbox" name="adresses[]" id="compare_{{ $adresse->id }}" type="checkbox" value="{{ $adresse->id }}">
            <label for="compare_{{ $adresse->id }}">&nbsp;</label>
        </div>
    </td>
    <td>
        <a target="_blank" href="{{ url('admin/adresse/'.$adresse->id) }}">
            <span class="label label-success">Adresse simple {{ $adresse->id }}</span>
        </a>
    </td>
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
    <td>
        @if($adresse->trashed())
            <button type="button" data-id="{{ $adresse->id }}" class="btn btn-xs btn-warning restoreAdresseBtn">restaurer adresse</button>
        @endif
        @if(!$adresse->trashed())
            <button type="button" data-id="{{ $adresse->id }}" class="btn btn-xs btn-danger pull-right deleteAdresseRowBtn">x</button>
        @endif
    </td>
</tr>