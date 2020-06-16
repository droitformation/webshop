@if(!$colloque->prices_active->isEmpty())
    <h4>Prix applicable</h4>
    <div class='wrapper'>

        @foreach($colloque->prices_active as $price)
            <div class="item_wrapper">
                <input class="prices" required type="radio" id="price_{{ $price->id }}" name="price_id" value="price_id:{{ $price->id }}">
                <label id="label_price_{{ $price->id }}" for="price_{{ $price->id }}">
                    <div class='package'>
                        <div class='name'>{{ $price->description }}</div>
                        <div class='price_cents'>{{ $price->price_cents > 0 ? $price->price_cents.' CHF' : 'Gratuit' }}</div>
                        {!! !empty($price->remarque) ? '<hr/><p>'.$price->remarque.'</p>' : '' !!}
                    </div>
                </label>
            </div>
        @endforeach

        @foreach($colloque->prices_link_active as $pricelink)
            <div class="item_wrapper item_wrapper_link" data-id="{{ $pricelink->id }}" data-colloque="{{ $colloque->id }}">
                <input class="prices priceslink" required type="radio" id="price_link_{{ $pricelink->id }}" name="price_id" value="price_link_id:{{ $pricelink->id }}">
                <label id="label_price_link_{{ $pricelink->id }}" for="price_link_{{ $pricelink->id }}">
                    <div class='package'>
                        <div class='name'>{{ $pricelink->description }}</div>
                        <div class='price_cents'>{{ $pricelink->price_cents > 0 ? $pricelink->price_cents.' CHF' : 'Gratuit' }}</div>
                        {!! !empty($pricelink->remarque) ? '<hr/><p>'.$pricelink->remarque.'</p>' : '' !!}
                    </div>
                </label>
            </div>
        @endforeach

        @if(isset($colloque->compte) && isset($account) && !$account->coupons($colloque->compte->id)->isEmpty())
            <?php $rabais = $account->coupons($colloque->compte->id)->first(); ?>
            <div class="card-rabais">
                <label>Votre rabais</label>
                <input type="text" disabled class="form-control" value="{{ !empty($rabais->description) ? $rabais->description : '' }} (-{{ $rabais->value }} CHF)">
                <input name="rabais_id" value="{{ $rabais->id }}" type="hidden">
            </div>
        @endif

    </div>
    <hr/>
@endif
