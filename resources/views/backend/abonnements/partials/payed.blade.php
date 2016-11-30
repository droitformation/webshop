<div class="input-group">
    <div class="form-control editablePayementDate"
         data-name="payed_at"
         data-type="date"
         data-pk="{{ $facture->id }}"
         data-url="admin/facture/edit"
         data-title="Date de payment">
        {{ $facture->payed_at ? $facture->payed_at->format('Y-m-d') : '' }}
    </div>
    <span class="input-group-addon bg-{{ $facture->payed_at ? 'success' : 'default' }}">
        {{ $facture->payed_at ? 'payé' : 'en attente' }}
    </span>
</div>