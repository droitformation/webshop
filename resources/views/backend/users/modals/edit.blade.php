
<!-- Modal -->
<div class="modal fade" id="editInscription_{{ $inscription->id }}" tabindex="-1" role="dialog" aria-labelledby="editInscription">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form" >
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editer {{ $inscription->inscription_no }}</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        @if($inscription->group_id)
                            <div class="form-group">
                                <label>Nom du participant</label>
                                <input name="participant" required class="form-control" value="{{ $inscription->participant->name }}" type="text">
                            </div>
                        @endif

                        @if(!$inscription->colloque->prices->isEmpty())
                            @include('backend.inscriptions.partials.prices', ['select' => 'price_id', 'price_current' => $inscription->price->id, 'colloque' => $inscription->colloque])
                        @endif

                        <!-- Occurence if any -->
                        @if(!$inscription->colloque->occurrences->isEmpty())
                            <h4>Conférences</h4>
                            @include('backend.inscriptions..partials.occurrences', ['select' => 'occurrences[]', 'colloque' => $inscription->colloque])
                        @endif

                        @if(!$inscription->colloque->options->isEmpty())
                            <h4>Merci de préciser</h4>
                            @include('backend.inscriptions.partials.options', ['select' => 'groupes', 'colloque' => $inscription->colloque])
                        @endif

                        <?php $user = ($inscription->group_id ? 'group_id' : 'user_id'); ?>

                        <input name="{{ $user }}" value="{{ $inscription->$user }}" type="hidden">
                        <input name="colloque_id" value="{{ $inscription->colloque->id }}" type="hidden">
                    </fieldset>

                    <br/>
                    <p class="text-warning"><i class="fa fa-warning"></i> &nbsp;La mise a jour prend quelques secondes car les documents sont regénérés</p>

                </div>
                <div class="modal-footer">
                    {!! Form::hidden('id', $inscription->id ) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">&eacute;diter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->