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
                <th>Titre</th>
                <th>Quantité</th>
                <th>Prix</th>
                <th>Prix spécial</th>
                <th>Gratuit</th>
                <th>Rabais</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_no }}</td>
                        <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>

                        <?php $grouped = $order->products->groupBy('id'); ?>
                        @foreach($grouped as $product)

                            <?php $price_sum = $product->reject(function ($item) { return $item->pivot->isFree; })->sum('price_cents'); ?>

                            <td valign="top">{{ $product->first()->title }}</td>
                            <td valign="top">{{ $product->count() }} x</td>
                            <td valign="top">{{ $product->first()->price_cents }} CHF</td>
                            <td valign="top">{{ $product->first()->price_special ? $product->first()->price_special.' CHF' : '' }}</td>
                            <td valign="top">{{ $product->first()->pivot->isFree ? 'Oui' : '' }}</td>
                            <td valign="top">{{ $product->first()->pivot->rabais ? $product->first()->pivot->rabais.'%' : '' }}</td>

                        @endforeach
                    </tr>

                @endforeach
            </tbody>
        </table>

    @endif

</body>
</html>