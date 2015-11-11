<fieldset class="container-export">
    <div class="form-group">

        @if(!$items->isEmpty())
            <?php
                $total  = $items->count();
                $slice  = ((int)$total/4) + 1;
                $chunks = $items->chunk($slice);
            ?>
            @foreach($chunks as $column)
                <div class="col-md-3">
                    @foreach($column as $item)
                        <div class="checkbox checkbox-item">
                            <label>
                                <input {!! isset($class) ? 'class="'.$class.'"' : '' !!} name="{{ $name }}" value="{{ $item->id }}" type="checkbox">
                                <span class="item-content">{{ $item->title }}</span>
                                <span class="label label-info pull-right">{{ $item->id }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach

        @endif

    </div>
</fieldset>