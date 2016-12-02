<!-- Payed -->
<div class="row">
    <div class="col-md-4">
        <a href="{{ url('admin/facture/'.$facture->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>

        @if($facture->abo_facture)
            <a class="btn btn-default btn-sm" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
        @endif
    </div>
    <div class="col-md-4">
        @if($facture->payed_at)
            <p style="line-height: 30px; margin-bottom: 0;"><span class="label label-success"><i class="fa fa-star"></i></span>&nbsp;&nbsp;
                <strong>Payé le {!! $facture->payed_at->format('Y-m-d') !!}</strong>
            </p>
        @endif
    </div>
    <div class="col-md-4 text-right">

        <!-- delete facture -->
        @include('backend.abonnements.partials.delete', ['payement' => $facture, 'type' => 'facture'])

        <!-- Make rappel -->
        @if(!$facture->payed_at)
            <form action="{{ url('admin/rappel') }}" method="POST" class="pull-right" style="margin-right: 10px;">{!! csrf_field() !!}
                <input type="hidden" value="{{ $facture->id }}" name="abo_facture_id">
                <button class="btn btn-brown btn-sm " type="submit">Générer un rappel</button>
            </form>
        @endif

    </div>
</div>
<!-- End Payed -->