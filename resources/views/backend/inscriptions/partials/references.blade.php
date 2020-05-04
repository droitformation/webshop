<div class="invoice_reference">
    <h4><strong>Références:</strong></h4>
    <div class="input-group">
        <span class="input-group-addon">N° référence</span>
        <input type="text" class="form-control" value="{{ old('reference_no') }}" name="reference_no">
    </div><br>

    <div class="input-group">
        <span class="input-group-addon">N° commande</span>
        <input type="text" class="form-control" value="{{ old('transaction_no') }}" name="transaction_no">
    </div>
</div>