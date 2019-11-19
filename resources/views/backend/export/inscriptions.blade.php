<!-- Occurences -->

<table align="left">
    <tr>
        <td style="font-size: 20px;">
            <h1>{{ $colloque->titre }}</h1>
            <h2>{{ $colloque->soustitre }}</h2>
        </td>
    </tr>
    <tr><td style="font-size: 15px;"><h3>{{ $colloque->organisateur }} | {{ $colloque->event_date }}</h3></td></tr>
</table>

@foreach($inscriptions as $occurence => $grouped)

    @if(is_string($occurence))
        <table align="left">
            <tr align="left">
                <td style="font-size: 19px;">{{ $occurence }}</td>
            </tr>
        </table>
    @endif

    <!-- options -->
    @if($sort == 'checkbox')
        @foreach($grouped as $option_id => $inscriptions)

            <table align="left">
                <tr align="left">
                    <?php
                    $option_name = $colloque->options->find($option_id);
                    $option_name = $option_name ? $option_name->title : 'Aucune option';
                    ?>
                    <td style="font-size: 15px;">{{ $option_name }}</td>
                </tr>
            </table>

            @include('backend.export.partials.table',['inscriptions' => $inscriptions])
        @endforeach
    @elseif($sort == 'choice')
        @foreach($grouped as $option_id => $group)

            <table align="left">
                <tr align="left">
                    <?php
                        $option_name = $colloque->options->find($option_id);
                        $option_name = $option_name ? $option_name->title : '';
                    ?>
                    <td style="font-size: 16px;">{{ $option_name }}</td>
                </tr>
            </table>

            @foreach($group as $group_id => $inscriptions)

                <table align="left">
                    <tr align="left">
                        <?php
                            $name = $colloque->groupes->find($group_id);
                            $name = $name ? $name->text : '';
                        ?>
                        <td style="font-size: 13px;">{{ $name }}</td>
                    </tr>
                </table>

                @include('backend.export.partials.table',['inscriptions' => $inscriptions])
            @endforeach
        @endforeach
    @else
        @foreach($grouped as $inscriptions)
            @include('backend.export.partials.table',['inscriptions' => $inscriptions])
        @endforeach
    @endif

@endforeach