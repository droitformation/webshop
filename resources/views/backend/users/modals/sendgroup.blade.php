
<!-- Modal -->
<div class="modal fade" id="sendInscriptionGroup_{{ $group->id }}" tabindex="-1" role="dialog" aria-labelledby="sendInscription_">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/inscription/send') }}" method="POST" class="form" >
                {!! csrf_field() !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Envoyer les inscriptions
                        @if(!empty($group->participant_list))
                            <dl>
                                @foreach($group->participant_list as $no => $participant)
                                    <dt>{{ $participant }}</dt><dd>{{ $no }}</dd>
                                @endforeach
                            </dl>
                        @endif
                    </h4>
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
                    <input name="id" required value="{{ $group->id }}" type="hidden">
                    <input name="model" required value="group" type="hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->