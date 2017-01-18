<tr class="isGoupe">
    <td>
        <p><strong>{{ $inscription->detenteur['civilite'] }}</strong></p>
        <p><a href="{{ url('admin/user/'.$inscription->detenteur['id']) }}">{{ $inscription->detenteur['name'] }}</a></p>
        <p>{{ $inscription->detenteur['email'] }}</p>
    </td>
    <td>
        @if($inscription->groupe->inscriptions)
            @foreach($inscription->groupe->inscriptions as $participant)
                @include('backend.inscriptions.partials.participant', ['inscription' => $participant])
            @endforeach
        @endif
    </td>
    <td>{{ $inscription->groupe->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>{{ $inscription->status_name['status'] }}</td>
</tr>