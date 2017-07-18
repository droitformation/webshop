<div class="collapse collapse_download {{ !$product->download_link ? 'in' : '' }}" id="collapseLink_{{ $product->id }}">
    {!! Form::file('download_link') !!}
</div>

@if($product->download_link)
    <a class="btn btn-primary btn-sm" target="_blank" href="{{ asset('files/downloads/'.$product->download_link) }}">
        <i class="fa fa-file"></i> &nbsp;Lien vers le pdf
    </a>
    <button class="btn btn-warning btn-sm" type="button" data-toggle="collapse" data-target="#collapseLink_{{ $product->id }}">Changer le pdf</button>
    <button class="btn btn-danger btn-sm link_delete_btn" type="button" data-id="{{ $product->id }}">x</button>
@endif