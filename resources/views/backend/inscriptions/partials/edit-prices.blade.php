<h4>Choix du prix applicable</h4>
<div class="list_prices">

    <?php $isLink = $inscription->price_link_id ?? null ?>

    <div class="form-group">
        <select data-colloque="{{ $colloque->id }}" data-index="0" data-form="{{ $form ?? 'price_id' }}" required name="price_id" class="form-control price_type">
            <option>Choix</option>
            @foreach($colloque->prices as $price)
                <option data-type="price_id" value="price_id:{{ $price->id }}" {{ !$isLink && $inscription->price_id == $price->id ? 'selected' : '' }}>
                    {{ $price->description }} | {{ $price->price_cents }} CHF
                </option>
            @endforeach
            @if(!$colloque->price_link->isEmpty())
                @foreach($colloque->price_link as $price_link)
                    <option data-type="price_link_id" value="price_link_id:{{ $price_link->id }}" {{ $isLink && $inscription->price_link_id == $price_link->id ? 'selected' : '' }}>
                        {{ $price_link->description }} | {{ $price_link->price_cents }} CHF
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>