 <div class="panel panel-{{ $color }} panel_33" data-id="{{ $adresse->id }}">
    <div class="panel-body panel-compare">
        <div class="panel-heading">{{ $heading }} <span class="pull-right badge">{{ $adresse->id }}</span></div>

        <h1>{{ $adresse->name }}</h1>
        <h2>{{ $adresse->email }}</h2>
        <p>{{ $adresse->adresse }}</p>
        {!! !empty($adresse->complement) ? '<p>'.$adresse->complement.'</p>' : '' !!}
        {!! !empty($adresse->cp) ? '<p>'.$adresse->cp_trim.'</p>' : '' !!}
        <p>{{ $adresse->npa }} {{ $adresse->ville }}</p>
        {!! isset($adresse->pays) ? '<p>'.$adresse->pays->title.'</p>' : '' !!}

        <?php $person = isset($adresse->user) ? $adresse->user : $adresse; ?>

        @if(!$person->orders->isEmpty())
            <dl>
                <dt>Commandes</dt>
                @foreach($person->orders as $order)
                    <dd>{{ $order->order_no }}</dd>
                @endforeach
            </dl>
        @endif

        @if(isset($adresse->user) && !$adresse->user->inscriptions->isEmpty())
            <dl>
                <dt>Inscriptions</dt>
                @foreach($adresse->user->inscriptions as $inscription)
                    <dd>{{ $inscription->inscription_no }}</dd>
                @endforeach
            </dl>
        @endif

        @if(isset($person->abos) && !$person->abos->isEmpty())
            <dl>
                <dt>Abonnements</dt>
                @foreach($person->abos as $abo)
                    <dd>{{ $abo->numero }}</dd>
                @endforeach
            </dl>
        @endif

    </div>
</div>