
<!-- Modal -->
<div class="modal fade" id="sendInscription_{{ $inscription->id }}" tabindex="-1" role="dialog" aria-labelledby="sendInscription_">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/inscription/send') }}" method="POST" class="form" >
                {!! csrf_field() !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Envoyer {{ $inscription->inscription_no }}</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" required class="form-control" value="" type="text">
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    {!! Form::hidden('id', $inscription->id ) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->