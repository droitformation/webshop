<!-- Modal -->
<div class="modal fade" id="addReference_{{ $group->id }}" tabindex="-1" role="dialog" aria-labelledby="editGroup">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- form start -->
            <form action="{{ url('admin/group/references/'.$group->id) }}" method="POST" class="form" >{!! csrf_field() !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ajouter/Modifier une référence</h4>
                </div>
                <div class="modal-body">

                    <h4>Détenteur: {!! $group->name !!}</h4>

                    <div class="form-group">
                        <label><strong>Colloque</strong></label>
                        <p>{{ $group->colloque->titre }}</p>
                        <input type="hidden" name="colloque_id" value="{{ $group->colloque->id }}" />
                    </div>

                    <h4>Réferences</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">N° référence</span>
                                <input type="text" class="form-control" value="{{ isset($group->references) ? $group->references->reference_no : '' }}" name="reference_no">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">N° commande</span>
                                <input type="text" class="form-control" value="{{ isset($group->references) ? $group->references->transaction_no : '' }}" name="transaction_no">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="group_id" value="{{ $group->id}}" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->