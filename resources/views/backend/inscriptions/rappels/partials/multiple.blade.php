<tr>
    <td>
        @if($group->inscriptions)
            <dl>
                @foreach($group->inscriptions as $inscription)
                    <dt>{!! $inscription->participant->name !!}</dt>
                    <dd>{{ $inscription->inscription_no }}</dd>
                @endforeach
            </dl>
        @endif
    </td>
    <td>{{ $group->price }} CHF</td>
    <td><a target="_blank" href="{{ $group->doc_facture }}?{{ rand(1,10000) }}" class="btn btn-xs btn-default">Facture en pdf</a></td>
    <td>{{ $group->inscriptions->first()->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>
        <rappel path="inscription" :rappels="{{ $group->rappel_list }}" item="{{ $group->inscriptions->first()->id }}"></rappel>
    </td>
</tr>