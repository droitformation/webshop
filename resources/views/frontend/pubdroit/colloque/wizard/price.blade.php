@if(!$colloque->prices_active->isEmpty())
    <h4>Prix applicable</h4>
    <div class='wrapper'>
        @foreach($colloque->prices_active as $price)
            <div class="item_wrapper">
                <input class="prices" required type="radio" id="price_{{ $price->id }}" name="price_id" value="{{ $price->id }}">
                <label id="label_price_{{ $price->id }}" for="price_{{ $price->id }}">
                    <div class='package'>
                        <div class='name'>{{ $price->description }}</div>
                        <div class='price_cents'>{{ $price->price_cents > 0 ? $price->price_cents.' CHF' : 'Gratuit' }}</div>
                        {!! !empty($price->remarque) ? '<hr/><p>'.$price->remarque.'</p>' : '' !!}
                    </div>
                </label>
            </div>
        @endforeach

        @if(isset($colloque->compte_id) && !$account->coupons($colloque->compte->id)->isEmpty())
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
