
@if(isset($bloc->product))

<div class="row">
    <div class="col-md-9">
        <div class="post">
            <div class="post-title">
                <h3 class="title">{{ $bloc->product->title }}</h3>
            </div><!--END POST-TITLE-->
            <div class="post-entry">
                <p>{!!$bloc->product->teaser !!}</p>
                <div>{!! $bloc->product->description !!}</div>
                <p><a target="_blank" style="padding: 5px 15px; text-decoration: none; background: {{ $campagne->newsletter->color }}; color: #fff; margin-top: 10px; display: inline-block;" href="{{ url('pubdroit/product/'.$bloc->product->id) }}">Acheter</a></p>
            </div>
        </div><!--END POST-->
    </div>
    <div class="col-md-3 text-center">
        <a target="_blank" href="{{ url('pubdroit/product/'.$bloc->product->id) }}">
            <img width="130" border="0" alt="{{ $bloc->product->title }}" src="{{ secure_asset('files/products/'.$bloc->product->image) }}" />
        </a>
    </div>
</div>

@endif