<tr {!! ($inscription->group_id ? 'class="isGoupe"' : '') !!}>
    <td><strong>{{ $inscription->inscription_no }}</strong></td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td> @include('backend.inscriptions.partials.payed',['model' => 'inscription', 'item' => $inscription]) </td>
    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>
        <rappel path="inscription" :rappels="{{ $inscription->rappel_list }}" item="{{ $inscription->id }}"></rappel>
    </td>
</tr>