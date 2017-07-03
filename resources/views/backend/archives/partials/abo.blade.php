<tr>
    <td>
        {!! $row->user_adresse ? $row->user_adresse->name : '<p><span class="label label-warning">Duplicata</span></p>' !!}

        @if($row->tiers_user_id || $row->tiers_id)
            <p><strong>Tiers payant:</strong></p>
            {{ $row->user_facturation->name }}<br/>
            {!! $row->user_facturation->adresse.'<br/>'.$row->user_facturation->npa.' '.$row->user_facturation->ville !!}
        @endif
    </td>
    <td>
        <p><strong>{{ $row->abo->title }}</strong>, {{ $row->exemplaires }} exemplaire{{ $row->exemplaires > 1 ? 's' : '' }}</p>
    </td>
    <td><strong>{{ $row->numero }}</strong></td>
    <td>{{ $row->status }}</td>
    <td>{{ $row->created_at->formatLocalized('%d %B %Y') }}</td>
</tr>