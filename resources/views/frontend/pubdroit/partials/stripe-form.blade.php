<!-- Modal -->
<div class="modal fade " id="payment-stripe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Payer</h4>
            </div>
            <div class="modal-body">
                @include('frontend.pubdroit.gateways.stripe')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>