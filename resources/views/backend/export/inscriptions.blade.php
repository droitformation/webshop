<!-- Occurences -->
@foreach($inscriptions as $occurence => $grouped)
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- options -->
            @if($sort == 'checkbox')
                @foreach($grouped as $inscriptions)
                    @foreach($inscriptions as $inscription)
                        @include('backend.export.partials.row',['inscription' => $inscription])
                    @endforeach
                @endforeach
            @elseif($sort == 'choice')
                @foreach($grouped as $group)
                    @foreach($group as $options)
                        @foreach($options as $inscription)
                            @include('backend.export.partials.row',['inscription' => $inscription])
                        @endforeach
                    @endforeach
                @endforeach
            @else
                @foreach($grouped as $inscriptions)
                    @foreach($inscriptions as $inscription)
                        @include('backend.export.partials.row',['inscription' => $inscription])
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>
@endforeach