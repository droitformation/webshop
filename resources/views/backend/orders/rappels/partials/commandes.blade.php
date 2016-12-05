@if(!$orders->isEmpty())

    <?php $orders->load('products','shipping','coupon','payement'); ?>
    <?php $orders = $orders->sortByDesc('created_at'); ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-2">Commande n°</th>
                <th class="col-md-2">Prix</th>
                <th class="col-md-2">Facture</th>
                <th class="col-md-6">Rappel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="mainRow">
                    <td><a href="{{ url('admin/order/'.$order->id) }}"><i class="fa fa-order fa-arrow-circle-right"></i> &nbsp;{{ $order->order_no }}</a></td>
                    <td>{{ $order->total_with_shipping }} CHF</td>
                    <td>
                        @if($order->facture)
                            <a target="_blank" href="{{ $order->facture }}?{{ rand(1,10000) }}" class="btn btn-xs btn-default">Facture en pdf</a>
                        @else
                            <generate path="order" order="{{ $order->id }}"></generate>
                        @endif
                    </td>
                    <td>
                        <rappel path="order" :rappels="{{ $order->rappel_list }}" item="{{ $order->id }}"></rappel>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Encore aucune commandes</p>
@endif