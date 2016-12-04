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
    <td> @include('backend.inscriptions.partials.payed', ['inscription' => $group->inscriptions->first(), 'model' => 'group', 'item' => $group]) </td>
    <td>{{ $group->inscriptions->first()->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>
        <rappel path="inscription" :rappels="{{ $group->rappel_list }}" item="{{ $group->inscriptions->first()->id }}"></rappel>
    </td>
</tr>