<h4>Choix du prix applicable</h4>
<div class="list_prices">

    <div class="form-group">
        <label><strong>Prix normal</strong></label>
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

    {{--@if(!$colloque->prices->isEmpty())
        <div class="form-group">
            <label><strong>Prix normal</strong></label>
            <select required name="price_id" class="form-control">
                <option>Choix</option>
                @foreach($colloque->prices as $price)
                    <option value="{{ $price->id }}" {{ isset($price_current) && $price_current == $price->id ? 'selected' : '' }}>
                        {{ $price->description }} | {{ $price->price_cents }} CHF
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @if(!$colloque->price_link->isEmpty())
        <div class="form-group">
            <label><strong>Prix multiple colloques</strong></label>
            <select required name="price_link_id" class="form-control">
                <option>Choix</option>
                @foreach($colloque->price_link as $price)
                    <option value="{{ $price->id }}" {{ isset($price_current) && $price_current == $price->id ? 'selected' : '' }}>
                        {{ $price->description }} | {{ $price->price_cents }} CHF
                    </option>
                @endforeach
            </select>
        </div>
    @endif
--}}
</div>