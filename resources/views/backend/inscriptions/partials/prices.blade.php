<h4>Choix du prix applicable</h4>
<div class="form-group">
    <!-- Only public prices -->
    <select required name="{{ $select }}" class="form-control">
        <option value="">Choix</option>
        @foreach($colloque->prices as $price)
            <option value="{{ $price->id }}" <?php echo (isset($price_current) && $price_current == $price->id ? 'selected' : ''); ?>>
                {{ $price->description }} | <strong>{{ $price->price_cents }} CHF</strong>
            </option>
        @endforeach
    </select>
</div>