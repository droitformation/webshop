<!-- Payed -->
<div class="row">
    <div class="col-md-6">
        <a href="{{ url('admin/facture/'.$facture->id) }}" class="btn btn-info"><i class="fa fa-edit"></i></a>
        @if($facture->abo_facture)
            <a class="btn btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
        @endif
    </div>
    <div class="col-md-6 text-right">
        @if($facture->payed_at)
            @include('backend.abonnements.partials.delete', ['payement' => $facture, 'type' => 'facture'])
        @else
            <form action="{{ url('admin/rappel') }}" method="POST">{!! csrf_field() !!}
                <input type="hidden" value="{{ $facture->id }}" name="abo_facture_id">
                <button class="btn btn-warning" type="submit">Créer un rappel</button>
            </form>
        @endif
    </div>
</div>

<!-- End Payed -->