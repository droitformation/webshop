<div class="row">
    <div class="col-md-4">
        <h4>Payement</h4>
        {!! $inscription->payed_at ? '<p class="label label-success">Payé le '.$inscription->payed_at->format('d/m/Y').'</p>' : '<p class="label label-default">En attente</p>' !!}
    </div>
    <div class="col-md-4">
        <h4>Date</h4>
        {{ $inscription->created_at->formatLocalized('%d %b %Y') }}
    </div>
    <div class="col-md-4">
        @if($inscription->send_at)
            <h4>Envoyé le</h4>
            <span class="fa fa-paper-plane"></span> &nbsp;{{ $inscription->send_at->formatLocalized('%d %b %Y') }}
        @endif
    </div>
</div>

<div class="row">
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