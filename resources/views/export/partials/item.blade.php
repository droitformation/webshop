<fieldset class="container-export">
    <div class="form-group">
        @if(!$items->isEmpty())
            @foreach($items as $item)
                <div class="checkbox checkbox-item">
                    <label>
                        <input name="{{ $name }}" value="{{ $item->id }}" type="checkbox"> {{ $item->title }}
                    </label>
                </div>
            @endforeach
        @endif
    </div>
</fieldset>