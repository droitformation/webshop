<dl>
    @if(!$adresse->orders->isEmpty())
        {!! isset($title) ? '<dt>Commandes '.$title.' <span class="label label-default pull-right">'.$adresse->id.'</span></dt>' : '<dt>Commandes</dt>' !!}
        @foreach($adresse->orders as $order)
            <dd>{{ $order->order_no }}</dd>
        @endforeach
    @endif

    @if(isset($type))
        <?php $unconvert = $adresse->abos->where('user_id',null);  ?>
        @if(!$unconvert->isEmpty())
            {!! '<dt>Abonnements (non converti) <span class="label label-warning pull-right">'.$adresse->id.'</span></dt>' !!}
            @foreach($unconvert as $abo)
                <dd>{{ $abo->abo->title }} | {{ $abo->numero }}</dd>
            @endforeach
        @endif
    @endif

</dl>