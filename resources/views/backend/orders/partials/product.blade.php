<fieldset class="field_clone_order" id="{{ $id }}">
    <div class="row">
        <div class="col-lg-7 col-md-6 col-xs-12">
            <label>Produit</label>
            <select name="order[products][]" required class="chosen-select form-control" data-placeholder="produits">
                <option value="">Choix</option>
                @if(!$products->isEmpty())
                    @foreach($products as $product)
                        <option {{ isset($old_product) && $old_product == $product->id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->title }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-lg-1 col-md-2 col-xs-12">
            <label>Quantité</label>
            <input class="form-control" required type="text" name="order[qty][]">
        </div>
        <div class="col-lg-1 col-md-2 col-xs-12">
            <label>Rabais</label>
            <div class="input-group">
                <input class="form-control" type="text" name="order[rabais][]">
                <span class="input-group-addon">%</span>
            </div><!-- /input-group -->
        </div>
        <div class="col-lg-2 col-md-1 col-xs-12">
            <label></label>
            <div class="checkbox">
                <label><input type="checkbox" name="order[gratuit][]" value="1"> Livre gratuit</label>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-xs-12 text-right">
            <label>&nbsp;</label>
            <p><a href="#" class="btn btn-danger btn-sm remove_order">x</a></p>
        </div>
    </div>
</fieldset>