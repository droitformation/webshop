<tr class="{{ $row->trashed() ? 'bg-warning' : '' }}">
    <td>
        <p>
            <strong>{!! $row->inscrit_account->name !!}</strong><br/>
            {!! isset($row->inscrit_account->main_adresse) ? $row->inscrit_account->main_adresse->email : '' !!}
            {!! $row->inscrit_account->main_adresse_deleted ? '<i class="text-warning fa fa-warning pull-right">&nbsp;</i>' : '' !!}
            {!! $row->inscrit_account->user_deleted ? '<i class="text-danger fa fa-warning pull-right">&nbsp;</i>' : '' !!}
        </p>

    </td>
    <td>
        @if($row->participant)
            {!! $row->participant->name  !!}
        @endif
    </td>
    <td><strong>{{ $row->inscription_no }}</strong></td>
    <td>{{ $row->price->price_cents }} CHF</td>
    <td>{{ $row->created_at->formatLocalized('%d %B %Y') }}</td>
</tr>