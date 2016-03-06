<!-- Payed -->
@if($facture->payed_at)

    <div class="row">
        <div class="col-md-9">
            <p><span class="label label-success"><i class="fa fa-star"></i></span> &nbsp;Payé le {!! $facture->payed_at->formatLocalized('%d %B %Y') !!}</p>
        </div>
        <div class="col-md-3">
            @include('backend.abonnements.partials.delete', ['payement' => $facture, 'type' => 'facture'])
        </div>
    </div>

@else

    <p><span class="label label-default"><i class="fa fa-star"></i></span> &nbsp;En attente: {{ $facture->created_at->formatLocalized('%d %B %Y') }}</p>
    <div class="row">
        <div class="col-md-3">
            @if($facture->abo_facture)
                <a class="btn btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
            @endif
        </div>
        <div class="col-md-6">
            <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
                <input type="hidden" value="{{ $facture->id }}" name="id">
                <div class="form-group input-group">
                    <input type="text" class="form-control datePicker" name="payed_at" placeholder="Payé le">
                    <span class="input-group-btn"><button class="btn btn-info" type="submit">Marquer payé</button></span>
                </div>
            </form>
        </div>
        <div class="col-md-3 text-right">
            <form action="{{ url('admin/rappel') }}" method="POST">{!! csrf_field() !!}
                <input type="hidden" value="{{ $facture->id }}" name="abo_facture_id">
                <button class="btn btn-warning" type="submit">Créer un rappel</button>
            </form>
        </div>
    </div>

@endif
<!-- End Payed -->