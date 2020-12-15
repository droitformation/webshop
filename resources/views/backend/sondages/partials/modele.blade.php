<div class="modal fade" id="updateModele" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <form action="{{ url('admin/sondage/updateModele') }}" method="POST">{!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Choisir un modèle</h4>
                </div>
                <div class="modal-body">
                    <label for="message" class="col-sm-3 control-label">Modèle de sondage</label>
                    <div class="col-sm-6">
                        <select name="id" class="form-control">
                            <option value="">Choisir</option>
                            @if(!$modeles->isEmpty())
                                @foreach($modeles as $modele)
                                    <option value="{{ $modele->id }}">{{ $modele->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <input name="sondage_id" value="{{ $sondage->id }}" type="hidden">
                </div>
                <div class="modal-footer row">
                    <div class="col-md-6 text-left">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                    <div class="col-md-6" style="text-align: right!important;">
                        <button type="submit" class="btn btn-primary">Enrgistrer</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>