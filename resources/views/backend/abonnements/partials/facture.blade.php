<!-- Payed -->
@if($facture->payed_at)

    <div class="row">
        <div class="col-md-3">
            <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
                <input type="hidden" value="{{ $facture->id }}" name="id">
                <input type="hidden" value="{{ $facture->payed_at }}" name="payed_at">
                <button class="btn btn-info btn-sm"><i class="fa fa-refresh"></i></button>
                @if($facture->abo_facture)
                    <a class="btn btn-default btn-sm" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
                @endif
            </form>
        </div>
        <div class="col-md-6">
            <!-- Ajax payement update input -->
            <div class="input-group">
                <div class="form-control editablePayementDate"
                     data-name="payed_at" data-type="date" data-pk="{{ $facture->id }}" data-model="facture"
                     data-url="admin/facture/editItem" data-title="Date de payment">
                    {{ $facture->payed_at ? $facture->payed_at->format('Y-m-d') : '' }}
                </div>
                <span class="input-group-addon bg-{{ $facture->payed_at ? 'success' : '' }}">
                    {{ $facture->payed_at ? 'payé' : 'en attente' }}
                </span>
            </div>
            <!-- End Ajax payement update input -->
        </div>
        <div class="col-md-3">
            @include('backend.abonnements.partials.delete', ['payement' => $facture, 'type' => 'facture'])
        </div>
    </div>

@else

    <div class="row">
        <div class="col-md-3">
            @if($facture->abo_facture)
                <a class="btn btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
            @endif
        </div>
        <div class="col-md-6">
            <!-- Ajax payement update input -->
            <div class="input-group">
                <div class="form-control editablePayementDate"
                     data-name="payed_at" data-type="date" data-pk="{{ $facture->id }}" data-model="facture"
                     data-url="admin/facture/editItem" data-title="Date de payment">
                    {{ $facture->payed_at ? $facture->payed_at->format('Y-m-d') : '' }}
                </div>
                <span class="input-group-addon bg-{{ $facture->payed_at ? 'success' : '' }}">{{ $facture->payed_at ? 'payé' : 'en attente' }}</span>
            </div>
            <!-- End Ajax payement update input -->
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