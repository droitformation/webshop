@if(!empty($orders))
    <table>
        <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order['Numero'] }}</td>
                    <td>{{ $order['Date']}}</td>
                    <td>{{ $order['Montant'] }}</td>
                    <td>{{ $order['Port'] }}</td>
                    <td>{{ $order['Paye'] }}</td>
                    <td>{{ $order['Status'] }}</td>
                    {!! isset($order['title']) ? '<td>'.$order['title'].'</td>' : '' !!}
                    {!! isset($order['qty']) ? '<td>'.$order['qty'].'</td>' : '' !!}
                    {!! isset($order['prix']) ? '<td>'.$order['prix'].'</td>' : '' !!}
                    {!! isset($order['special']) ? '<td>'.$order['special'].'</td>' : '' !!}
                    {!! isset($order['free']) ? '<td>'.$order['free'].'</td>' : '' !!}
                    {!! isset($order['rabais']) ? '<td>'.$order['rabais'].'</td>' : '' !!}
                </tr>
            @endforeach
        </tbody>

    </table>
@endif
