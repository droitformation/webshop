<!-- Modal -->
<div class="modal fade" id="stockHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Historique des stocks</h4>
            </div>
            <div class="modal-body">

                <?php $helper = new App\Droit\Helper\Helper(); ?>

                @if(!$stocks->isEmpty())
                <table class="table table-bordered">
                   <thead>
                       <tr>
                           <td><strong>Motif</strong></td>
                           <td><strong>Opération</strong></td>
                           <td><strong>Nombre</strong></td>
                           <td><strong>État du stock</strong></td>
                       </tr>
                   </thead>
                    <tbody>
                        <?php $etat = 0; ?>
                        @foreach($stocks as $stock)
                            <?php $etat = $helper->calculSku($etat, $stock->amount, $stock->operator); ?>
                            <tr>
                                <td>{{ $stock->motif }}</td>
                                <td>{!! $stock->operator == '+' ? '<span class="label label-success">+</span>' : '<span class="label label-danger">-</span>' !!}</td>
                                <td>{{ $stock->amount }}</td>
                                <td>{{ $etat }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <a href="{{ url('admin/stock/export/'.$product->id) }}" class="btn btn-brown btn-sm" data-dismiss="modal">Exporter</a>
            </div>
        </div>
    </div>
</div>