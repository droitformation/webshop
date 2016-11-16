@if(!$inscription->is_free)
    <div class="input-group">
        <div class="form-control editablePayementDate"
             data-name="payed_at"
             data-type="date"
             data-model="{{ $model }}"
             data-pk="{{ $item->id }}"
             data-url="admin/inscription/edit"
             data-title="Date de payment">
            {{ $inscription->payed_at ? $inscription->payed_at->format('Y-m-d') : '' }}
        </div>
        <span class="input-group-addon bg-{{ $inscription->payed_at ? 'success' : 'default' }}">
            {{ $inscription->payed_at ? 'payé' : 'en attente' }}
        </span>
    </div>
@endif