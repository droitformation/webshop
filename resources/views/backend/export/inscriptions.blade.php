<table>
    <thead>
        <tr>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($inscriptions as $inscription)
            <tr>
                <td>{{ $inscription->present ? 'Oui' : '' }}</td>
                <td>{{ $inscription->inscription_no }}</td>
                <td>{{ $inscription->price_cents }}</td>
                <td>{{ $inscription->status_name['status'] }}</td>
                <td>{{ $inscription->created_at->format('m/d/Y') }}</td>
                <td>{{ ($inscription->group_id > 0 ? $inscription->participant->name : '') }}</td>
                @foreach($columns as $column)
                    <?php $user = $inscription->inscrit; ?>
                    {{ $user->adresses->first()->$column }}
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>