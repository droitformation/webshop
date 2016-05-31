<div class="row">
    <div class="col-md-2">
        <h4>Payement</h4>
        @if($inscription->payed_at)
            <p class="label label-success" style="font-size: 90%;">Payé le</p> {{ $inscription->payed_at->format('d/m/Y') }}
        @else
            <p class="label label-default" style="font-size: 90%;">En attente</p>
        @endif
    </div>
    <div class="col-md-5">
        <h4>Documents</h4>
        @include('backend.users.inscription.documents')
    </div>
    <div class="col-md-5">
        <!-- Occurences -->
        @if(!$inscription->occurrences->isEmpty())
            <h4>Conférences</h4>
            <ol>
                @foreach($inscription->occurrences as $occurrences)
                    <li>{{ $occurrences->title }}</li>
                @endforeach
            </ol>
        @endif
        @include('backend.users.inscription.options')
    </div>
</div>