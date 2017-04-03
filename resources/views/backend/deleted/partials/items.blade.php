<dl>
    @if(!$adresse->orders->isEmpty())
        {!! isset($title) ? '<dt>Commandes '.$title.' <span class="label label-default pull-right">'.$adresse->id.'</span></dt>' : '<dt>Commandes</dt>' !!}
        @foreach($adresse->orders as $order)
            <dd>{{ $order->order_no }}</dd>
        @endforeach
    @endif

    @if(isset($adresse->abos) && !$adresse->abos->isEmpty())
        {!! isset($title) ? '<dt>Abonnements '.$title.' <span class="label label-default pull-right">'.$adresse->id.'</span></dt>' : '<dt>Abonnements</dt>' !!}
        @foreach($adresse->abos as $abo)
            <dd>{{ $abo->numero }}</dd>
        @endforeach
    @endif
</dl>