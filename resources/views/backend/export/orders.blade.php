<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>

    @if(!$orders->isEmpty())
        <table>
            <thead>
            <tr>
                <th>Commande n°</th>
                <th>Date</th>
                <th>Payé le</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Statut</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_no }}</td>
                        <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                        <td>{{ $order->payed_at ? $order->payed_at->formatLocalized('%d %B %Y') : '' }}</td>
                        <td>{{ $order->price_cents }} CHF</td>
                        <td>{{ $order->status_code['status'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>