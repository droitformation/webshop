<tr {!! ($inscription->group_id ? 'class="isGoupe"' : '') !!}>
    <td>
        <strong>{{ $inscription->user->name }}</strong><br/>
        {{ $inscription->inscription_no }}
    </td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td>
        @if($inscription->doc_facture)
            <a target="_blank" href="{{ $inscription->doc_facture }}?{{ rand(1,10000) }}" class="btn btn-xs btn-default">Facture en pdf</a>
        @else
            <generate path="inscription" order="{{ $inscription->id }}"></generate>
        @endif
    </td>
    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>
        <rappel path="inscription" :rappels="{{ $inscription->rappel_list }}" item="{{ $inscription->id }}"></rappel>
    </td>
</tr>