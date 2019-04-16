<h3>Tableau changements par année</h3>
<table class="table table-condensed" style="margin-bottom: 20px;">
    <thead>
    <tr>
        <th>Année</th>
        <th>Total</th>
        <th>Crées</th>
        <th>Supprimés</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datapoints['labels'] as $i => $label)
        <tr>
            <td>{{ $label }}</td>
            <td><p class="text-primary">{{ $datapoints['datasets'][0]['data'][$i] }}</p></td>
            <td><p class="text-success">{{ $datapoints['datasets'][1]['data'][$i] }}</p></td>
            <td><p class="text-danger">{{ $datapoints['datasets'][2]['data'][$i] }}</p></td>
        </tr>
    @endforeach
    </tbody>
</table>