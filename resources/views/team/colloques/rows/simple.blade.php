<tr>
    <td>
        <p><strong>{{ $inscription->detenteur['civilite'] }}</strong></p>
        <p>{{ $inscription->detenteur['name'] }}</p>
        <p>{{ $inscription->detenteur['email'] }}</p>
    </td>
    <td>
        <strong>{{ $inscription->inscription_no }}</strong>
    </td>
    <td>{{ $inscription->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>{{ $inscription->status_name['status'] }}</td>
</tr>