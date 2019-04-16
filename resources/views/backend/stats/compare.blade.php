<h3>Tableau changements par année</h3>

<table class="table table-condensed">
        <tr>
            <td>&nbsp;</td>
            @foreach($datapoints['labels'] as $labels)
                <td><strong>{{ $labels }}</strong></td>
            @endforeach
        </tr>
        @foreach($datapoints['datasets'] as $dataset)
            <tr>
                <td>{{ $dataset['label'] }}</td>
                @foreach($dataset['data'] as $data)
                    <td>{{ $data }}</td>
                @endforeach
            </tr>
            {{--    @foreach($datapoints['labels'] as $i => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td><p class="text-primary">{{ $datapoints['datasets'][0]['data'][$i] }}</p></td>
                        <td><p class="text-success">{{ $datapoints['datasets'][1]['data'][$i] }}</p></td>
                        <td><p class="text-danger">{{ $datapoints['datasets'][2]['data'][$i] }}</p></td>
                    </tr>
                @endforeach--}}
        @endforeach
</table>