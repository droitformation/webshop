<fieldset class="field_clone_order" data-index="{{ $index }}" id="{{ $id }}">
    <div class="row">
        <div class="col-lg-6 col-md-5 col-xs-12">
            <label style="display: block;">Produit</label>
            <select name="order[products][]" class="chosen-select form-control input-product" data-placeholder="produits">
                <option value="">Choix</option>
                @if(!$products->isEmpty())
                    @foreach($products as $product)
                        <option data-sku="{{ $product->sku }}" {{ isset($old_product['product']) && $old_product['product'] == $product->id ? 'selected' : '' }} value="{{ $product->id }}">
                            {{ $product->title }} | {{ $product->price_cents  }} CHF | qté: {{ $product->sku  }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-lg-1 col-md-2 col-xs-12">
            <label>Quantité</label>
            <input class="form-control input-qty" required type="number" value="{{ isset($old_product) ? $old_product['qty'] : '' }}" name="order[qty][{{ $index }}]">
            <span class="error_qty" style="display:none; font-size: 10px; color: red;">Plus assez de stock</span>
        </div>
        <div class="col-lg-1 col-md-2 col-xs-12">
            <label>Rabais</label>
            <div class="input-group">
                <input class="form-control" value="{{ isset($old_product['rabais']) ? $old_product['rabais'] : '' }}" type="text" name="order[rabais][{{ $index }}]">
                <span class="input-group-addon">%</span>
            </div><!-- /input-group -->
        </div>
        <div class="col-lg-1 col-md-2 col-xs-12">
            <label>Prix spécial</label>
            <div class="input-group">
                <input class="form-control" value="{{ isset($old_product['price']) ? $old_product['price'] : '' }}" type="text" name="order[price][{{ $index }}]">
                <span class="input-group-addon">CHF</span>
            </div>
        </div>
        <div class="col-lg-2 col-md-1 col-xs-12">
            <label></label>
            <div class="checkbox">
                <label><input type="checkbox" {{ isset($old_product['gratuit']) ? 'checked' : '' }} name="order[gratuit][{{ $index }}]" value="1"> Livre gratuit</label>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-xs-12 text-right">
            <label>&nbsp;</label>
            <p><a href="#" class="btn btn-danger btn-sm remove_order">x</a></p>
        </div>
    </div>
</fieldset>