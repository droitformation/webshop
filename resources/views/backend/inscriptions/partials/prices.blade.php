<h4>Choix du prix applicable</h4>
<div class="list_prices">

    <div class="form-group">
        <select data-colloque="{{ $colloque->id }}" data-index="0" data-form="{{ $form ?? 'price_id' }}" required name="{{ $select }}" class="form-control price_type">
            <option>Choix</option>
            @foreach($colloque->prices as $price)
                <option data-type="price_id" value="price_id:{{ $price->id }}" {{ isset($price_current) && $price_current == $price->id ? 'selected' : '' }}>
                    {{ $price->description }} | {{ $price->price_cents }} CHF
                </option>
            @endforeach
            @if(!$colloque->prices->isEmpty())
                @foreach($colloque->price_link as $price)
                    <option data-type="price_link_id" value="price_link_id:{{ $price->id }}" {{ isset($price_current) && $price_current == $price->id ? 'selected' : '' }}>
                        {{ $price->description }} | {{ $price->price_cents }} CHF
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>