<h4>Choix du prix applicable</h4>
<div class="form-group">
    <!-- Only public prices -->
    <select required name="{{ $select }}" class="form-control">
        @foreach($colloque->prices as $price)
            <option value="{{ $price->id }}" {{ isset($price_current) && $price_current == $price->id ? 'selected' : '' }}>
                {{ $price->description }} | {{ $price->price_cents }} CHF
            </option>
        @endforeach
    </select>
</div>