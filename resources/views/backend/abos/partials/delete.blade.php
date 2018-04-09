@if($abonnement)
    <div class="modal fade" id="resilier_{{ $abonnement->id }}" tabindex="-1" role="dialog" aria-labelledby="resilier_{{ $abonnement->id }}">
        <div class="modal-dialog" role="document">
            <form action="{{ url('admin/abonnement/'.$abonnement->id) }}" method="POST">
                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Résilier l'abonnement {{ $abonnement->numero }}</h4>
                    </div>
                    <div class="modal-body text-left">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="raison"><strong>Raison de la résiliation</strong></label><br/>
                                <input type="text" name="raison" class="form-control" style="width: 100%;" placeholder="Raison">
                            </div>
                            <div class="col-md-3">
                                <label><strong>Date de la résiliation</strong></label><br/>
                                <input type="text" name="deleted_at" class="form-control datePicker" placeholder="" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-warning">Résilier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif