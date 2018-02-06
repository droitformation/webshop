<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">Colloque</th>
            <th class="col-md-4">Détenteur</th>
            <th class="col-md-2 text-right">Montant inscription</th>
            <th class="col-md-2 text-right">Montant total</th>
        </tr>
    </thead>
    <tbody>
        <!-- Group start -->

        @foreach($user->inscription_participations as $participant)
            @if(isset($participant->inscription))
            <tr class="mainRow">
                <td><strong>{{ $participant->inscription->colloque->titre }}</strong></td>
                <td>
                    <address>
                        {{ $participant->inscription->groupe->user->adresse_facturation->company }}<br/>
                        <a class="text-info" href="{{ url('admin/user/'.$participant->inscription->inscrit->id) }}">{{ $participant->inscription->inscrit->name }}</a><br>
                        {{ $participant->inscription->groupe->user->adresse_facturation->adresse }}<br/>
                        {{ $participant->inscription->groupe->user->adresse_facturation->npa }} {{ $participant->inscription->groupe->user->adresse_facturation->ville }}
                    </address>
                </td>
                <td class="text-right">{{ $participant->inscription->price_cents }} CHF</td>
                <td class="text-right">{{ $participant->inscription->groupe->price_cents }} CHF</td>
            </tr>
            @endif
        @endforeach

        <!-- Group end -->
    </tbody>
</table>
