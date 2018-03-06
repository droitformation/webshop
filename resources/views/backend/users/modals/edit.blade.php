
<!-- Modal -->
<div class="modal fade" id="editInscription_{{ $inscription->id }}" tabindex="-1" role="dialog" aria-labelledby="editInscription">
    <div class="modal-dialog" role="document">
        <div class="modal-content edit-modal-inscription">
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
                            <div class="form-group participant">
                                <label><strong>Nom du participant</strong></label>
                                <input name="participant" required class="form-control" value="{{ isset($inscription->participant) ? $inscription->participant->name : 'problème avec le participant' }}" type="text">
                            </div>
                            <div class="form-group participant">
                                <label><strong>Email du participant</strong></label>
                                <input name="email" class="form-control" value="{{ isset($inscription->participant) ? $inscription->participant->email : '' }}" type="text">
                            </div>
                        @endif

                        @if(!$inscription->colloque->prices->isEmpty())
                            @include('backend.inscriptions.partials.prices', ['select' => 'price_id', 'price_current' => $inscription->price->id, 'colloque' => $inscription->colloque])
                        @endif

                        <!-- Occurence if any -->
                        @if(!$inscription->colloque->occurrences->isEmpty())
                            <div class="inscription_set">
                                <h4>Conférences</h4>
                                @include('backend.inscriptions.partials.occurrences', ['select' => 'occurrences[]', 'colloque' => $inscription->colloque, 'inscription' => $inscription])
                            </div>
                        @endif

                        @if(!$inscription->colloque->options->isEmpty())
                            <div class="inscription_set">
                                <h4>Options</h4>
                                @include('backend.inscriptions.partials.options', ['select' => 'groupes', 'colloque' => $inscription->colloque, 'inscription' => $inscription])
                            </div>
                        @endif

                        <?php $user = ($inscription->group_id ? 'group_id' : 'user_id'); ?>

                        <input name="{{ $user }}" value="{{ $inscription->$user }}" type="hidden">
                        <input name="colloque_id" value="{{ $inscription->colloque->id }}" type="hidden">
                        <input name="payed_at" value="{{ $inscription->payed_at ? $inscription->payed_at->format('Y-m-d') : null }}" type="hidden">
                    </fieldset>

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