@if(!$items->isEmpty())
    <label>{{ $title }}</label>
    <select class="form-control" name="sort[{{ $type }}]">
        <option value="">Choix</option>
        @foreach($items as $item)
            <option {{ isset($sort[$type]) && ($sort[$type] == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title ?? $item->name }}</option>
        @endforeach
    </select>
@endif