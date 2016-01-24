<div class="row">
    @foreach($blocs as $bloc)
        <div class="col-md-4"><p><strong>{!! $bloc->title !!}</strong></p></div>
        <div class="col-md-8">{!! $bloc->content !!}</div>
    @endforeach
</div>