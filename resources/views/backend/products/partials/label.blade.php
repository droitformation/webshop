<div class="panel panel-midnightblue">
    <div class="panel-heading">
        <h4><i class="fa fa-star"></i> &nbsp;{{ $title }}</h4>
    </div>
    <div class="panel-body">

        @if(!$product->$labels->isEmpty())
            @foreach($product->$labels as $type)
                <div style="min-height: 30px;">
                    <strong style="line-height: 30px;">{{ $type->title or $type->name }}</strong>
                    <form action="{{ url('admin/productlabel/'.$type->id) }}" method="POST" class="pull-right">
                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                        <input type="hidden" name="type" value="{{ $labels }}" />
                        <button id="delete{{ ucfirst($labels) }}_{{ $type->id }}" data-what="Supprimer" data-action="{{ $type->title }}" class="btn btn-danger btn-xs deleteAction">x</button>
                    </form>
                </div>
                <?php $choices[] = $type->id; ?>
            @endforeach
            <hr/>
        @endif

        <h4>Ajouter {{ $title }}</h4>
        <form action="{{ url('admin/productlabel') }}" method="POST">{!! csrf_field() !!}
            <input type="hidden" name="type" value="{{ $labels }}">
            <input type="hidden" name="product_id" value="{{ $product->id }}" />
            <div class="form-group">
                @if(!$items->isEmpty())
                    <p><select class="form-control multi-selection" name="type_id[]" multiple>
                        @foreach($items as $item)
                            <option {{ isset($choices) && in_array($item->id, $choices) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title or $item->name }}</option>
                        @endforeach
                    </select></p>
                    <button id="add{{ ucfirst($labels) }}" class="btn btn-info" type="submit">Ajouter</button>
                @endif
            </div>
        </form>

    </div>
</div>