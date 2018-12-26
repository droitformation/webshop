
@if(isset($bloc->product))

<p><img width="130" border="0" src="{{ secure_asset('files/products/'.$bloc->product->image) }}" /></p>
<h3 class="title">{{ $bloc->product->title }}</h3>
<p>{!!$bloc->product->teaser !!}</p>

@endif