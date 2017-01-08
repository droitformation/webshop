<optgroup label="{{ $site->nom }}">
    @if(!$site->internal_pages->isEmpty())
        @foreach($site->internal_pages as $page)
            <option {{ $bloc->pages->contains('id',$page->id) ? 'selected' : '' }} value="{{ $page->id }}">{{ $page->title }}</option>
        @endforeach
    @endif
</optgroup>