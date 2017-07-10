<tr class="{{ $row->trashed() ? 'bg-warning' : '' }}">
    <td>
        @if($row->order_adresse)
            {{ $row->order_adresse->name}}
        @elseif($row->order_adresse_deleted)
            {{ $row->order_adresse_deleted->name }}
        @else
            Admin
        @endif
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