<!-- Modal -->
<div class="modal fade" id="stockHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Historique des stocks</h4>
            </div>
            <div class="modal-body ativa-scroll">
               @include('backend.stocks.modals.table')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <a href="{{ url('admin/stock/export/'.$product->id) }}" class="btn btn-brown btn-sm">Exporter</a>
            </div>
        </div>
    </div>
</div>