<tr>
    <td>
        {{ $row->order_account->name }}
        {!! $row->order_account->main_adresse_deleted ? '<i class="text-warning fa fa-warning pull-right">&nbsp;</i>' : '' !!}
        {!! $row->order_account->user_deleted ? '<i class="text-danger fa fa-warning pull-right">&nbsp;</i>' : '' !!}
    </td>
    <td>
        @if($row->facture)
            <a target="_blank" href="{{ $row->facture }}?{{ rand(1,10000) }}" class="btn btn-xs btn-default">Facture en pdf</a>
        @else
            <a target="_blank" href="{{ url('admin/invoice/'.$row->id) }}" class="btn btn-xs btn-default">Facture en pdf</a>
        @endif
    </td>
    <td><strong>{{ $row->order_no }}</strong></td>
    <td>{{ $row->total_with_shipping }} CHF</td>
    <td>{{ $row->created_at->formatLocalized('%d %B %Y') }}</td>
</tr>