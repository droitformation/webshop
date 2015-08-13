<div class="form-group">
    <label class="control-label">Choix du prix applicable</label>
    <!-- Only public prices -->
    <?php  $filtered = $colloque->prices->reject(function ($item) {return $item->type == 'admin'; });?>
    <select style="max-width: 260px;" required name="{{ $select }}" class="form-control">
        <option value="">Choix</option>
        @foreach($filtered as $price)
            <option value="{{ $price->id }}">{{ $price->description }} | <strong>{{ $price->price_cents }} CHF</strong></option>
        @endforeach
    </select>
</div>