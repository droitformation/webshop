<tr>
    <td>
        @if($row->inscrit)
            <p><strong>{!! $row->inscrit->name !!}</strong></p>
            <p>{!! $row->inscrit->email !!}</p>
        @endif
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