<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>

    <table>
        <tr>
            <td height="80px">
                <h3>{{ $colloque->titre }}</h3>
                <h4>{{ $colloque->soustitre }}</h4>
            </td>
            <td>
                <h4><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->organisateur }}</a></h4>
                <p>{{ $colloque->event_date }}</p>
            </td>
        </tr>
        <tr><td colspan="2"></td></tr>
    </table>

    @if(!empty($inscriptions))
        @if(!empty($order))
            @foreach($inscriptions as $option_id => $rows)

                <table>
                    <tr><td><strong>{{ (isset($options[$option_id]) ? 'OPTION: '.$options[$option_id] : '') }}</strong></td></tr>
                </table>

                @if($order == 'checkbox')
                    @include('backend.export.table',['rows' => $rows])
                @else
                    @foreach($rows as $groupe_id => $row)

                        <table>
                            <tr><td><strong>{{ (isset($groupes[$option_id][$groupe_id]) ? 'CHOIX: '.$groupes[$option_id][$groupe_id] : '') }}</strong></td></tr>
                        </table>

                        @include('backend.export.table',['rows' => $row])
                    @endforeach
                @endif

            @endforeach
        @else
            @include('backend.export.table',['rows' => $inscriptions])
        @endif
    @endif

</body>
</html>