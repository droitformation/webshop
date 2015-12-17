@if(!$items->isEmpty())
    <label>{{ $title }}</label>
    <select class="form-control" name="search[{{ $type }}]">
        <option value="">Choix</option>
        @foreach($items as $item)
            <option value="{{ $item->id }}">{{ $item->title or $item->name }}</option>
        @endforeach
    </select>
@endif