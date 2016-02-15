<div class="panel panel-success">
    <div class="panel-body">
        <h4 class="text-success"><i class="fa fa-shopping-cart"></i> &nbsp;Dernières commandes</h4>
        <table class="table">
            <thead>
            <tr>
                <th class="col-sm-1">Action</th>
                <th class="col-sm-3">Déteteur</th>
                <th class="col-sm-3">No</th>
                <th class="col-sm-2">Date</th>
            </tr>
            </thead>
            <tbody class="selects">
                @if(!$orders->isEmpty())
                    @foreach($orders as $order)
                        <tr>
                            <td><a class="btn btn-success btn-sm" href="{{ url('admin/order/'.$order->id) }}"><i class="fa fa-edit"></i></a></td>
                            <td>{{ $order->order_adresse->name }}</td>
                            <td><strong>{{ $order->order_no }}</strong></td>
                            <td>{{ $order->created_at->formatLocalized('%d %B %Y') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>