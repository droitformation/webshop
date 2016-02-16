@if(!$orders->isEmpty())
    <table>
        <thead>
        <tr>
            <th>Commande n°</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Payé le</th>
            <th class="text-right">Statut</th>

            @foreach($generator->columns as $columns)
                <th>{{ $names[$columns] }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_no }}</td>
                    <td>{{ $order->price_cents }} CHF</td>
                    <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                    <td>{{ $order->payed_at ? $order->payed_at->formatLocalized('%d %B %Y') : '' }}</td>
                    <td>{{ $order->status_code['status'] }}</td>
                    {!! $generator->toRowUser($order->user) !!}
                </tr>
            @endforeach
        </tbody>
    </table>
    <table>
        <tr>
            <td><strong>Total:</strong></td>
            <td>{{ $orders->sum('price_cents') }} CHF</td>
        </tr>
    </table>
@endif
