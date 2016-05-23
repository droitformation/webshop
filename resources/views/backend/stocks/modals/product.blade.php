<!-- Modal -->
<div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{!! url('admin/stock/change') !!}" method="POST" class="form">{!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Changer le stock</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Motif</label>
                        <input type="text" class="form-control" name="motif">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon bg-success"> + </span>
                            <input type="number" class="form-control" name="amount[+]" placeholder="Augmenter de">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon bg-danger"> - </span>
                            <input type="number" class="form-control" name="amount[-]" placeholder="Diminuer de">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary btn-sm">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>