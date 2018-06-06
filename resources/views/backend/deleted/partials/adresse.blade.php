 <div class="panel panel-{{ $color }} panel_33" data-id="{{ $adresse->id }}">
    <div class="panel-body panel-compare">
        <div class="panel-heading">{{ $heading }} <span class="pull-right badge">{{ $adresse->id }}</span></div>

        <?php $person = isset($adresse->user) ? $adresse->user : $adresse; ?>

        <h4>Compte</h4>

        <div class="{{ $person->trashed() ? 'isTrashed' : 'isNotTrashed' }}">
            <h1>{{ $person->name }}</h1>
            <h2>{{ $person->email }}</h2>
        </div>

        <h4>Adresses</h4>

        @if(isset($adresse->user))
            @foreach($adresse->user->adresses as $user_adresse)
                @include('backend.deleted.partials.adresse-bloc', ['adresse' => $user_adresse])
            @endforeach
        @else
           @include('backend.deleted.partials.adresse-bloc', ['adresse' => $adresse])
        @endif

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