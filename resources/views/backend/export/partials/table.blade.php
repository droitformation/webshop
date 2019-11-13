<table>
    <thead>
    <tr>
        @foreach($headers as $header)
            <th style="font-weight: bold;">{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach($inscriptions as $inscription)
            @include('backend.export.partials.row',['inscription' => $inscription])
        @endforeach
    </tbody>
</table>